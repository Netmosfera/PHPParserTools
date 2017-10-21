<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 *
 * @param           Node[]                                  $nodes                          `Array<Int, Node>`
 * @TODOC
 *
 * @param           Closure|NULL                            $enterNode                      `Closure|NULL`
 * @TODOC
 *
 * @param           Closure|NULL                            $leaveNode                      `Closure|NULL`
 * @TODOC
 *
 * @return          Node[]                                                                  `Array<Int, Node>`
 * @TODOC
 */
function visitNodes(Array $nodes, ?Closure $enterNode = NULL, ?Closure $leaveNode = NULL): Array{
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
