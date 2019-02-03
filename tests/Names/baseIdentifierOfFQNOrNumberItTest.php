<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\baseIdentifierOfFQNOrNumberIt;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\baseIdentifierOfFQNInUses;
use function Netmosfera\PHPParserToolsTests\parse;

class baseIdentifierOfFQNOrNumberItTest extends TestCase
{
    const FN = Use_::TYPE_FUNCTION;

    function test_reuse_base_identifier(){
        $namespace = parse("<?php
            namespace Bar\\Baz;
            use function Baz\\Qux\\foo as boo;
        ")[0];

        $i = baseIdentifierOfFQNOrNumberIt($namespace, "Baz\\Qux\\foo", self::FN);
        self::assertSame("boo", $i);
    }

    function test_namespace_relative(){
        $namespace = parse("<?php
            namespace Bar\\Baz;
        ")[0];

        $i = baseIdentifierOfFQNOrNumberIt($namespace, "Bar\\Baz\\foo", self::FN);
        self::assertSame("foo", $i);
    }

    function test_generates_one_because_already_in_use_in_uses(){
        $namespace = parse("<?php
            namespace A\\B;
            use function C\\D\\x as boo;
            use function E\\F\\y as boo1;
            use function G\\H\\z as boo2;
        ")[0];

        $i = baseIdentifierOfFQNInUses(self::FN, "X\\boo", $namespace);
        self::assertSame(NULL, $i);

        $i = baseIdentifierOfFQNOrNumberIt($namespace, "X\\boo", self::FN);
        self::assertSame("boo3", $i);

        $i = baseIdentifierOfFQNInUses(self::FN, "X\\boo", $namespace);
        self::assertSame("boo3", $i);

    }

    function test_generates_one_because_already_in_use_in_actual_usages(){
        $namespace = parse("<?php
            namespace A\\B;
            boo(); boo1(); boo2();
        ")[0];

        $i = baseIdentifierOfFQNInUses(self::FN, "X\\boo", $namespace);
        self::assertSame(NULL, $i);

        $i = baseIdentifierOfFQNOrNumberIt($namespace, "X\\boo", self::FN);
        self::assertSame("boo3", $i);

        $i = baseIdentifierOfFQNInUses(self::FN, "X\\boo", $namespace);
        self::assertSame("boo3", $i);
    }


    function test_uses_basename(){
        $namespace = parse("<?php
            namespace A\\B;
        ")[0];

        $identifier = baseIdentifierOfFQNOrNumberIt($namespace, "X\\Y\\boo", self::FN);
        self::assertSame("boo", $identifier);
    }
}
