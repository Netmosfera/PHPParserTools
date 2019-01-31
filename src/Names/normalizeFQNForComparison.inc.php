<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Stmt\Use_;
use function array_pop;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function strtolower;

function normalizeFQNForComparison(String $FQN, Int $type): String{
    assert(isValidGlobalSymbolType($type));

    $pieces = explode("\\", $FQN);

    $last = NULL;

    if($type === Use_::TYPE_CONSTANT){
        $last = array_pop($pieces);
    }

    foreach($pieces as $index => $piece){
        $pieces[$index] = strtolower($piece);
    }

    if($type === Use_::TYPE_CONSTANT){
        $pieces[] = $last;
    }

    return implode("\\", $pieces);
}
