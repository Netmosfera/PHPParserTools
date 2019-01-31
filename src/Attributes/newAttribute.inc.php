<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Attributes;

use Closure;
use PhpParser\Node;
use const PHP_INT_MAX;
use function random_int;

/**
 * Returns an attribute name that isn't in use in the given tree of {@see Node}s.
 *
 * @param           Node[] $nodes
 * `Array<Int, Node>` The tree of {@see Node}s where to check for existing attributes.
 *
 * @param           String $prefix
 * `String` The attribute's prefix; more uncommon prefixes make this function run faster.
 *
 * @param           Closure|NULL $randomIntGenerator
 * `Closure():Int` Generates a random number, defaults to PHP's {@see random_int()}.
 *
 * @return          String
 * `String` Returns an attribute name that isn't in use in the given tree of {@see Node}s.
 */
function newAttribute(
    Array $nodes,
    String $prefix = "attribute",
    ?Closure $randomIntGenerator = NULL
): String{
    /** @codeCoverageIgnore */
    $randomIntGenerator = $randomIntGenerator ?? function(){
        return random_int(0, PHP_INT_MAX);
    };

    do{
        $searchAttribute = $prefix . $randomIntGenerator();
    }while(containsAttribute($nodes, $searchAttribute));

    return $searchAttribute;
}
