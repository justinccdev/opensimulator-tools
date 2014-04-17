<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/presence-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('userUUIDs', array('multiple' => true));
        
try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$userIds = $params->args['userUUIDs'];

foreach ($userIds as $userId)
{    
    if (!IsUuid($userId))
    {
        print "ERROR: [$userId] is not a well-formed user UUID.\n";
        continue;
    }

    GetAgents($PRESENCE_SERVICE_URI, array($userId), TRUE);
}