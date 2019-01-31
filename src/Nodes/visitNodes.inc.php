<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Nodes;

use Closure;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * @TODOC
 *
 * @param           Node[] $nodes
 * `Array<Int, Node>`
 *
 * @param           Closure|NULL $enterNode
 * `Closure1<Node, NULL|Node|Array<Int, Node>>|NULL`
 *
 * @param           Closure|NULL $leaveNode
 * `Closure1<Node, NULL|Node|Array<Int, Node>>|NULL`
 *
 * @return          Node[]
 * `Array<Int, Node>`
 */
function visitNodes(
    Array $nodes,
    ?Closure $enterNode = NULL,
    ?Closure $leaveNode = NULL
): array{
    $enterNode = $enterNode ?? function(){};
    $leaveNode = $leaveNode ?? function(){};
    $traverser = new NodeTraverser();
    $traverser->addVisitor(
        new class($enterNode, $leaveNode) extends NodeVisitorAbstract{
            private $enterNodeFunction;

            private $leaveNodeFunction;

            function __construct(Closure $enterNode, Closure $leaveNode){
                $this->enterNodeFunction = $enterNode;
                $this->leaveNodeFunction = $leaveNode;
            }

            function enterNode(Node $node){
                return ($this->enterNodeFunction)($node);
            }

            function leaveNode(Node $node){
                return ($this->leaveNodeFunction)($node);
            }
        }
    );
    return $traverser->traverse($nodes);
}
