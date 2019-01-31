<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\NodeTraverser;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;

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
