<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Function_;
use function Netmosfera\PHPParserTools\Names\isImplicitlyRelativeName;

function functionBaseIdentifiersInNode(Node $node): array{

    if(
        $node instanceof FuncCall &&
        isImplicitlyRelativeName($node->name) &&
        count($node->name->parts) === 1
    ){
        return [$node->name->parts[0]];
    }

    if($node instanceof Function_){
        return [$node->name->toString()];
    }

    return [];
}
