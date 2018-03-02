<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPParserTools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Name\FullyQualified;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @TODOC
 *
 * @param           Int                                     $type
 * One of the Use_::TYPE_* constants.
 *
 * @param           String                                  $fqn
 * The FQN whose alias is to be searched.
 *
 * @param           Node[]                                  $intoNodes
 * The Namespace_ object, or the root nodes if there is no namespace declaration.
 *
 * @return          Name
 * Returns the Name, or NULL if no import could be found.
 */
function getNameByImport(Int $type, String $fqn, Array $intoNodes): ?Name{
    assert($type === Use_::TYPE_NORMAL || $type === Use_::TYPE_FUNCTION || $type === Use_::TYPE_CONSTANT);

    $getGroupUseAlias = static function(GroupUse $groupUse, Int $ifType, String $fqn): ?Name{
        foreach($groupUse->uses as $useUse){
            if($useUse->type === $ifType){
                $fqnName = new FullyQualified(array_merge($groupUse->prefix->parts, $useUse->name->parts));
                if(strtolower($fqnName->toString()) === strtolower($fqn)){
                    return new Name([(String)($useUse->alias ?? $useUse->name->getLast())]);
                }
            }
        }
        return NULL;
    };

    $foundName = NULL;

    visitNodes($intoNodes, function(Node $node) use($type, $fqn, $getGroupUseAlias, &$foundName){
        if($node instanceof Use_){
            if($node->type !== $type){ return; }
            foreach($node->uses as $useUse){
                if(strtolower($useUse->name->toString()) === strtolower($fqn)){
                    $foundName = new Name((String)($useUse->alias ?? $useUse->name->getLast()));
                    return NodeTraverser::STOP_TRAVERSAL;
                }
            }
        }elseif($node instanceof GroupUse){
            /** @var GroupUse $node */
            if($node->type === Use_::TYPE_UNKNOWN){
                $foundName = $getGroupUseAlias($node, $type, $fqn);
            }elseif($node->type === $type){
                $foundName = $getGroupUseAlias($node, Use_::TYPE_UNKNOWN, $fqn);
            }
            if($foundName !== NULL){
                return NodeTraverser::STOP_TRAVERSAL;
            }
        }
    });

    return $foundName;
}
