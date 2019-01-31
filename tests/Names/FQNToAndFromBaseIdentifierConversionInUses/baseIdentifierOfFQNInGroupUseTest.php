<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\baseIdentifierOfFQNInGroupUse as ff;
use function Netmosfera\PHPParserToolsTests\parse;

/**
 * #0  | class | ok | nonmixed | (+ tests that case insensitive)
 * #1  | class | ok | mixed    | (+ tests that case insensitive)
 * #2  | class | no | nonmixed | wrong type
 * #3  | class | no | mixed    | wrong type
 *
 * #4  | fn    | ok | nonmixed | (+ tests that case insensitive)
 * #5  | fn    | ok | mixed    | (+ tests that case insensitive)
 * #6  | fn    | no | nonmixed | wrong type
 * #7  | fn    | no | mixed    | wrong type
 *
 * #8  | const | ok | nonmixed | (+ tests that namespace is case insensitive)
 * #9  | const | ok | mixed    | (+ tests that namespace is case insensitive)
 * #10 | const | no | nonmixed | NS is identical but base identifier's case don't match
 * #11 | const | no | mixed    | NS is identical but base identifier's case don't match
 * #12 | const | no | nonmixed | wrong type
 * #13 | const | no | mixed    | wrong type
 *
 * @TODO test "as foo"
 */
class baseIdentifierOfFQNInGroupUseTest extends TestCase
{
    public function test0(){
        $groupUse = parse("<?php use A\\B\\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "a\\b\\e");
        self::assertSame("E", $identifier);
    }

    public function test1(){
        $groupUse = parse("<?php use A\\B\\{C, function D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "a\\b\\e");
        self::assertSame("E", $identifier);
    }

    public function test2(){
        $groupUse = parse("<?php use function A\B\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test3(){
        $groupUse = parse("<?php use A\\B\\{C, D, function E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test4(){
        $groupUse = parse("<?php use function A\\B\\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "a\\b\\e");
        self::assertSame("E", $identifier);
    }

    public function test5(){
        $groupUse = parse("<?php use A\\B\\{C, D, function E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "a\\b\\e");
        self::assertSame("E", $identifier);
    }

    public function test6(){
        $groupUse = parse("<?php use const A\B\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test7(){
        $groupUse = parse("<?php use A\\B\\{C, D, const E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test8(){
        $groupUse = parse("<?php use const A\\B\\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "a\\b\\E");
        self::assertSame("E", $identifier);
    }

    public function test9(){
        $groupUse = parse("<?php use A\\B\\{C, D, const E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "a\\b\\E");
        self::assertSame("E", $identifier);
    }

    public function test10(){
        $groupUse = parse("<?php use const A\\B\\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\e");
        self::assertSame(NULL, $identifier);
    }

    public function test11(){
        $groupUse = parse("<?php use A\\B\\{C, D, const E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\e");
        self::assertSame(NULL, $identifier);
    }

    public function test12(){
        $groupUse = parse("<?php use function A\\B\\{C, D, E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test13(){
        $groupUse = parse("<?php use A\\B\\{C, D, function E};")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }
}
