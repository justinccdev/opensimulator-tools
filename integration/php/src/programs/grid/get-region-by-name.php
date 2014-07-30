<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/grid-service-connector.php";

$parser = new Console_CommandLine();

$parser->addArgument('name');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

GetRegionByName($GRID_SERVICE_URI, $params->args['name'], TRUE);

?>