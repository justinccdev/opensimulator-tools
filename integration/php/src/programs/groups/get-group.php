<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('group');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

$group = $params->args['group'];
$groupId = NULL;
$groupName = NULL;

# For the purposes of this example program, we will assume that if the argument is a UUID then we always intend to 
# use it as a UUID in the query.
if (IsUuid($group))
    GetGroupById($GROUPS_SERVICE_URI, $group, TRUE);
else
    GetGroupByName($GROUPS_SERVICE_URI, $group, TRUE);