<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\FunctionLike;

function normalBaseIdentifiersInFunctionLikeSignature(FunctionLike $function): array{

    $baseIdentifiers = [];

    foreach($function->getParams() as $param){
        $baseIdentifier = normalBaseIdentifierInTypeDeclaration($param->type);
        if($baseIdentifier !== NULL){
            $baseIdentifiers[] = $baseIdentifier;
        }
    }

    $baseIdentifier = normalBaseIdentifierInTypeDeclaration($function->getReturnType());
    if($baseIdentifier !== NULL){
        $baseIdentifiers[] = $baseIdentifier;
    }

    return $baseIdentifiers;
}
