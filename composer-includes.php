<?php

require(__DIR__ . "/src/isValidGlobalSymbolType.inc.php");
require(__DIR__ . "/src/Attributes/containsAttribute.inc.php");
require(__DIR__ . "/src/Attributes/newAttribute.inc.php");
require(__DIR__ . "/src/Attributes/removeAttribute.inc.php");
require(__DIR__ . "/src/Misc/moveOrphanNodesToRootNS.inc.php");
require(__DIR__ . "/src/Names/baseIdentifierOfFQN.inc.php");
require(__DIR__ . "/src/Names/baseIdentifierOfFQNOrNumberIt.inc.php");
require(__DIR__ . "/src/Names/isBaseIdentifierActuallyInUse.inc.php");
require(__DIR__ . "/src/Names/isImplicitlyRelativeName.inc.php");
require(__DIR__ . "/src/Names/normalizeFQNForComparison.inc.php");
require(__DIR__ . "/src/Names/BaseIdentifiersExtractionFromNodes/constantBaseIdentifiersInNode.inc.php");
require(__DIR__ . "/src/Names/BaseIdentifiersExtractionFromNodes/functionBaseIdentifiersInNode.inc.php");
require(__DIR__ . "/src/Names/BaseIdentifiersExtractionFromNodes/normalBaseIdentifierInTypeDeclaration.inc.php");
require(__DIR__ . "/src/Names/BaseIdentifiersExtractionFromNodes/normalBaseIdentifiersInFunctionLikeSignature.inc.php");
require(__DIR__ . "/src/Names/BaseIdentifiersExtractionFromNodes/normalBaseIdentifiersInNode.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/baseIdentifierOfFQNInGroupUse.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/baseIdentifierOfFQNInUse.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/baseIdentifierOfFQNInUses.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/FQNOfBaseIdentifierInGroupUse.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/FQNOfBaseIdentifierInUse.inc.php");
require(__DIR__ . "/src/Names/FQNToAndFromBaseIdentifierConversionInUses/FQNOfBaseIdentifierInUses.inc.php");
require(__DIR__ . "/src/Nodes/containsNode.inc.php");
require(__DIR__ . "/src/Nodes/setParentNodes.inc.php");
require(__DIR__ . "/src/Nodes/visitNodes.inc.php");
require(__DIR__ . "/src/Variables/findUnusedVariable.inc.php");
