<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\baseIdentifierOfFQN;
use function Netmosfera\PHPParserToolsTests\parse;

class baseIdentifierOfFQNTest extends TestCase
{
    public function test_existing_identifier_in_uses(){
        $namespace = parse("<?php namespace Foo; use A\\B\\C as LoL; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "A\\B\\C", Use_::TYPE_NORMAL
        );
        self::assertSame("LoL", $identifier);
    }

    public function test_base_identifier_in_uses_has_precedence_over_relative_name(){
        $namespace = parse("<?php namespace A\\B; use A\\B\\C as LoL; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "A\\B\\C", Use_::TYPE_NORMAL
        );
        self::assertSame("LoL", $identifier);
    }

    public function test_relative_name(){
        $namespace = parse("<?php namespace A\\B; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "A\\B\\C", Use_::TYPE_NORMAL
        );
        self::assertSame("C", $identifier);
    }

    public function test_relative_name_one_part(){
        $namespace = parse("<?php namespace{}; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "C", Use_::TYPE_NORMAL
        );
        self::assertSame("C", $identifier);
    }

    public function test_no_existing_identifiers(){
        $namespace = parse("<?php namespace A\\B; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "C\\D\\E", Use_::TYPE_NORMAL
        );
        self::assertSame(NULL, $identifier);
    }

    public function test_no_existing_identifiers_one_part(){
        $namespace = parse("<?php namespace A\\B\\C; ")[0];
        $identifier = baseIdentifierOfFQN(
            $namespace, "C", Use_::TYPE_NORMAL
        );
        self::assertSame(NULL, $identifier);
    }
}
