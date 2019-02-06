<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\CodeGeneration;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;

function TRUE_(){
    return Bool_(true);
}

function FALSE_(){
    return Bool_(false);
}

function Bool_(Bool $bool): ConstFetch{
    return new ConstFetch(new Name($bool ? "TRUE" : "FALSE"));
}
