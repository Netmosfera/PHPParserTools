<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Stmt\Namespace_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\FQNOfBaseIdentifierInUses;

function FQNOfBaseIdentifier(
    Int $type,
    String $baseIdentifier,
    Namespace_ $withinNamespace
): String{
    assert(isValidGlobalSymbolType($type));

    $FQN = FQNOfBaseIdentifierInUses($type, $baseIdentifier, $withinNamespace);

    if($FQN === NULL){
        $pieces = $withinNamespace->name->parts;
        $pieces[] = $baseIdentifier;
        $FQN = implode("\\", $pieces);
    }

    return $FQN;
}
