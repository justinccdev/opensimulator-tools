<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetGridUserInfo($serviceUri, $userId, $debug = FALSE)
{
    $params 
        = array(
            'UserID' => $userId,
            'METHOD' => "getgriduserinfo");   
            
    return PostToService($serviceUri, http_build_query($params), $debug);            
}

?>