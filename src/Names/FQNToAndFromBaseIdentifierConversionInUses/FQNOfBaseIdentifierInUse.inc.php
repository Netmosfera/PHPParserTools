<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;

function FQNOfBaseIdentifierInUse(
    Use_ $use,
    Int $type,
    String $baseIdentifier
): ?String{

    assert(isValidGlobalSymbolType($type));

    if($use->type !== $type){
        return NULL;
    }

    if($type !== Use_::TYPE_CONSTANT){
        $baseIdentifier = strtolower($baseIdentifier);
    }

    foreach($use->uses as $useUse){

        $compare = (String)(
            $useUse->alias ?? $useUse->name->getLast()
        );

        if($type !== Use_::TYPE_CONSTANT){
            $compare = strtolower($compare);
        }

        if($compare === $baseIdentifier){
            return $useUse->name->toString();
        }
    }

    return NULL;
}
