<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use Exception;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;

/**
 * Returns the AST for `ProvidedName::CLASS` or equivalent.
 *
 * @throws          Exception
 *
 * @param           Name|Identifier|NULL $name
 *
 * @param           Bool $allowSelf
 *
 * @param           Bool $allowParent
 *
 * @param           Bool $allowStatic
 *
 * @return          Expr
 */
function classNameConstFetch(
    $name,
    Bool $allowSelf = TRUE,
    Bool $allowParent = TRUE,
    Bool $allowStatic = TRUE
): Expr{

    if($name instanceof Identifier){
        $name = new Name($name->name);
    }

    if(count($name->parts) === 1){
        $nameString = strtolower($name->parts[0]);

        if($nameString === "self" && !$allowSelf){
            throw new Exception("`self::CLASS` is disallowed");
        }

        if($nameString === "parent" && !$allowParent){
            throw new Exception("`parent::CLASS` is disallowed");
        }

        if($nameString === "static" && !$allowStatic){
            throw new Exception("`static::CLASS` is disallowed");
        }

        if($nameString === "void"){
            throw new Exception("`void::CLASS` is disallowed");
        }

        if($nameString === "callable" || $nameString === "array"){
            return new String_($nameString);
        }
    }

    return new ClassConstFetch($name, "CLASS");
}
