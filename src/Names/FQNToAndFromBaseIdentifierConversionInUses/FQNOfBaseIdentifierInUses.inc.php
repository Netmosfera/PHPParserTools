<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;

function FQNOfBaseIdentifierInUses(
    Int $type,
    String $baseIdentifier,
    Namespace_ $withinNamespace
): ?String{

    assert(isValidGlobalSymbolType($type));

    $FQN = NULL;

    foreach($withinNamespace->stmts as $node){
        if($node instanceof Use_){
            $FQN = FQNOfBaseIdentifierInUse($node, $type, $baseIdentifier);
        }elseif($node instanceof GroupUse){
            $FQN = FQNOfBaseIdentifierInGroupUse($node, $type, $baseIdentifier);
        }
        if($FQN !== NULL){
            return $FQN;
        }
    }

    return NULL;
}
