<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Attributes;

use PhpParser\Node;
use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Attributes\containsAttribute;
use function Netmosfera\PHPParserTools\Attributes\newAttribute;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;
use function Netmosfera\PHPParserToolsTests\parse;

class newAttributeTest extends TestCase
{
    private function sampleNodes(){
        return parse("<?php
            namespace Foo\\Bar;
            class Baz{
                public function bar(){
                    for(;;){
                        if(TRUE){
                            echo '42'; // 0
                            echo '42'; // 1
                            echo '42'; // 2
                            echo '42'; // 3
                            echo '42'; // 4
                        }
                    }
                }
            }
        ");
    }

    public function test(){
        $nodes = $this->sampleNodes();

        $count1 = 0;
        visitNodes($nodes, function(Node $node) use(&$count1){
            if($node instanceof Echo_){
                $node->setAttribute("prefix_" . $count1++, 42);
            }
        });

        $count2 = 0;
        $newAttribute = newAttribute($nodes, "prefix_", function() use(&$count2){
            return $count2++;
        });

        self::assertTrue(containsAttribute($nodes, "prefix_0"));
        self::assertTrue(containsAttribute($nodes, "prefix_1"));
        self::assertTrue(containsAttribute($nodes, "prefix_2"));
        self::assertTrue(containsAttribute($nodes, "prefix_3"));
        self::assertTrue(containsAttribute($nodes, "prefix_4"));

        self::assertSame("prefix_5", $newAttribute);
    }
}
