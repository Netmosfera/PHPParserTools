<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\functionBaseIdentifiersInNode as ff;

class functionBaseIdentifiersInNodeTest extends TestCase
{
    public function test_function_call(){
        $functionCall = new FuncCall(new Name(["foo"]));
        $expect = ["foo"];
        $actual = ff($functionCall);
        self::assertSame($expect, $actual);
    }

    public function test_qualified_function_call(){
        $functionCall = new FuncCall(new Name(["Bar", "foo"]));
        $expect = [];
        $actual = ff($functionCall);
        self::assertSame($expect, $actual);
    }

    public function test_fully_qualified_function_call(){
        $functionCall = new FuncCall(new FullyQualified(["foo"]));
        $expect = [];
        $actual = ff($functionCall);
        self::assertSame($expect, $actual);
    }

    public function test_relative_function_call(){
        $functionCall = new FuncCall(new Relative(["foo"]));
        $expect = [];
        $actual = ff($functionCall);
        self::assertSame($expect, $actual);
    }

    public function test_function_definition(){
        $function = new Function_("bubi", []);
        $expect = ["bubi"];
        $actual = ff($function);
        self::assertSame($expect, $actual);
    }

    public function test_no_results(){
        $echo = new Echo_([new LNumber(42)]);
        $expect = [];
        $actual = ff($echo);
        self::assertSame($expect, $actual);
    }
}
