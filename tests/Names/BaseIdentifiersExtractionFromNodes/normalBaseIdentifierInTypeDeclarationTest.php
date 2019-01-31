<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names\BaseIdentifiersExtractionFromNodes;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\NullableType;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\normalBaseIdentifierInTypeDeclaration as ff;

class normalBaseIdentifierInTypeDeclarationTest extends TestCase
{
    public function test_no_type_declaration(){
        $typeDeclaration = NULL;
        $expect = NULL;
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function test_nullable_type_is_unwrapped(){
        $typeDeclaration = new NullableType(new Identifier("Float"));
        $expect = "Float";
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function data_special_class_name(){
        foreach(["self", "parent", "static"] as $scn){
            yield [new Identifier($scn)];
            yield [new Name([$scn])];
        }
    }

    /** @dataProvider data_special_class_name */
    public function test_special_class_name($specialClassName){
        $typeDeclaration = $specialClassName;
        $expect = NULL;
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function test_relative(){
        $typeDeclaration = new Relative(["Foo"]);
        $expect = NULL;
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function test_fully_qualified(){
        $typeDeclaration = new FullyQualified(["Foo"]);
        $expect = NULL;
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function test_base_identifier_identifier(){
        $typeDeclaration = new Identifier("Foo");
        $expect = "Foo";
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }

    public function test_base_identifier_name(){
        $typeDeclaration = new Name(["Foo"]);
        $expect = "Foo";
        $actual = ff($typeDeclaration);
        self::assertSame($expect, $actual);
    }
}
