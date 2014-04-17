<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetAgents($serviceUri, $userIds, $debug = FALSE)
{
    $params = "METHOD=getagents";
    
    foreach ($userIds as $userId)
    {
        $params .= "&uuids[]=$userId";
    }
               
    return PostToService($serviceUri, $params, $debug); 
}    

?>