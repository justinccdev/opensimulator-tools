<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/grid-user-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('userID');
$parser->addArgument('homeRegionID');
$parser->addArgument('homePosX');
$parser->addArgument('homePosY');
$parser->addArgument('homePosZ');
$parser->addArgument('homeLookAtX');
$parser->addArgument('homeLookAtY');
        
try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$userId = $params->args['userID'];
   
if (!IsUuid($userId))
{
    print "ERROR: [$userId] is not a well-formed user UUID.\n";
    exit(1);
}

$homeRegionId = $params->args['homeRegionID'];
   
if (!IsUuid($userId))
{
    print "ERROR: [homeRegionID] is not a well-formed user UUID.\n";
    exit(2);
}

$homePosX = $params->args['homePosX'];
$homePosY = $params->args['homePosY'];
$homePosZ = $params->args['homePosZ'];
$lookAtX = $params->args['homeLookAtX'];
$lookAtY = $params->args['homeLookAtY'];

SetHome($GRID_USER_SERVICE_URI, $userId, $homeRegionId, $homePosX, $homePosY, $homePosZ, $lookAtX, $lookAtY, TRUE);

?>