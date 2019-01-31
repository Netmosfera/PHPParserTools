<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Nodes;

use PhpParser\Node;

/**
 * Links every {@see Node} to their respective parent node into the provided attribute.
 *
 * @param           Node[] $nodes
 * The PHP file's nodes or a part of it.
 *
 * @param           String $attributeName
 * The name of the attribute that will store the parent node; after the call, parent nodes
 * will be accessible through `$node->getAttribute($attributeName)`.
 */
function setParentNodes(Array $nodes, String $attributeName): void{
    $stack = [];
    visitNodes(
        $nodes,
        function(Node $node) use(&$stack, $attributeName){
            if($stack !== []){
                $node->setAttribute(
                    $attributeName,
                    $stack[count($stack) - 1]
                );
            }
            $stack[] = $node;
        },
        function(Node $node) use(&$stack){
            array_pop($stack);
        }
    );
}
