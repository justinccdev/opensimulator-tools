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

?>