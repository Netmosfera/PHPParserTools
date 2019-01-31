<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use function Netmosfera\PHPParserTools\Names\baseIdentifierOfFQNOrNumberIt as ff;
use function Netmosfera\PHPParserToolsTests\parse;
use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;

class baseIdentifierOfFQNOrNumberItTest extends TestCase
{
    function test_reuse_base_identifier(){
        $namespace = parse("<?php
            namespace Bar\\Baz;
            use function Baz\\Qux\\foo as boo;
        ")[0];

        $identifier = ff($namespace, "Baz\\Qux\\foo", Use_::TYPE_FUNCTION);

        self::assertSame("boo", $identifier);
    }

    function test_generates_one_because_already_in_use_in_uses(){
        $namespace = parse("<?php
            namespace A\\B;
            use function C\\D\\x as boo;
            use function E\\F\\y as boo1;
            use function G\\H\\z as boo2;
        ")[0];

        $identifier = ff($namespace, "X\\boo", Use_::TYPE_FUNCTION);

        self::assertSame("boo3", $identifier);
    }

    function test_generates_one_because_already_in_use_in_actual_usages(){
        $namespace = parse("<?php
            namespace A\\B;
            
            boo(); boo1(); boo2();
        ")[0];

        $identifier = ff($namespace, "X\\boo", Use_::TYPE_FUNCTION);

        self::assertSame("boo3", $identifier);
    }


    function test_uses_basename(){
        $namespace = parse("<?php
            namespace A\\B;
        ")[0];

        $identifier = ff($namespace, "X\\Y\\boo", Use_::TYPE_FUNCTION);

        self::assertSame("boo", $identifier);
    }
}
