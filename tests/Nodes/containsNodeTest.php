<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Nodes;

use PhpParser\Node;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Nodes\containsNode;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;
use function Netmosfera\PHPParserToolsTests\parse;

class containsNodeTest extends TestCase
{
    private function sampleNodes(){
        return parse("<?php
            namespace Foo\\Bar;
            class Baz{
                public function bar(){
                    for(;;){
                        if(TRUE){
                            echo '42';
                            echo '42';
                            echo '42';
                            print '44';
                            echo '42';
                            echo '42';
                        }
                    }
                }
            }
        ");
    }

    public function test(){
        $nodes = $this->sampleNodes();

        $saveNode = NULL;

        visitNodes($nodes, function(Node $node) use(&$saveNode){
            if($node instanceof Print_){
                $saveNode = $node;
            }
        });

        self::assertFalse(containsNode($nodes, new Echo_([new LNumber(42)])));

        self::assertTrue(containsNode($nodes, $saveNode));
    }
}
