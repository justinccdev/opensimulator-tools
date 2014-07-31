<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('groupID');
$parser->addArgument('userID');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

$groupId = $params->args['groupID'];
$userId = $params->args['userID'];

// Lazily we'll just add people to the everyone role (UUID_Zero)
AddUserToGroup($GROUPS_SERVICE_URI, $groupId, $userId, UUID_ZERO, true);