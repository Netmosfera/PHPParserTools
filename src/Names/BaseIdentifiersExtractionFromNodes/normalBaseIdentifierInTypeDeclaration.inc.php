<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use function Netmosfera\PHPParserTools\Names\isImplicitlyRelativeName;

function normalBaseIdentifierInTypeDeclaration($type): ?String{
    assert(
        $type instanceof Identifier ||
        $type instanceof Name ||
        $type instanceof NullableType ||
        $type === NULL
    );

    if($type === NULL){
        return NULL;
    }

    if($type instanceof NullableType){
        $type = $type->type;
    }

    if($type instanceof Identifier){
        return $type->isSpecialClassName() ? NULL : $type->name;
    }

    assert($type instanceof Name);

    if(isImplicitlyRelativeName($type) && $type->isSpecialClassName() === FALSE){
        return $type->parts[0];
    }

    return NULL;
}
