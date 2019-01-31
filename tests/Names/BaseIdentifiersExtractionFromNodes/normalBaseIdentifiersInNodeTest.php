<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\TraitUse;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\normalBaseIdentifiersInNode;
use function Netmosfera\PHPParserToolsTests\parse;
use function str_split;

class normalBaseIdentifiersInNodeTest extends TestCase
{
    public function test_function_call(){
        $function = new FuncCall(new Name(["Bar", "foo"]));
        $expect = ["Bar"];
        $actual = normalBaseIdentifiersInNode($function);
        self::assertSame($expect, $actual);
    }

    public function test_constant_fetch(){
        $function = new ConstFetch(new Name(["Bar", "foo"]));
        $expect = ["Bar"];
        $actual = normalBaseIdentifiersInNode($function);
        self::assertSame($expect, $actual);
    }

    public function data_class_operator(){
        $className = new Name(["Foo", "Bar", "Baz"]);
        yield [new New_($className)];
        yield [new Class_("Foo")];
        yield [new Instanceof_(new LNumber(11), $className)];
        yield [new StaticPropertyFetch($className, "xxx")];
        yield [new ClassConstFetch($className, "XXX")];
        yield [new StaticCall($className, "xxx")];
    }

    /** @dataProvider data_class_operator */
    public function test_class_operator($classOperator){
        $expect = ["Foo"];
        $actual = normalBaseIdentifiersInNode($classOperator);
        self::assertSame($expect, $actual);
    }

    public function test_function_like(){
        $expect = str_split("ACDG");

        $function = parse("<?php
            function foo(
                A\\B         \$v1,
                C            \$v2,
                D\\E         \$v3,
                \\D\\E       \$v4,
                namespace\\F \$v5
            ): G{}
        ")[0];

        $actual = normalBaseIdentifiersInNode($function);
        self::assertSame($expect, $actual);
    }

    public function test_trait_uses(){
        $expect = ["Foo", "Baz", "Lol"];

        $t1 = new Name          (["Foo", "AAA"]);
        $t2 = new FullyQualified(["Bar", "AAA"]);
        $t3 = new Name          (["Baz", "AAA"]);
        $t4 = new Relative      (["Qux", "AAA"]);
        $t5 = new Name          (["Lol", "AAA"]);

        $traitUse = new TraitUse([$t1, $t2, $t3, $t4, $t5]);
        $actual = normalBaseIdentifiersInNode($traitUse);
        self::assertSame($expect, $actual);
    }

    public function test_no_result(){
        $expect = [];
        $node = new Echo_([new LNumber(42)]);
        $actual = normalBaseIdentifiersInNode($node);
        self::assertSame($expect, $actual);
    }
}
