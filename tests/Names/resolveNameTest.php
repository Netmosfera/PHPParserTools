<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Names;

use Error;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Names\resolveName;
use function Netmosfera\PHPParserToolsTests\parse;

class resolveNameTest extends TestCase
{
    public function test_fully_qualified(){
        $namespace = parse("<?php namespace X; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new FullyQualified("Foo\\Bar"));
        self::assertSame("Foo\\Bar", $FQN);
    }

    public function test_relative(){
        $namespace = parse("<?php namespace X; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Relative("Foo\\Bar"));
        self::assertSame("X\\Foo\\Bar", $FQN);
    }

    public function test_identifier_is_converted_to_name(){
        $namespace = parse("<?php namespace X\\Y\\Z; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_FUNCTION, new Identifier("poo"));
        self::assertSame("X\\Y\\Z\\poo", $FQN);
    }

    public function test_relative_to_namespace(){
        $namespace = parse("<?php
            namespace A\\B\\C;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_FUNCTION, new Name("D\\E\\callMe"));

        self::assertSame("A\\B\\C\\D\\E\\callMe", $FQN);
    }

    public function test_relative_to_use(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_CONSTANT, new Name("D\\E\\CONSTANT"));

        self::assertSame("X\\Y\\Z\\D\\E\\CONSTANT", $FQN);
    }

    public function test_relative_to_namespace_because_different_type(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use function X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name("D\\E\\F"));

        self::assertSame("A\\B\\C\\D\\E\\F", $FQN);
    }

    public function test_type_1(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use function X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name("D"));

        self::assertSame("A\\B\\C\\D", $FQN);
    }

    public function test_type_2(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name("D"));

        self::assertSame("X\\Y\\Z\\D", $FQN);
    }

    public function test_type_3(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_FUNCTION, new Name("D"));

        self::assertSame("A\\B\\C\\D", $FQN);
    }

    public function test_type_4(){
        $namespace = parse("<?php
            namespace A\\B\\C;
            use function X\\Y\\Z\\D;
        ")[0];

        $FQN = resolveName($namespace, Use_::TYPE_FUNCTION, new Name("D"));

        self::assertSame("X\\Y\\Z\\D", $FQN);
    }

    public function builtin_classes(){
        $builtinTypes = "bool,int,float,string,void,array,callable,iterable,object";
        $builtinTypes = explode(",", $builtinTypes);
        foreach($builtinTypes as $builtinType){
            yield [$builtinType];
            yield [strtoupper($builtinType)];
            yield [ucfirst($builtinType)];
        }
    }

    /** @dataProvider builtin_classes */
    public function test_builtin_class(String $builtinType){
        $namespace = parse("<?php namespace X; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name($builtinType));
        self::assertSame($builtinType, $FQN);
    }

    public function test_self_available(){
        $namespace = parse("<?php namespace X; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name("self"), "a\\b\\c");
        self::assertSame("a\\b\\c", $FQN);
    }

    public function test_self_unavailable(){
        $this->expectException(Error::CLASS);
        $namespace = parse("<?php namespace X; ")[0];
        resolveName($namespace, Use_::TYPE_NORMAL, new Name("self"));
    }

    public function test_static_available(){
        $namespace = parse("<?php namespace X; ")[0];
        $FQN = resolveName($namespace, Use_::TYPE_NORMAL, new Name("static"), NULL, "a\\b\\c");
        self::assertSame("a\\b\\c", $FQN);
    }

    public function test_static_unavailable(){
        $this->expectException(Error::CLASS);
        $namespace = parse("<?php namespace X; ")[0];
        resolveName($namespace, Use_::TYPE_NORMAL, new Name("static"));
    }

    public function test_func_and_const_invalid_identifiers(){
        $this->expectException(Error::CLASS);
        $namespace = parse("<?php namespace X; ")[0];
        resolveName($namespace, Use_::TYPE_FUNCTION, new Name("static"));
    }
}
