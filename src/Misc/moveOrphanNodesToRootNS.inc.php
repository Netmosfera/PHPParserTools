<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Misc;

use PhpParser\Node;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;

/**
 * Moves non-namespaced nodes into a `namespace{}` statement.
 *
 * Performing this step makes traversing the AST much easier.
 *
 * @param           Node[] $nodes
 * `Array<Int, Node>` The PHP file's nodes.
 *
 * @return          Node[]
 * `Array<Int, Node>` The modified PHP file's nodes.
 */
function moveOrphanNodesToRootNS(array $nodes): array{

    $newNodes = [];
    $orphanNodes = [];

    foreach($nodes as $node){
        if($node instanceof Declare_){
            $newNodes[] = $node;
        }elseif($node instanceof Namespace_){
            $newNodes[] = $node;
        }else{
            $orphanNodes[] = $node;
        }
    }

    if($orphanNodes !== []){
        $newNodes[] = new Namespace_(NULL, $orphanNodes);
    }

    return $newNodes;
}
