<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserToolsTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPParserTools\isIdentifierInUse;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Use_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class IsIdentifierInUseTest extends TestCase
{
    private function parse($string){
        return (new ParserFactory())->create(ParserFactory::ONLY_PHP7)->parse($string);
    }

    function data(){
        $concat = [" use X\\X\\X; ", " "];
        $nss = ["namespace Bar;", ""];
        foreach($nss as $ns){
            foreach($concat as $prepend){
                foreach($concat as $append){
                    yield [$ns, $prepend, $append, Use_::TYPE_NORMAL, " ", " function "];
                    yield [$ns, $prepend, $append, Use_::TYPE_FUNCTION, " function ", " const "];
                    yield [$ns, $prepend, $append, Use_::TYPE_CONSTANT, " const ", " "];
                }
            }
        }
    }

    /** @dataProvider data */
    function test_simple_one($ns, $prepend, $append, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string A\\B\\C; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString A\\B\\C; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
    }

    /** @dataProvider data */
    function test_simple_at_the_start_of_multiple($ns, $prepend, $append, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string A\\B\\C, X\\X\\X, X\\X\\X; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString A\\B\\C, X\\X\\X, X\\X\\X; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
    }

    /** @dataProvider data */
    function test_simple_in_the_middle_of_multiple($ns, $prepend, $append, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string X\\X\\X, A\\B\\C, X\\X\\X; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString X\\X\\X, A\\B\\C, X\\X\\X; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
    }

    /** @dataProvider data */
    function test_simple_at_the_end_of_multiple($ns, $prepend, $append, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string X\\X\\X, X\\X\\X, A\\B\\C; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString X\\X\\X, X\\X\\X, A\\B\\C; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
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

        foreach(["namespace Bar;", ""] as $ns){
            foreach($concat as $prepend){
                foreach($concat as $append){
                    foreach($prependInGroup as $gp){
                        foreach($appendInGroup as $ga){
                            yield [$ns, $prepend, $append, $gp, $ga, Use_::TYPE_NORMAL, " ", " function "];
                            yield [$ns, $prepend, $append, $gp, $ga, Use_::TYPE_FUNCTION, " function ", " const "];
                            yield [$ns, $prepend, $append, $gp, $ga, Use_::TYPE_CONSTANT, " const ", " "];
                        }
                    }
                }
            }
        }
    }

    /** @dataProvider group_data */
    function test_group_single_identifier($ns, $prepend, $append, $gp, $ga, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string A\\B\\{ $gp C $ga }; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString A\\B\\{ $gp C $ga }; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
    }

    /** @dataProvider group_data */
    function test_group_multiple_identifiers($ns, $prepend, $append, $gp, $ga, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use $string A\\B\\{ $gp C\\D\\E $ga }; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("E"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use $notString A\\B\\{ $gp C\\D\\E $ga }; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("E"), $nodes));
    }

    /** @dataProvider group_data */
    function test_mixed_group_single_identifier($ns, $prepend, $append, $gp, $ga, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use A\\B\\{ $gp $string C $ga }; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("C"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use A\\B\\{ $gp $notString C $ga }; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("C"), $nodes));
    }

    /** @dataProvider group_data */
    function test_mixed_group_multiple_identifiers($ns, $prepend, $append, $gp, $ga, $type, $string, $notString){
        $nodes = $this->parse("<?php $ns $prepend use A\\B\\{ $gp $string C\\D\\E $ga }; $append");
        self::assertTrue(isIdentifierInUse($type, new Identifier("E"), $nodes));

        $nodes = $this->parse("<?php $ns $prepend use A\\B\\{ $gp $notString C\\D\\E $ga }; $append");
        self::assertFalse(isIdentifierInUse($type, new Identifier("E"), $nodes));
    }
}
