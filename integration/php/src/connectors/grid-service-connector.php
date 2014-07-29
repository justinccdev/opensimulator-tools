<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetRegionRange($serviceUri, $minX, $minY, $maxX, $maxY, $debug = FALSE)
{
    $params 
        = array(
            'SCOPEID' => UUID_ZERO,
            'XMIN' => $minX,
            'YMIN' => $minY,
            'XMAX' => $maxX,
            'YMAX' => $maxY,
            'METHOD' => "get_region_range");
            
    return PostToService($serviceUri, http_build_query($params), $debug);    
}

?>