<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\FQNOfBaseIdentifierInGroupUse as ff;
use function Netmosfera\PHPParserToolsTests\parse;

/**
 * #0  | class | mixed    |                | OK (+ tests that works with different case)
 * #1  | class | nonmixed |                | OK (+ tests that works with different case)
 * #2  | class | mixed    | different type | NO
 * #3  | class | nonmixed | different type | NO
 *
 * #4  | fn    | mixed    |                | OK (+ tests that works with different case)
 * #5  | fn    | nonmixed |                | OK (+ tests that works with different case)
 * #6  | fn    | mixed    | different type | NO
 * #7  | fn    | nonmixed | different type | NO
 *
 * #8  | const | mixed    |                | OK
 * #9  | const | nonmixed |                | OK
 * #10 | const | mixed    | different case | NO
 * #11 | const | nonmixed | different case | NO
 * #12 | const | mixed    | different type | NO
 * #13 | const | nonmixed | different type | NO
 */
class FQNOfBaseIdentifierInGroupUseTest extends TestCase
{
    public function dataCaseInsensitive(){
        yield [" as Y", "y"];
        yield ["", "e"];
    }

    public function dataCaseSensitive(){
        yield [" as Y", "Y"];
        yield ["", "E"];
    }

    /** @dataProvider dataCaseInsensitive */
    public function test0(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, function D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_NORMAL, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test1(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_NORMAL, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test2(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, function E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_NORMAL, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test3(String $alias, String $identifier){
        $groupUse = parse("<?php use function A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_NORMAL, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test4(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, function E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test5(String $alias, String $identifier){
        $groupUse = parse("<?php use function A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test6(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, const E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test7(String $alias, String $identifier){
        $groupUse = parse("<?php use const A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test8(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, const E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test9(String $alias, String $identifier){
        $groupUse = parse("<?php use const A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test10(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, const E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test11(String $alias, String $identifier){
        $groupUse = parse("<?php use const A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test12(String $alias, String $identifier){
        $groupUse = parse("<?php use A\\B\\{C, D, function E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test13(String $alias, String $identifier){
        $groupUse = parse("<?php use function A\\B\\{C, D, E $alias};")[0];
        $FQN = ff($groupUse, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }
}
