<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

# Create required asset xml for posting using id and raw asset data
function CreateAssetXml($data, $id, $name, $description, $type)
{
    $assetBaseE = new SimpleXmlElement("<AssetBase/>");
    $assetBaseE->addChild("Data", base64_encode($data));
    $fullIdE = $assetBaseE->addChild("FullID");
    $fullIdE->addChild("Guid", $id);
    $assetBaseE->addChild("ID", $id);
    $assetBaseE->addChild("Name", $name);
    $assetBaseE->addChild("Description", $description);
    $assetBaseE->addChild("Type", $type);   
    
    return $assetBaseE->asXML();
}

function AddAsset($serviceUri, $assetXml, $debug = FALSE)
{
    return PostToService($serviceUri, $assetXml, $debug);
}

function GetAsset($serviceUri, $assetId, $debug = FALSE)
{
    return GetFromService($serviceUri . "/" . $assetId, $debug);
}

function DeleteAsset($serviceUri, $assetId, $debug = FALSE)
{
    return DeleteFromService($serviceUri . "/" . $assetId, $debug);
}

?>