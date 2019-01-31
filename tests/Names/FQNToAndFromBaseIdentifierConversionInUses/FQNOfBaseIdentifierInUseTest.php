<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\FQNOfBaseIdentifierInUse as ff;
use function Netmosfera\PHPParserToolsTests\parse;

/**
 * #0  | class |                | OK (+ tests that works with different case)
 * #1  | class | different type | NO
 * #2  | fn    |                | OK (+ tests that works with different case)
 * #3  | fn    | different type | NO
 * #4  | const |                | OK
 * #5  | const | different case | NO
 * #6  | const | different type | NO
 */
class FQNOfBaseIdentifierInUseTest extends TestCase
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
        $use = parse("<?php use A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_NORMAL, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test1(String $alias, String $identifier){
        $use = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_NORMAL, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test2(String $alias, String $identifier){
        $use = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test3(String $alias, String $identifier){
        $use = parse("<?php use A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_FUNCTION, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test4(String $alias, String $identifier){
        $use = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame("A\\B\\E", $FQN);
    }

    /** @dataProvider dataCaseInsensitive */
    public function test5(String $alias, String $identifier){
        $use = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }

    /** @dataProvider dataCaseSensitive */
    public function test6(String $alias, String $identifier){
        $use = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $FQN = ff($use, Use_::TYPE_CONSTANT, $identifier);
        self::assertSame(NULL, $FQN);
    }
}
