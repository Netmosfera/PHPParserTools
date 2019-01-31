<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\normalizeFQNForComparison;

function baseIdentifierOfFQNInUse(Use_ $use, Int $type, String $FQN): ?String{
    assert(isValidGlobalSymbolType($type));

    if($use->type !== $type){
        return NULL;
    }

    foreach($use->uses as $useUse){
        $compareFQN = implode("\\", $useUse->name->parts);

        if(
            normalizeFQNForComparison($FQN, $type) ===
            normalizeFQNForComparison($compareFQN, $type)
        ){
            return (String)(
                $useUse->alias ?? $useUse->name->getLast()
            );
        }
    }

    return NULL;
}
