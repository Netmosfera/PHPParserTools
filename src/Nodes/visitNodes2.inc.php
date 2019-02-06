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
 * @param           Closure|NULL $visitor
 * `Closure2<Bool, Node, NULL|Node|Array<Int, Node>>|NULL`
 *
 * @return          Node[]
 * `Array<Int, Node>`
 */
function visitNodes2(Array $nodes, Closure $visitor ): array{

    $traverser = new NodeTraverser();

    $traverser->addVisitor(
        new class($visitor) extends NodeVisitorAbstract{
            private $visitor;

            function __construct(Closure $visitor){
                $this->visitor = $visitor;
            }

            function enterNode(Node $node){
                return ($this->visitor)(TRUE, $node);
            }

            function leaveNode(Node $node){
                return ($this->visitor)(FALSE, $node);
            }
        }
    );

    return $traverser->traverse($nodes);
}
