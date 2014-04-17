<?php

require_once "./config.php";
require_once "./utils.php";

function GetAvatar($serviceUri, $principalId)
{
    $getParams 
        = array(
            'VERSIONMIN' => 0,
            'VERSIONMAX' => 0,
            'UserID' => $principalId,
            'METHOD' => "getavatar");    
    
    return PostToService($serviceUri, http_build_query($getParams), TRUE);
}

function SetAvatar($serviceUri, $userId, $setParams)
{
    $setParams["VERSIONMAX"] = 0;
    $setParams["VERSIONMIN"] = 0;   
    $setParams["UserID"] = $userId;
    $setParams["METHOD"] = "setavatar";
    
    return PostToService($serviceUri, http_build_query($setParams), TRUE);    
}

//function RemoveItem($serviceUri, 

$USER_ID = "efc1b932-20e3-4298-8824-0f891fe3dc59";

$serverResponseE = GetAvatar($AVATAR_SERVICE_URI, $USER_ID);
$avatarE = $serverResponseE -> result;
$avatarTuplesEs = $avatarE->children();

$responseParams = array();

# Filter out wearables
foreach ($avatarTuplesEs as $avatarTupleE)
{
    $name = $avatarTupleE->getName();
    
    if (!preg_match("/^Wearable/", $name))
    {
        $responseParams[$name] = (string)$avatarTupleE;
        //unset ($avatarTuplesEs->{"${name}"});
    }    
}

// eyes
$responseParams["Wearable 0:0"] = "99999999-9999-9999-9999-999999999000:4bb6fa4d-1cd2-498a-a84c-95c1a0e74000";

// skin
$responseParams["Wearable 1:0"] = "99999999-9999-9999-9999-999999999001:f90883b6-4dcb-eb6c-312b-bcf9bfbde000";

// shape
$responseParams["Wearable 2:0"] = "99999999-9999-9999-9999-999999999002:cb88c0b3-6874-a9d2-b046-209982656000";

// hair
$responseParams["Wearable 3:0"] = "99999999-9999-9999-9999-999999999003:d1fe60ba-589a-e2fb-59f1-b0c068726000";

// shirt
$responseParams["Wearable 4:0"] = "99999999-9999-9999-9999-999999999004:32012209-b656-6a32-2c87-97010974b000";

// pants
$responseParams["Wearable 5:0"] = "99999999-9999-9999-9999-999999999005:0d72de3d-afe3-c783-802e-7f322d564000"; 

print "Response data:\n";
print_r($responseParams);
print "\n";

//echo GetPrettyXML($serverResponseE->asXML());

SetAvatar($AVATAR_SERVICE_URI, $USER_ID, $responseParams);

?>