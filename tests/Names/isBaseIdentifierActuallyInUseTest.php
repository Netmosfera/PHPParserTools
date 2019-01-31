<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\isBaseIdentifierActuallyInUse as ff;
use function Netmosfera\PHPParserToolsTests\parse;

class isBaseIdentifierActuallyInUseTest extends TestCase
{
    public function test_normal(){
        $namespace = parse("<?php namespace{
            echo 123; lol(); baz(); 
            if(TRUE){ function qux(){} }
            echo Bar\\foo();
        }")[0];

        $inUse = ff($namespace, Use_::TYPE_NORMAL, "bar");

        self::assertSame(TRUE, $inUse);
    }

    public function test_function(){
        $namespace = parse("<?php namespace{
            echo 123; bar(); baz(); 
            if(TRUE){ function foo(){} }
        }")[0];

        $inUse = ff($namespace, Use_::TYPE_FUNCTION, "FoO");

        self::assertSame(TRUE, $inUse);
    }

    public function test_constant(){
        $namespace = parse("<?php namespace{
            echo 123; bar(); baz(); 
            if(TRUE){ function qux(){} }
            const FoO = 123;
        }")[0];

        $inUse = ff($namespace, Use_::TYPE_CONSTANT, "FoO");

        self::assertSame(TRUE, $inUse);
    }

    public function test_not_in_use(){
        $namespace = parse("<?php namespace{
            echo 123; bar(); baz(); 
            if(TRUE){ function qux(){} }
        }")[0];

        $inUse = ff($namespace, Use_::TYPE_FUNCTION, "FoO");

        self::assertSame(FALSE, $inUse);
    }
}
