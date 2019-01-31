<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\Const_ as ConstConst;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Echo_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\constantBaseIdentifiersInNode as ff;

class constantBaseIdentifiersInNodeTest extends TestCase
{
    public function test_constant_fetch(){
        $constFetch = new ConstFetch(new Name(["FOO"]));
        $expect = ["FOO"];
        $actual = ff($constFetch);
        self::assertSame($expect, $actual);
    }

    public function test_qualified_constant_fetch(){
        $constFetch = new ConstFetch(new Name(["Bar", "foo"]));
        $expect = [];
        $actual = ff($constFetch);
        self::assertSame($expect, $actual);
    }

    public function test_fully_qualified_constant_fetch(){
        $constFetch = new ConstFetch(new FullyQualified(["FOO"]));
        $expect = [];
        $actual = ff($constFetch);
        self::assertSame($expect, $actual);
    }

    public function test_relative_constant_fetch(){
        $constFetch = new ConstFetch(new Relative(["FOO"]));
        $expect = [];
        $actual = ff($constFetch);
        self::assertSame($expect, $actual);
    }

    public function test_constant_definition(){
        $c1 = new ConstConst("BOBO", new LNumber(42));
        $c2 = new ConstConst("BUBI", new LNumber(42));
        $c3 = new ConstConst("BOBI", new LNumber(42));
        $constFetch = new Const_([$c1, $c2, $c3]);
        $expect = ["BOBO", "BUBI", "BOBI"];
        $actual = ff($constFetch);
        self::assertSame($expect, $actual);
    }

    public function test_no_results(){
        $echo = new Echo_([new LNumber(42)]);
        $expect = [];
        $actual = ff($echo);
        self::assertSame($expect, $actual);
    }
}
