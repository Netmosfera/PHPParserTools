<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserToolsTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPParserTools\getNameByImport;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class GetNameByImportTest extends TestCase
{
    private function parse($string){
        return (new ParserFactory())->create(ParserFactory::ONLY_PHP7)->parse($string);
    }

    function simple_data(){
        $concat = [" use X\\X\\X; ", " "];
        foreach($concat as $prepend){
            foreach($concat as $append){
                yield [$prepend, $append, Use_::TYPE_NORMAL,   " ",          " function "];
                yield [$prepend, $append, Use_::TYPE_FUNCTION, " function ", " const "];
                yield [$prepend, $append, Use_::TYPE_CONSTANT, " const    ", " "];

            }
        }
    }

    /** @dataProvider simple_data */
    function test_simple_one($p, $a, $type, $string, $differentString){
        $nodes = $this->parse("<?php $p use $string A\\B\\C; $a");
        $name = getNameByImport($type, "A\\B\\C", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["C"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C; $a");
        $name = getNameByImport($type, "A\\B\\C", $nodes);
        self::assertNull($name);
    }

    /** @dataProvider simple_data */
    function test_simple_at_the_start_of_multiple($p, $a, $type, $string, $differentString){
        $nodes = $this->parse("<?php $p use $string A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "A\\B\\C", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["C"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "A\\B\\C", $nodes);
        self::assertNull($name);
    }

    /** @dataProvider simple_data */
    function test_simple_in_the_middle_of_multiple($p, $a, $type, $string, $differentString){
        $nodes = $this->parse("<?php $p use $string A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "D\\E\\F", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["F"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "D\\E\\F", $nodes);
        self::assertNull($name);
    }

    /** @dataProvider simple_data */
    function test_simple_at_the_end_of_multiple($p, $a, $type, $string, $differentString){
        $nodes = $this->parse("<?php $p use $string A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "G\\H\\I", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["I"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C, D\\E\\F, G\\H\\I; $a");
        $name = getNameByImport($type, "G\\H\\I", $nodes);
        self::assertNull($name);
    }


    function group_data(){
        $concat = [" use X\\X\\X; ", " "];
        $prependInGroup = [
            " ",
            "X, ",
            "X\\X, ",
            "X, X, ",
            "X\\X, X\\X, ",
        ];

        $appendInGroup = [
            " ",
            ", X",
            ", X\\X",
            ", X, X",
            ", X\\X, X\\X",
        ];

        foreach($concat as $prepend){
            foreach($concat as $append){
                foreach($prependInGroup as $gp){
                    foreach($appendInGroup as $ga){
                        yield [$prepend, $append, $gp, $ga, Use_::TYPE_NORMAL, " ", " function "];
                        yield [$prepend, $append, $gp, $ga, Use_::TYPE_FUNCTION, " function ", " const "];
                        yield [$prepend, $append, $gp, $ga, Use_::TYPE_CONSTANT, " const ", " "];
                    }
                }
            }
        }
    }

    /** @dataProvider group_data */
    function test_group_single_identifier($p, $a, $gp, $ga, $type, $typeString, $differentString){
        $nodes = $this->parse("<?php $p use $typeString A\\B\\C\\{ $gp D $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["D"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C\\{ $gp D $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D", $nodes);
        self::assertNull($name);
    }

    /** @dataProvider group_data */
    function test_group_multiple_identifiers($p, $a, $gp, $ga, $type, $typeString, $differentString){
        $nodes = $this->parse("<?php $p use $typeString A\\B\\C\\{ $gp D\\E\\F $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D\\E\\F", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["F"], $name->parts);

        $nodes = $this->parse("<?php $p use $differentString A\\B\\C\\{ $gp D\\E\\F $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D\\E\\F", $nodes);
        self::assertNull($name);
    }

    /** @dataProvider group_data */
    function test_mixed_group_single_identifier($p, $a, $gp, $ga, $type, $typeString, $differentString){
        $nodes = $this->parse("<?php $p use A\\B\\C\\{ $gp $typeString D $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["D"], $name->parts);

        $nodes = $this->parse("<?php $p use A\\B\\C\\{ $gp $differentString D $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D", $nodes);
        self::assertNull($name);
    }
    /** @dataProvider group_data */
    function test_mixed_group_multiple_identifiers($p, $a, $gp, $ga, $type, $typeString, $differentString){
        $nodes = $this->parse("<?php $p use A\\B\\C\\{ $gp $typeString D\\E\\F $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D\\E\\F", $nodes);
        self::assertInstanceOf(Name::CLASS, $name);
        self::assertSame(["F"], $name->parts);

        $nodes = $this->parse("<?php $p use A\\B\\C\\{ $gp $differentString D\\E\\F $ga }; $a");
        $name = getNameByImport($type, "A\\B\\C\\D\\E\\F", $nodes);
        self::assertNull($name);
    }
}
