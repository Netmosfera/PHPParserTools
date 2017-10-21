<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Declare_;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function visitNamespaceChildNodes(Array $nodes, Closure $visitor): Array{

    $visitStatements = function(Array $statements, Closure $visitor){
        $newStatements = [];
        foreach($statements as $statement){
            foreach($visitor($statement) as $newStatement){
                $newStatements[] = $newStatement;
            }
        }
        return $newStatements;
    };

    $i = 0;

    if(@$nodes[$i] instanceof Declare_) $i++;

    if(@$nodes[$i] instanceof Namespace_){
        /** @var Namespace_[] $nodes */
        for(; $i < count($nodes); $i++){
            $nodes[$i]->stmts = $visitStatements($nodes[$i]->stmts, $visitor);
        }
    }else{
        $newStatements = $visitStatements(array_slice($nodes, $i), $visitor);
        array_splice($nodes, $i, count($nodes) - $i, $newStatements);
    }

    return $nodes;
}
