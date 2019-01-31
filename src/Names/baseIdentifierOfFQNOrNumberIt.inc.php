<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Stmt\Namespace_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\FQNOfBaseIdentifierInUses;
use function strrpos;

function baseIdentifierOfFQNOrNumberIt(
    Namespace_ $namespace,
    String $FQN,
    Int $type
): String{

    assert(isValidGlobalSymbolType($type));

    $existingBaseIdentifier = baseIdentifierOfFQN($namespace, $FQN, $type);

    if($existingBaseIdentifier !== NULL){
        return $existingBaseIdentifier;
    }

    // If there is no existing usable base identifier one must be generated

    // Now, we need to make sure that the the base identifier is not in use in the file.
    // For example if we are adding `use function Bar\Baz\foo`, we need to make sure that:

    $newBaseIdentifier = NULL;
    $count = 2;
    $lastSeparator = strrpos($FQN, "\\");
    $lastSeparator = $lastSeparator === FALSE ? 0 : $lastSeparator;
    $baseName = substr($FQN, $lastSeparator + 1);

    while(TRUE){
        if($newBaseIdentifier === NULL){
            $newBaseIdentifier = $baseName;
        }else{
            $newBaseIdentifier = $baseName . $count++;
        }

        // 1- there is no pre-existing `use function foo` or `use function bar as foo`

        $existingFQN = FQNOfBaseIdentifierInUses($type, $newBaseIdentifier, $namespace);
        if($existingFQN !== NULL){ continue; }

        // 2- there is no `foo()` call in the code: we don't want the existing unqualified
        //    calls to point to something else.

        $isInUse = isBaseIdentifierActuallyInUse($namespace, $type, $newBaseIdentifier);
        if($isInUse){ continue; }

        // In both cases, if a conflict is found, try again with a different identifier

        // Otherwise return the found identifier

        break;
    }

    return $newBaseIdentifier;
}
