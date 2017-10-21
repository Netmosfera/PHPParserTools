<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\NodeTraverser;
use function random_int;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function findUnusedAttribute(Array $nodes): String{
    do{
        $searchAttribute = "attribute" . random_int(0, PHP_INT_MAX);
        $attributeNameIsUsedAlready = FALSE;
        visitNodes($nodes, NULL, function(Node $node) use(
            &$searchAttribute,
            &$attributeNameIsUsedAlready
        ){
            if($node->hasAttribute($searchAttribute)){
                NodeTraverser::STOP_TRAVERSAL;
                $attributeNameIsUsedAlready = TRUE;
            }
        });
    } while($attributeNameIsUsedAlready);
    return $searchAttribute;
}
