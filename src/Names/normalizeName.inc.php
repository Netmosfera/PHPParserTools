<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;

function normalizeName($name): array{
    assert(
        $name instanceof Name ||
        $name instanceof Identifier ||
        $name instanceof NullableType ||
        $name === NULL
    );

    if($name === NULL){
        return [NULL, NULL];
    }

    $isNullable = $name instanceof NullableType;

    $type = $isNullable ? $name->type : $name;

    $type = $type instanceof Identifier ? new Name($type->name) : $type;

    return [$type, $isNullable];
}
