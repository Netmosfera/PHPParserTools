<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\normalizeFQNForComparison as ff;

class normalizeFQNForComparisonTest extends TestCase
{
    function test(){
        self::assertSame("a\\b\c", ff("A\\B\\C", Use_::TYPE_NORMAL));
        self::assertSame("a\\b\c", ff("A\\B\\C", Use_::TYPE_FUNCTION));
        self::assertSame("a\\b\C", ff("A\\B\\C", Use_::TYPE_CONSTANT));

        self::assertSame("c", ff("C", Use_::TYPE_NORMAL));
        self::assertSame("c", ff("C", Use_::TYPE_FUNCTION));
        self::assertSame("C", ff("C", Use_::TYPE_CONSTANT));
    }
}
