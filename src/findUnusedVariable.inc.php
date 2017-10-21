<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Node\Expr\Variable;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function findUnusedVariable(Array $nodes, String $variableName = "var"){
    $variableNameSuffix = "";
    $countOccupiedVariables = 2;
    do{
        $variableNameIsUsedAlready = FALSE;
        visitNodes($nodes, function(Node $node) use(
            &$variableName,
            &$variableNameIsUsedAlready,
            &$variableNameSuffix,
            &$countOccupiedVariables
        ){
            if(
                $node instanceof Variable &&
                $node->name === $variableName . $variableNameSuffix
            ){
                $variableNameIsUsedAlready = TRUE;
                $variableNameSuffix = (String)$countOccupiedVariables++;
                return NodeTraverser::STOP_TRAVERSAL;
            }
        });
    }while($variableNameIsUsedAlready);

    return $variableName . $variableNameSuffix;
}
