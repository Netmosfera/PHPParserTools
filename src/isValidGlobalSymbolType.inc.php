<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools;

use PhpParser\Node\Stmt\Use_;

/** @codeCoverageIgnore */
function isValidGlobalSymbolType(Int $type){
    return
        $type === Use_::TYPE_NORMAL ||
        $type === Use_::TYPE_FUNCTION||
        $type === Use_::TYPE_CONSTANT;
}
