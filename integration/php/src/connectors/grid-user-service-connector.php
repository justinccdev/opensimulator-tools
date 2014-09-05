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

function SetHome($serviceUri, $userId, $regionId, $posX, $posY, $posZ, $lookAtX, $lookAtY, $debug = FALSE)
{
    $params 
        = array(
            'UserID' => $userId,
            'RegionID' => $regionId,
            'Position' => "<$posX,$posY,$posZ>",
            'LookAt' => "<$lookAtX,$lookAtY,0>",
            'METHOD' => "sethome");   
            
    return PostToService($serviceUri, http_build_query($params), $debug);            
}

function SetLastLocation($serviceUri, $userId, $regionId, $posX, $posY, $posZ, $lookAtX, $lookAtY, $debug = FALSE)
{
    $params 
        = array(
            'UserID' => $userId,
            'RegionID' => $regionId,
            'Position' => "<$posX,$posY,$posZ>",
            'LookAt' => "<$lookAtX,$lookAtY,0>",
            'METHOD' => "setposition");   
            
    return PostToService($serviceUri, http_build_query($params), $debug);            
}

?>