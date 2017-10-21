<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\NodeTraverser;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Returns `TRUE` if the provided tree of {@see Node}s contains the given attribute name.
 *
 * @param           Node[]                                  $nodes                          `Array<Int, Node>`
 * The tree in which the given attribute name is to be searched.
 *
 * @param           String                                  $searchAttribute                `String`
 * The attribute name that is to be searched.
 *
 * @return          Bool                                                                    `Bool`
 * Returns `TRUE` if the provided tree of {@see Node}s contains the given attribute name.
 */
function containsAttribute(Array $nodes, String $searchAttribute): Bool{
    $contains = FALSE;
    visitNodes($nodes, function(Node $node) use(&$contains, &$searchAttribute){
        if($node->hasAttribute($searchAttribute)){
            $contains = TRUE;
            return NodeTraverser::STOP_TRAVERSAL;
        }
    });
    return $contains;
}
