<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserToolsTests\Misc;

use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPParserTools\Misc\moveOrphanNodesToRootNS;
use function Netmosfera\PHPParserToolsTests\parse;
use function Netmosfera\PHPParserToolsTests\stringify;

class moveOrphanNodesToRootNSTest extends TestCase
{
    public function test_orphan_statements_are_moved_to_root_namespace(){
        $original = parse("<?php
            declare(strict_types = 1);
            echo 1; echo 2; echo 3; class Bar{}            
        ");
        $actualNodes = moveOrphanNodesToRootNS($original);
        $actual = stringify($actualNodes);

        $expectNodes = parse("<?php
            declare(strict_types = 1);
            namespace { echo 1; echo 2; echo 3; class Bar{} }
        ");
        $expect = stringify($expectNodes);

        self::assertSame($expect, $actual);
    }

    public function test_blank_file_is_kept_as_is(){
        $original = parse("");
        $actualNodes = moveOrphanNodesToRootNS($original);
        $actual = stringify($actualNodes);

        $expectNodes = parse("");
        $expect = stringify($expectNodes);

        self::assertSame($expect, $actual);
    }

    public function test_blank_php_file_is_kept_as_is(){
        $original = parse("<?php ");
        $actualNodes = moveOrphanNodesToRootNS($original);
        $actual = stringify($actualNodes);

        $expectNodes = parse("<?php ");
        $expect = stringify($expectNodes);

        self::assertSame($expect, $actual);
    }

    public function test_namespaces_no_brackets_are_kept_as_is(){
        $original = parse("<?php
            declare(strict_types = 1);
            namespace Foo; echo 1; echo 2; class A{}
            namespace Bar; echo 1; echo 2; class A{}
        ");
        $actualNodes = moveOrphanNodesToRootNS($original);
        $actual = stringify($actualNodes);

        $expectNodes = parse("<?php
            declare(strict_types = 1);
            namespace Foo; echo 1; echo 2; class A{}
            namespace Bar; echo 1; echo 2; class A{}
        ");
        $expect = stringify($expectNodes);

        self::assertSame($expect, $actual);
    }

    public function test_namespaces_brackets_are_kept_as_is(){
        $original = parse("<?php
            namespace Foo{ echo 1; echo 2; class A{} }
            namespace Bar{ echo 1; echo 2; class A{} }
            namespace    { echo 1; echo 2; class A{} }
        ");
        $actualNodes = moveOrphanNodesToRootNS($original);
        $actual = stringify($actualNodes);

        $expectNodes = parse("<?php
            namespace Foo{ echo 1; echo 2; class A{} }
            namespace Bar{ echo 1; echo 2; class A{} }
            namespace    { echo 1; echo 2; class A{} }
        ");
        $expect = stringify($expectNodes);

        self::assertSame($expect, $actual);
    }
}
