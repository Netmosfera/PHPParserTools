<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;

/** @codeCoverageIgnore */
function isImplicitlyRelativeName($name){
    return
        $name instanceof Name &&
        $name instanceof Relative === FALSE &&
        $name instanceof FullyQualified === FALSE;
}
