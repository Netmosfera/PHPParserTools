<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\normalizeFQNForComparison;

function baseIdentifierOfFQNInGroupUse(GroupUse $groupUse, Int $type, String $FQN): ?String{

    assert(isValidGlobalSymbolType($type));

    if($groupUse->type === Use_::TYPE_UNKNOWN){
        $filterTypeInUseUse = TRUE;
    }elseif($groupUse->type === $type){
        $filterTypeInUseUse = FALSE;
    }else{
        return NULL;
    }

    foreach($groupUse->uses as $useUse){
        if($filterTypeInUseUse && $useUse->type !== $type){ continue; }

        $compareFQN =
            implode("\\", $groupUse->prefix->parts) . "\\" .
            implode("\\", $useUse->name->parts);

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
