<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('groupUuid');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$groupUuid = $params->args['groupUuid'];

if (!IsUuid($groupUuid))
{
    print "ERROR: [$groupUuid] is not a well-formed UUID.\n";
    exit(-1);
}

GetGroupMembers($GROUPS_SERVICE_URI, $groupUuid, TRUE);