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
$parser->addArgument('regionID');
$parser->addArgument('posX');
$parser->addArgument('posY');
$parser->addArgument('posZ');
$parser->addArgument('lookAtX');
$parser->addArgument('lookAtY');
        
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

$regionId = $params->args['regionID'];
   
if (!IsUuid($userId))
{
    print "ERROR: [homeRegionID] is not a well-formed user UUID.\n";
    exit(2);
}

$posX = $params->args['posX'];
$posY = $params->args['posY'];
$posZ = $params->args['posZ'];
$lookAtX = $params->args['lookAtX'];
$lookAtY = $params->args['lookAtY'];

SetHome($GRID_USER_SERVICE_URI, $userId, $regionId, $posX, $posY, $posZ, $lookAtX, $lookAtY, TRUE);

?>