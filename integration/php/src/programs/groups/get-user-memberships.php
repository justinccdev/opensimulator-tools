<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('userUuid');
$parser->addArgument(
    'groupUuid', 
    array('description' => "The group UUID, or 'active' to turn information about the user's active group membership or 'all' to return information about all group memberships"));

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$userUuid = $params->args['userUuid'];
$groupUuid = $params->args['groupUuid'];

if ($groupUuid != "all" && $groupUuid != "active" && !IsUuid($groupUuid))
{
    print "ERROR: [$groupUuid] is not a well-formed UUID or 'all' or 'active'.\n";
    exit(-1);
}

GetUserMemberships($GROUPS_SERVICE_URI, $userUuid, $groupUuid, TRUE);