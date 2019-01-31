<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\BaseIdentifiersExtractionFromNodes;

use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\normalBaseIdentifiersInFunctionLikeSignature as ff;
use function Netmosfera\PHPParserToolsTests\parse;

class normalBaseIdentifiersInFunctionLikeSignatureTest extends TestCase
{
    public function test_no_type_declaration(){
        $function = parse("<?php
            function bar(Bar \$a, Foo \$b, Baz \$c): Qux{}
        ")[0];
        $expect = ["Bar", "Foo", "Baz", "Qux"];
        $actual = ff($function);
        self::assertSame($expect, $actual);
    }
}
