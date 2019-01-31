<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests;

use PhpParser\ParserFactory;

function parse(String $string){
    return (new ParserFactory())->create(ParserFactory::ONLY_PHP7)->parse($string);
}
