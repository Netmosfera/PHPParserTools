<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;

function classNameConstFetch($name): Expr{

    if($name instanceof Identifier){
        $name = new Name($name->name);
    }

    if(count($name->parts) === 1){
        $nameString = strtolower($name->parts[0]);
        if($nameString === "callable" || $nameString === "array"){
            return new String_($nameString);
        }
    }

    return new ClassConstFetch($name, "CLASS");
}
