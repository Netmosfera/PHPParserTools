<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function isIdentifierInUse(Int $type, Identifier $identifier, Array $intoNodes): Bool{
    assert($type === Use_::TYPE_NORMAL || $type === Use_::TYPE_FUNCTION || $type === Use_::TYPE_CONSTANT);
    $identifier = strtolower((String)$identifier);
    $inUse = FALSE;
    visitNodes($intoNodes, function(Node $node) use($type, $identifier, &$inUse){
        if($node instanceof Use_){
            if($node->type !== $type){
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }else{
                foreach($node->uses as $useUse){
                    $existingIdentifier = (String)($useUse->alias ?? $useUse->name->getLast());
                    if(strtolower($existingIdentifier) === $identifier){
                        $inUse = TRUE;
                        return NodeTraverser::STOP_TRAVERSAL;
                    }
                }
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        }elseif($node instanceof GroupUse){
            if($node->type === Use_::TYPE_UNKNOWN){
                foreach($node->uses as $useUse){
                    if($useUse->type === $type){
                        $existingIdentifier = (String)($useUse->alias ?? $useUse->name->getLast());
                        if(strtolower($existingIdentifier) === $identifier){
                            $inUse = TRUE;
                            return NodeTraverser::STOP_TRAVERSAL;
                        }
                    }
                }
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }elseif($node->type === $type){
                foreach($node->uses as $useUse){
                    $existingIdentifier = (String)($useUse->alias ?? $useUse->name->getLast());
                    if(strtolower($existingIdentifier) === $identifier){
                        $inUse = TRUE;
                        return NodeTraverser::STOP_TRAVERSAL;
                    }
                }
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }else{
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }
        }elseif($node instanceof Namespace_ === FALSE){
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    });
    return $inUse;
}
