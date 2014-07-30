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

    return PostToService($serviceUri, http_build_query($params), $debug);
}

function GetGroup($serviceUri, $groupUuid = NULL, $groupName = NULL, $debug = FALSE)
{
    if ($groupUuid != NULL && $groupName != NULL)
        throw new Exception("Cannot specify both group ID and name.");
    else if ($groupUuid == NULL && $groupName == NULL)
        throw new Exception("Either group ID or group name must be specified.");            
    
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,            
            'METHOD' => 'GETGROUP');
            
    if ($groupUuid != NULL)
        $params['GroupID'] =  $groupUuid;
    else
        $params['Name'] = $groupName;

    return PostToService($serviceUri, http_build_query($params), $debug);
}

function GetGroupMembers($serviceUri, $groupUuid, $debug = FALSE)
{
    $params
        = array(
            'RequestingAgentID' => UUID_ZERO,
            'GroupID' => $groupUuid,
            'METHOD' => 'GETGROUPMEMBERS');

    return PostToService($serviceUri, http_build_query($params), $debug);
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

    return PostToService($serviceUri, http_build_query($params), $debug);
}

?>