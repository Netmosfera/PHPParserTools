<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\TraitUse;
use function Netmosfera\PHPParserTools\Names\isImplicitlyRelativeName;

function normalBaseIdentifiersInNode(Node $node): array{

    if(
        ($node instanceof FuncCall || $node instanceof ConstFetch) &&
        isImplicitlyRelativeName($node->name) &&
        count($node->name->parts) >= 2
    ){
        // - name->parts[0] in FuncCall --- only if count(name->parts) is > 1
        // - name->parts[0] in ConstFetch - only if count(name->parts) is > 1
        // because if it's one only then the base identifier is not TYPE_NORMAL
        return [$node->name->parts[0]];
    }

    if($node instanceof ClassLike && $node->name instanceof Identifier){
        return [$node->name->name];
    }

    if($node instanceof FunctionLike){
        return normalBaseIdentifiersInFunctionLikeSignature($node);
    }

    if(
        (
            $node instanceof New_ ||
            $node instanceof Instanceof_ ||
            $node instanceof StaticPropertyFetch ||
            $node instanceof ClassConstFetch ||
            $node instanceof StaticCall
        ) &&
        isImplicitlyRelativeName($node->class)
    ){
        return [$node->class->parts[0]];
    }

    if($node instanceof TraitUse){
        $identifiers = [];
        foreach($node->traits as $name){
            if(isImplicitlyRelativeName($name)){
                $identifiers[] = $name->parts[0];
            }
        }
        return $identifiers;
    }

    return [];
}
