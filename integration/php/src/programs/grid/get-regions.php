<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/grid-service-connector.php";

GetRegionRange($GRID_SERVICE_URI, 0, 0, 2147483647, 2147483647, TRUE);

?>