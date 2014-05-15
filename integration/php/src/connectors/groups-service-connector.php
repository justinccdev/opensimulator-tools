<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function FindGroups($serviceUri, $query, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'Query' => $query,
            'METHOD' => 'FINDGROUPS');

    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);
}

function GetGroupMembers($serviceUri, $groupUuid, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'GroupID' => $groupUuid,
            'METHOD' => 'GETGROUPMEMBERS');

    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);
}

function GetUserMemberships($serviceUri, $userUuid, $groupUuid, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'AgentID' => $userUuid,
            'METHOD' => 'GETMEMBERSHIP');
            
    if ($groupUuid == "all")
        $params['ALL'] = '';
    else if ($groupUuid == "active")
        $params['GroupID'] = UUID_ZERO;
    else
        $params['GroupID'] = $groupUuid;

    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);
}

?>