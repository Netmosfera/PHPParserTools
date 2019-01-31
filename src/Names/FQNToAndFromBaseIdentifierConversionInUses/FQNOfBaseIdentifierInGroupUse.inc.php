<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;

function FQNOfBaseIdentifierInGroupUse(
    GroupUse $groupUse,
    Int $type,
    String $baseIdentifier
): ?String{

    assert(isValidGlobalSymbolType($type));

    if($groupUse->type === Use_::TYPE_UNKNOWN){
        $doVerifyTypesInUseUse = TRUE;
    }else{
        if($groupUse->type === $type){
            $doVerifyTypesInUseUse = FALSE;
        }else{
            return NULL;
        }
    }

    if($type !== Use_::TYPE_CONSTANT){
        $baseIdentifier = strtolower($baseIdentifier);
    }

    foreach($groupUse->uses as $useUse){
        if($doVerifyTypesInUseUse && $useUse->type !== $type){
            continue;
        }

        $compare = (String)(
            $useUse->alias ?? $useUse->name->getLast()
        );

        if($type !== Use_::TYPE_CONSTANT){
            $compare = strtolower($compare);
        }

        if($compare === $baseIdentifier){
            return
                implode("\\", $groupUse->prefix->parts) . "\\" .
                implode("\\", $useUse->name->parts);
        }
    }

    return NULL;
}
