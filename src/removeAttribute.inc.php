<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function removeAttribute(Array $nodes, ?String $name = NULL): Array{
    return visitNodes($nodes, NULL, function(Node $node) use($name){
        $attributes = $node->getAttributes();
        unset($attributes[$name]);
        $node->setAttributes($attributes);
    });
}
