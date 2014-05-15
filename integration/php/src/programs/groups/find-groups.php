<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('query');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

FindGroups($GROUPS_SERVICE_URI, $params->args['query'], TRUE);