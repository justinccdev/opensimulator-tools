<?php

require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/presence-service-connector.php";

############
### MAIN ###
############   

$agents = array("e4f3924a-5a7c-4e1a-bee7-aa96580f2515", "e92bcf57-00e3-44b1-9e3b-586bfdcac4c3");
GetAgents($PRESENCE_SERVICE_URI, $agents, TRUE);