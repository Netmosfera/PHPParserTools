<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Attributes;

use PhpParser\Node;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;

/**
 * Removes all the attributes with the specified `$name` from a tree of {@see Node}s.
 *
 * @param           Node[] $nodes
 * `Array<Int, Node>` The nodes from which the attribute is to be removed.
 *
 * @param           String $name
 * `String` The attribute name that is to be removed.
 */
function removeAttribute(Array $nodes, String $name): void{
    visitNodes($nodes, NULL, function(Node $node) use($name){
        $attributes = $node->getAttributes();
        unset($attributes[$name]);
        $node->setAttributes($attributes);
    });
}
