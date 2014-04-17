<?php

require_once "add-cabp-config.php";
require_once "$IP/connectors/asset-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

############
### MAIN ###
############  

$assetInfos = array(
    "student male kit 1 skin" => array(
        "filename" => "f90883b6-4dcb-eb6c-312b-bcf9bfbde1ea_bodypart.txt",
        "id" => "f90883b6-4dcb-eb6c-312b-bcf9bfbde000",
        "description" => "smk1 skin desc",
        "type" => OpenMetaverse\AssetType::Bodypart),
        
    "student male kit 1 pants" => array(
        "filename" => "0d72de3d-afe3-c783-802e-7f322d56498a_clothing.txt",
        "id" => "0d72de3d-afe3-c783-802e-7f322d564000",
        "description" => "smk1 pants desc",
        "type" => OpenMetaverse\AssetType::Clothing),
        
    "student male kit 1 top" => array(
        "filename" => "32012209-b656-6a32-2c87-97010974b285_clothing.txt",
        "id" => "32012209-b656-6a32-2c87-97010974b000",
        "description" => "smk1 top desc",
        "type" => OpenMetaverse\AssetType::Clothing),
        
    "generic eyes" => array(
        "filename" => "4bb6fa4d-1cd2-498a-a84c-95c1a0e745a7_bodypart.txt",
        "id" => "4bb6fa4d-1cd2-498a-a84c-95c1a0e74000",
        "description" => "generic eyes desc",
        "type" => OpenMetaverse\AssetType::Bodypart),
        
    "master kids shape" => array(
        "filename" => "cb88c0b3-6874-a9d2-b046-209982656cab_bodypart.txt",
        "id" => "cb88c0b3-6874-a9d2-b046-209982656000",
        "description" => "master kids shape desc",
        "type" => OpenMetaverse\AssetType::Bodypart),
        
    "short black hair" => array(
        "filename" => "d1fe60ba-589a-e2fb-59f1-b0c0687260ff_bodypart.txt",
        "id" => "d1fe60ba-589a-e2fb-59f1-b0c068726000",
        "description" => "short black hair desc",
        "type" => OpenMetaverse\AssetType::Bodypart));       
                
# Asset upload
foreach ($assetInfos as $name => $assetInfo)
{
    $assetPath = "$ACABP_ASSETS_PATH/" . $assetInfo['filename'];
    #echo $assetPath;
    $assetData = file_get_contents($assetPath);
    
    echo "Uploading $name\n"; 
    AddAsset(
        $ASSET_SERVICE_URI, 
        CreateAssetXml(
            $assetData, $assetInfo['id'], $name, $assetInfo['description'], $assetInfo['type']));    
}      		

# Add associated textures for clothing/body parts
$texturePaths = glob("$ACABP_ASSETS_PATH/*_texture.jp2");
foreach ($texturePaths as $texturePath)
{
    $textureData = file_get_contents($texturePath);
    
    $textureFilename = basename($texturePath);
    $textureFilenameParts = explode("_", $textureFilename);
    $textureId = $textureFilenameParts[0];
    
    echo "Uploading texture $texturePath\n";
    AddAsset($ASSET_SERVICE_URI, CreateAssetXml($textureData, $textureId, $textureId, "", 0));
}

echo "fin\n";

?>