<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Attributes;

use PhpParser\Node;
use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Attributes\containsAttribute;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;
use function Netmosfera\PHPParserToolsTests\parse;

class containsAttributeTest extends TestCase
{
    private function sampleNodes(){
        return parse("<?php
            namespace Foo\\Bar;
            class Baz{
                public function bar(){
                    for(;;){
                        if(TRUE){
                            echo '42';
                        }
                    }
                }
            }
        ");
    }

    public function test(){
        $nodes = $this->sampleNodes();

        self::assertFalse(containsAttribute($nodes, "rofl"));

        visitNodes($nodes, function(Node $node){
            if($node instanceof Echo_){
                $node->setAttribute("rofl", 42);
            }
        });

        self::assertTrue(containsAttribute($nodes, "rofl"));
    }
}
