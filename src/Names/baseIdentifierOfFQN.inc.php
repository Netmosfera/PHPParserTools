<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\baseIdentifierOfFQNInUses;
use function strrpos;

function baseIdentifierOfFQN(Namespace_ $namespace, String $FQN, Int $type): ?String{

    assert(isValidGlobalSymbolType($type));

    // First we look into the `use`s  of the file;
    // if a base identifier for the given FQN exists, we can use that one for sure

    $existingBaseIdentifier = baseIdentifierOfFQNInUses($type, $FQN, $namespace);

    if($existingBaseIdentifier !== NULL){
        return $existingBaseIdentifier;
    }

    //------------------------------------------------------------------------------------

    // Otherwise, if `$namespace->name` equals `dirname($FQN)` then
    // `basename($FQN)` is usable already without the need of a "use;"

    // However, we need to take fallback to the root namespace into account,
    // if `$type` is constant or function.

    // The scenarios for existing code are the following:
    // 1- implicit namespace-relative reference relies on fallback to root namespace
    // 2- implicit namespace-relative reference points to to `$FQN`

    // However, since we are adding a reference to `$FQN`, we can deduce that `$FQN`
    // exist for sure; why would we call a function that does not exist?

    // The very fact that `$FQN` exists, and given that the scenario 2 has the precedence
    // over the scenario 1, the scenario 1 is actually impossible.

    // Therefore, we can use basename($FQN) as identifier:

    $FQNLastSeparator = strrpos($FQN, "\\");
    $FQNamespace = substr($FQN, 0, $FQNLastSeparator);
    $newBaseIdentifier = substr($FQN, $FQNLastSeparator + 1);
    if(
        normalizeFQNForComparison($namespace->name->toString(), Use_::TYPE_NORMAL) ===
        normalizeFQNForComparison($FQNamespace, Use_::TYPE_NORMAL)
    ){
        return $newBaseIdentifier;
    }

    // Or else, there no existing base identifier usable:

    return NULL;
}
