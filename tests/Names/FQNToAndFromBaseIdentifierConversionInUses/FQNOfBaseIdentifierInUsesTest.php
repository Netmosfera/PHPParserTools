<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\FQNOfBaseIdentifierInUses as ff;
use function Netmosfera\PHPParserToolsTests\parse;

class FQNOfBaseIdentifierInUsesTest extends TestCase
{
    public function test_use(){
        $namespace = parse("<?php namespace{
            use X\\Y\\Z;
            use function X\\Y\\Z;
            use const X\\Y\\Z;
            use A\\B\\C, A\\B\\D, A\\B\\E as QUX;
        }")[0];

        $FQN = ff(Use_::TYPE_NORMAL, "QUX", $namespace);

        self::assertSame("A\\B\\E", $FQN);
    }

    public function test_group_use(){
        $namespace = parse("<?php namespace{
            use X\\Y\\Z;
            use function X\\Y\\Z;
            use const X\\Y\\Z;
            use A\\B\\{C, D, E as QUX};
        }")[0];

        $FQN = ff(Use_::TYPE_NORMAL, "QUX", $namespace);

        self::assertSame("A\\B\\E", $FQN);
    }

    public function test_no_result(){
        $namespace = parse("<?php namespace{
            use X\\Y\\Z;
            use function X\\Y\\Z;
            use const X\\Y\\Z;
            use function A\\B\\{C, D, E as QUX};
        }")[0];

        $FQN = ff(Use_::TYPE_NORMAL, "QUX", $namespace);

        self::assertSame(NULL, $FQN);
    }
}
