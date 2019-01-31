<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;

/**
 * @TODOC
 *
 * @param           Int $type
 *
 * @param           String $FQN
 *
 * @param           Namespace_ $withinNamespace
 *
 * @return          String
 */
function baseIdentifierOfFQNInUses(
    Int $type,
    String $FQN,
    Namespace_ $withinNamespace
): ?String{

    assert(isValidGlobalSymbolType($type));

    $identifier = NULL;

    foreach($withinNamespace->stmts as $node){
        if($node instanceof Use_){
            $identifier = baseIdentifierOfFQNInUse($node, $type, $FQN);
        }elseif($node instanceof GroupUse){
            $identifier = baseIdentifierOfFQNInGroupUse($node, $type, $FQN);
        }
        if($identifier !== NULL){
            return $identifier;
        }
    }

    return NULL;
}
