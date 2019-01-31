<?php declare(strict_types = 1);

namespace Netmosfera\PHPParserTools\Names;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;
use function Netmosfera\PHPParserTools\isValidGlobalSymbolType;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\constantBaseIdentifiersInNode;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\functionBaseIdentifiersInNode;
use function Netmosfera\PHPParserTools\Names\BaseIdentifiersExtractionFromNodes\normalBaseIdentifiersInNode;
use function Netmosfera\PHPParserTools\Nodes\visitNodes;

function isBaseIdentifierActuallyInUse(
    Namespace_ $namespace,
    Int $searchType,
    String $searchBaseIdentifier
): Bool{

    // Note that this does not include usages in use; statements

    assert(isValidGlobalSymbolType($searchType));

    $searchBaseIdentifier = normalizeFQNForComparison($searchBaseIdentifier, $searchType);

    $inUse = FALSE;

    visitNodes([$namespace], function(Node $node) use(
        $searchBaseIdentifier,
        $searchType,
        &$inUse
    ){
        if($searchType === Use_::TYPE_NORMAL){
            $baseIdentifiers = normalBaseIdentifiersInNode($node);
        }elseif($searchType === Use_::TYPE_FUNCTION){
            $baseIdentifiers = functionBaseIdentifiersInNode($node);
        }else{
            $baseIdentifiers = constantBaseIdentifiersInNode($node);
        }

        foreach($baseIdentifiers as $baseIdentifier){
            $baseIdentifier = normalizeFQNForComparison($baseIdentifier, $searchType);
            if($searchBaseIdentifier === $baseIdentifier){
                $inUse = TRUE;
                return NodeTraverser::STOP_TRAVERSAL;
            }
        }
    });

    return $inUse;
}
