<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function resolveParents(Array $nodes, String $useAttributeName): Array{
    if(containsAttribute($nodes, $useAttributeName)){
        throw new Error("The provided attribute name `$useAttributeName` is already in use");
    }
    $stack = [];
    return visitNodes(
        $nodes,
        function(Node $node) use(&$stack, &$useAttributeName){
            if($stack !== []){
                $node->setAttribute(
                    $useAttributeName,
                    $stack[count($stack) - 1]
                );
            }
            $stack[] = $node;
        },
        function(Node $node) use(&$stack){
            array_pop($stack);
        }
    );
}
