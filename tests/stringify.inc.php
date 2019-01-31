<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests;

use PhpParser\PrettyPrinter\Standard;

function stringify(array $nodes){
    return (new Standard())->prettyPrintFile($nodes);
}
