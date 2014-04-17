<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetAvatar($serviceUri, $userId, $debug = FALSE)
{
    $params 
        = array(
            'VERSIONMIN' => 0,
            'VERSIONMAX' => 0,
            'UserID' => $userId,
            'METHOD' => "getavatar");
            
    return PostToService($serviceUri, http_build_query($params), $debug);
}    

?>