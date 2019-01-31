<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\Const_;
use function Netmosfera\PHPParserTools\Names\isImplicitlyRelativeName;

function constantBaseIdentifiersInNode(Node $node): array{

    if(
        $node instanceof ConstFetch &&
        isImplicitlyRelativeName($node->name) &&
        count($node->name->parts) === 1
    ){
        return [$node->name->parts[0]];
    }

    if($node instanceof Const_){
        $allBaseIdentifiers = [];
        foreach($node->consts as $const){
            $allBaseIdentifiers[] = $const->name->toString();
        }
        return $allBaseIdentifiers;
    }

    return [];
}
