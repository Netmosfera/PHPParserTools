<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use Closure;
use PhpParser\Node;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Walks through the ancestors of `$searchNode`, and returns the first ancestor that matches
 * the provided predicate function.
 *
 * @throws
 *
 * @param           Node[]                                  $nodes                          `Array<Int, Node>`
 * @TODOC
 *
 * @param           Node                                    $searchNode                     `Node`
 * @TODOC
 *
 * @param           Closure                                 $predicate                      `Closure`
 * @TODOC
 *
 * @return          Node|NULL                                                               `Node|NULL`
 * @TODOC
 */
function findClosestAncestor(Array $nodes, Node $searchNode, Closure $predicate): ?Node{
    if(contains($nodes, $searchNode) === FALSE){
        throw new Error("The provided `Node` is not a descendant of the given tree");
    }

    $attributeName = findUnusedAttribute($nodes);
    $nodes = resolveParents($nodes, $attributeName);

    RECURSE:
    $parentNode = $searchNode->getAttribute($attributeName);

    if($parentNode === NULL || $predicate($parentNode)){
        removeAttribute($nodes, $attributeName);
        return $parentNode;
    }

    $searchNode = $parentNode;
    goto RECURSE;
}
