<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Nodes;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\While_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Nodes\setParentNodes;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;
use function Netmosfera\PHPParserToolsTests\parse;

class setParentNodesTest extends TestCase
{
    private function sampleNodes(){
        return parse("<?php
            namespace Foo\\Bar;
            class Baz{
                public function bar(){
                    for(;;){
                        if(TRUE){
                            while(FALSE){
                                echo '42';
                            }
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
            if($node instanceof Echo_){
                $saveNode = $node;
            }
        });

        assert($saveNode instanceof Node);

        $name = __CLASS__ . "Parent";

        setParentNodes($nodes, $name);

        self::assertInstanceOf(While_::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Node);

        self::assertInstanceOf(If_::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Node);

        self::assertInstanceOf(For_::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Node);

        self::assertInstanceOf(ClassMethod::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Node);

        self::assertInstanceOf(Class_::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Node);

        self::assertInstanceOf(Namespace_::CLASS, $saveNode = $saveNode->getAttribute($name));
        assert($saveNode instanceof Namespace_);

        self::assertNull($saveNode->getAttribute($name));
    }
}
