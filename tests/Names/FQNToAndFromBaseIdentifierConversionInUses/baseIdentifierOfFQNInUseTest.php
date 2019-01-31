<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\FQNToAndFromBaseIdentifierConversionInUses;

use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\FQNToAndFromBaseIdentifierConversionInUses\baseIdentifierOfFQNInUse as ff;
use function Netmosfera\PHPParserToolsTests\parse;

/**
 * #0  | class | ok
 * #1  | class | no | wrong type
 * #2  | class | no | not found
 *
 * #3  | fn    | ok
 * #4  | fn    | no | wrong type
 * #5  | fn    | no | not found
 *
 * #6  | const | ok | namespace with different case
 * #7  | const | ok | namespace with exact same case
 * #8  | const | no | same constant name but wrong case
 * #9  | const | no | different constant name
 * #10 | const | no | wrong type
 */
class baseIdentifierOfFQNInUseTest extends TestCase
{
    public function data(){
        yield [" as Y", "Y"];
        yield ["", "E"];
    }

    /** @dataProvider data */
    public function test0(String $alias, String $expectIdentifier){
        $groupUse = parse("<?php use A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "a\\b\\e");
        self::assertSame($expectIdentifier, $identifier);
    }

    public function test1(){
        $groupUse = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E;")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test2(){
        $groupUse = parse("<?php use A\\B\\C, A\\B\\D, A\\B\\F;")[0];
        $identifier = ff($groupUse, Use_::TYPE_NORMAL, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    /** @dataProvider data */
    public function test3(String $alias, String $expectIdentifier){
        $groupUse = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "a\\b\\e");
        self::assertSame($expectIdentifier, $identifier);
    }

    public function test4(){
        $groupUse = parse("<?php use A\\B\\C, A\\B\\D, A\\B\\E;")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test5(){
        $groupUse = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\F;")[0];
        $identifier = ff($groupUse, Use_::TYPE_FUNCTION, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    /** @dataProvider data */
    public function test6(String $alias, String $expectIdentifier){
        $groupUse = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "a\\b\\E");
        self::assertSame($expectIdentifier, $identifier);
    }

    /** @dataProvider data */
    public function test7(String $alias, String $expectIdentifier){
        $groupUse = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\E $alias;")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\E");
        self::assertSame($expectIdentifier, $identifier);
    }

    public function test8(){
        $groupUse = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\E;")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\e");
        self::assertSame(NULL, $identifier);
    }

    public function test9(){
        $groupUse = parse("<?php use const A\\B\\C, A\\B\\D, A\\B\\F;")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }

    public function test10(){
        $groupUse = parse("<?php use function A\\B\\C, A\\B\\D, A\\B\\E;")[0];
        $identifier = ff($groupUse, Use_::TYPE_CONSTANT, "A\\B\\E");
        self::assertSame(NULL, $identifier);
    }
}
