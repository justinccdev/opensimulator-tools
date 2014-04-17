<?php

require_once "add-attachments-config.php";
require_once "$IP/connectors/asset-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

#################
### FUNCTIONS ###
#################

/*
 * Handle serialized attachment object assets in the configured attachment set directory. 
 * 
 * FIXME: The MAJOR weakness here is that we assume all object assets are top-level attachment objects
 * and that none of them are part of the inventory of other objects.  If this needs to be fixed,
 * could split assets folder into a top-level containing this object xml and a contents/ folder for
 * everything else. 
 * 
 * @returns AttachmentData[] Attachment structrures containing asset IDs and asset data. 
 */
function GetAttachmentObjectAssetsInDir()
{
    global $ATTACHMENTS_SET_PATH;
    
    // We will populate this with AttachmentData structures for the attachment objects added.
    $attachments = array();
    
    $assetsGlob = "$ATTACHMENTS_SET_PATH/*_object.xml";
    
    echo "Looking for objects in $assetsGlob\n";
    
    $assetPaths = glob($assetsGlob);
    foreach ($assetPaths as $assetPath)
    {        
        $assetData = file_get_contents($assetPath);
        $assetFilename = basename($assetPath);
        $assetId = GetUuidFromAssetFilename($assetFilename);               
        //$assetId = GenerateUuid();
        
        echo "Found object asset $assetFilename with id $assetId\n";

        $attachment = new AttachmentData();
        $attachment->assetId = $assetId;
        $attachment->assetData = $assetData;
        $attachments[] = $attachment;
    } 
    
    return $attachments;         
}

/*
 * Add serialized attachment object assets previously read from the configured attachment set dir 
 * to the simulator
 * 
 * @param AttachmentData[] $attachments Attachment information.  These must contain asset data as well as the asset ID.
 */ 
function AddAttachmentObjectAssets($attachments)
{
    global $ASSET_SERVICE_URI;
    
    foreach ($attachments as $attachment)
    {
        echo "Adding attachment with id $attachment->assetId to $ASSET_SERVICE_URI\n";
        
        AddAsset(
            $ASSET_SERVICE_URI, 
            CreateAssetXml(
                $attachment->assetData, 
                $attachment->assetId, 
                $attachment->assetId, 
                "", 
                OpenMetaverse\AssetType::Object));        
    }
}

// Generic function used to add all non-object assets from files in the assets/ directory.
// These files themselves have been extracted from an IAR/OAR, hence the incorporation of the UUID
// into their filenames, a fact that we shall use.
function AddAssetsFromDir($fileExtension, $assetType)
{
    global $ATTACHMENTS_SET_PATH, $ASSET_SERVICE_URI;
    
    $assetsGlob = "$ATTACHMENTS_SET_PATH/*" . $fileExtension;
    
    echo "Uploading assets from $assetsGlob\n";
    
    $assetPaths = glob($assetsGlob);
    foreach ($assetPaths as $assetPath)
    {        
        $assetData = file_get_contents($assetPath);
        $assetFilename = basename($assetPath);
        $assetId = GetUuidFromAssetFilename($assetFilename);
        
        echo "Uploading asset $assetFilename\n";
        
        AddAsset(
            $ASSET_SERVICE_URI, 
            CreateAssetXml($assetData, $assetId, $assetId, "", $assetType));
    }    
} 

/*
 * Return the UUID component of an asset path
 * 
 * @param AttachmentData[] $assetFilename Filename of asset encoded with an asset ID (as extracted from an OAR or IAR). 
 */
function GetUuidFromAssetFilename($assetFilename)
{    
    $assetFilenameParts = explode("_", $assetFilename);
    return $assetFilenameParts[0];
}   

?>