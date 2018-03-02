<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 *
 * @param           Node[]                                  $nodes                          ``
 * @TODOC
 *
 * @return          Node[]                                                                  ``
 * @TODOC
 */
function adoptOrphanStmts(Array $nodes): Array{
    $hasNamespaces = FALSE;
    $nodes = visitNodes($nodes, function(Node $node) use(&$hasNamespaces){
        if($node instanceof Namespace_){
            $hasNamespaces = TRUE;
            return NodeTraverser::STOP_TRAVERSAL;
        }
    });

    if($hasNamespaces === FALSE){
        $namespacedStatements = [];
        $newNodes = [];
        foreach($nodes as $node){
            if($node instanceof Declare_){
                $newNodes[] = $node;
            }else{
                $namespacedStatements[] = $node;
            }
        }
        $namespace = new Namespace_(NULL, $namespacedStatements);
        $newNodes[] = $namespace;
        $nodes = $newNodes;
    }

    return $nodes;
}
