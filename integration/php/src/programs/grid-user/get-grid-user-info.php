<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/grid-user-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('userUUID');
        
try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$userId = $params->args['userUUID'];
   
if (!IsUuid($userId))
{
    print "ERROR: [$userId] is not a well-formed user UUID.\n";
    continue;
}

GetGridUserInfo($GRID_USER_SERVICE_URI, $userId, TRUE);