<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('groupName');
$parser->addArgument('founderID');
$parser->addArgument('charter');
$parser->addArgument('groupPictureID');
$parser->addArgument('openEnrollment');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

$groupName = $params->args['groupName'];
$founderId = $params->args['founderID'];
$charter = $params->args['charter'];
$groupPictureID = $params->args['groupPictureID'];
$openEnrollment = $params->args['openEnrollment'];

AddGroup($GROUPS_SERVICE_URI, $groupName, $founderId, $charter, $groupPictureID, TRUE, TRUE, TRUE, 0, TRUE, TRUE);