<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\CodeGeneration;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;

function variable($name){
    return new Variable($name);
}

function true_(){
    return bool_(true);
}

function false_(){
    return bool_(false);
}

function bool_(Bool $bool): ConstFetch{
    return new ConstFetch(new FullyQualified($bool ? "TRUE" : "FALSE"));
}

function arg(Expr $arg, Bool $byRef = FALSE, Bool $unpack = FALSE): Arg{
    return new Arg($arg, $byRef, $unpack);
}

function fnCall($name, Array $arguments){
    if(is_string($name)){
        if(substr($name, 0, 1) === "\\"){
            $name = new FullyQualified(substr($name, 1));
        }else{
            $name = new Name($name);
        }
    }
    return new FuncCall($name, $arguments);
}
