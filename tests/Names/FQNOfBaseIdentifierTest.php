<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNOfBaseIdentifier;
use function Netmosfera\PHPParserToolsTests\parse;

class FQNOfBaseIdentifierTest extends TestCase
{
    public function test_uses(){
        $namespace = parse("<?php
            namespace Foo;
            use A\\B\\C as lol;
        ")[0];

        $FQN = FQNOfBaseIdentifier(Use_::TYPE_NORMAL, "lol", $namespace);

        self::assertSame("A\\B\\C", $FQN);
    }

    public function test_relative(){
        $namespace = parse("<?php namespace Foo\\Bar\\Baz; ")[0];

        $FQN = FQNOfBaseIdentifier(Use_::TYPE_NORMAL, "Qux", $namespace);

        self::assertSame("Foo\\Bar\\Baz\\Qux", $FQN);
    }
}
