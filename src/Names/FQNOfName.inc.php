<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use Error;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use function strtolower;

function FQNOfName(
    Namespace_ $namespace,
    Int $type, $name,
    ?String $selfValue = NULL,
    ?String $staticValue = NULL
): String{
    assert($name instanceof Identifier || $name instanceof Name);

    if($name instanceof FullyQualified){
        return $name->toString();
    }

    if($name instanceof Relative){
        $parts = $namespace->name->parts;
        foreach($name->parts as $part){
            $parts[] = $part;
        }
        return implode("\\", $parts);
    }

    if($name instanceof Identifier){
        $name = new Name($name->name);
    }

    assert($name instanceof Name);

    $stringifiedName = $name->toString();
    $lowercasedName = strtolower($stringifiedName);

    if($type === Use_::TYPE_NORMAL){

        $automaticallyImportedIdentifiers = [
            "bool" => TRUE,
            "int" => TRUE,
            "float" => TRUE,
            "string" => TRUE,
            "void" => TRUE,
            "array" => TRUE,
            "callable" => TRUE,
            "iterable" => TRUE,
            "object" => TRUE
        ];

        if(isset($automaticallyImportedIdentifiers[$lowercasedName])){
            return $stringifiedName;
        }elseif($lowercasedName === "self"){
            if($selfValue === NULL){
                throw new Error("Encountered 'self' not in a class context");
            }
            return $selfValue;
        }elseif($lowercasedName === "static"){
            if($staticValue === NULL){
                throw new Error("Encountered 'static' not in a class context");
            }
            return $staticValue;
        }

    }elseif($type === Use_::TYPE_FUNCTION || $type === Use_::TYPE_CONSTANT){
        if(
            $lowercasedName === "array" ||
            $lowercasedName === "callable" ||
            $lowercasedName === "static"
        ){
            throw new Error("Invalid function or constant name $lowercasedName");
        }
    }

    $baseIdentifier = $name->parts[0];
    if(count($name->parts) === 1){
        $FQN = FQNOfBaseIdentifier($type, $baseIdentifier, $namespace);
    }else{
        $baseFQN = FQNOfBaseIdentifier(Use_::TYPE_NORMAL, $baseIdentifier, $namespace);
        $FQN = $baseFQN . "\\" . implode("\\", array_slice($name->parts, 1));
    }

    return $FQN;
}
