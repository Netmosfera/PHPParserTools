<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Nodes;

use PhpParser\Node;
use PhpParser\NodeTraverser;

/**
 * Returns `TRUE` if the provided tree of {@see Node}s contains the given {@see Node}.
 *
 * @param           Node[] $nodes
 * `Array<Int, Node>` The tree in which the given {@see Node} is to be searched.
 *
 * @param           Node $searchNode
 * `Node` The {@see Node} that is to be searched.
 *
 * @return          Bool
 * `Bool` Returns `TRUE` if the provided tree contains the given {@see Node}.
 */
function containsNode(Array $nodes, Node $searchNode): Bool{
    $contains = FALSE;

    visitNodes($nodes, function(Node $node) use(&$contains, &$searchNode){
        if($node === $searchNode){
            $contains = TRUE;
            return NodeTraverser::STOP_TRAVERSAL;
        }
    });

    return $contains;
}
