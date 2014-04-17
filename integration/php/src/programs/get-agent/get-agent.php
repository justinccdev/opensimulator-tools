<?php

require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/presence-service-connector.php";

############
### MAIN ###
############    

$options = getopt("", array("user:", "help"));

// Also print help if user doesn't put any parameters on the command line.
if (array_key_exists("help", $options) || count($argv) == 1)
{
    print "Usage: " . basename($_SERVER["SCRIPT_NAME"]) . " --user <user-uuid> [--help]\n";
    print "  user-uuid is a UUID relating to an existing user.\n";
    exit(-1);
}

if (!array_key_exists("user", $options))
{
    print "ERROR: You must specify a user ID using the --user switch\n";
    exit(-2);
}

$USER_ID = $options['user'];

if (!IsUuid($USER_ID))
{
    print "ERROR: [$USER_ID] is not a well-formed user UUID.\n";
    exit(-2);
}   

$agents = array($USER_ID);
GetAgents($PRESENCE_SERVICE_URI, $agents, TRUE);