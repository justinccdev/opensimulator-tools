<?php

require_once "add-cabp-config.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

#################
### FUNCTIONS ###
#################

/*
 * This is a convenience function that kills the whole script if it detects failure.
 */
function AddWearableInventoryItem(
    $inventoryServiceUri, $itemName, $itemDesc, $itemId, $assetId, $userId, $folderId, $assetType, $wearableType)
{ 
    $responseE 
        = AddInventoryItem(
            $inventoryServiceUri, 
            $itemName, 
            $itemDesc, 
            $itemId,
            $assetId,
            $userId,
            $folderId,
            $assetType,
            OpenMetaverse\InventoryType::Wearable,
            $wearableType);
            
    if ($responseE->RESULT == "False")
    {
        exit("Failure when adding \"$itemName\" $itemId to folder $folderId for $userId\n");
    }  
    
    return $responseE;             
}

############
### MAIN ###
############  

$bodypartFolderTypeE = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, 13);

// XXX: Prepending items with time string just to uniqify them for testing purposes
// eyes.
//
// Asset IDs match those we uploaded in add-assets.php
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI,
    (string)time() . "my eyes 2", 
    "", 
    $CABP_INVENTORY_ITEM_ID_PREFIX . "000",
    "4bb6fa4d-1cd2-498a-a84c-95c1a0e74000",
    $USER_ID, 
    (string)$bodypartFolderTypeE->ID, 
    OpenMetaverse\AssetType::Bodypart,
    OpenMetaverse\WearableType::Eyes); 
    
// skin
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 skin", 
    "", 
    $CABP_INVENTORY_ITEM_ID_PREFIX . "001", 
    "f90883b6-4dcb-eb6c-312b-bcf9bfbde000",
    $USER_ID, 
    (string)$bodypartFolderTypeE->ID,
    OpenMetaverse\AssetType::Bodypart,
    OpenMetaverse\WearableType::Skin);

// shape
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "master kids shape", 
    "",
    $CABP_INVENTORY_ITEM_ID_PREFIX . "002",
    "cb88c0b3-6874-a9d2-b046-209982656000",
    $USER_ID, 
    (string)$bodypartFolderTypeE->ID,   
    OpenMetaverse\AssetType::Bodypart,
    OpenMetaverse\WearableType::Shape); 
    
// hair
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "short black hair", 
    "", 
    $CABP_INVENTORY_ITEM_ID_PREFIX . "003",
    "d1fe60ba-589a-e2fb-59f1-b0c068726000",
    $USER_ID, 
    (string)$bodypartFolderTypeE->ID, 
    OpenMetaverse\AssetType::Bodypart,
    OpenMetaverse\WearableType::Hair);  
    
$clothingFolderTypeE = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, 5);     
    
// shirt/top
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 top", 
    "", 
    $CABP_INVENTORY_ITEM_ID_PREFIX . "004",
    "32012209-b656-6a32-2c87-97010974b000",
    $USER_ID, 
    (string)$clothingFolderTypeE->ID, 
    OpenMetaverse\AssetType::Clothing,
    OpenMetaverse\WearableType::Shirt);

// pants
AddWearableInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 pants", 
    "", 
    $CABP_INVENTORY_ITEM_ID_PREFIX . "005",
    "0d72de3d-afe3-c783-802e-7f322d564000",
    $USER_ID, 
    (string)$clothingFolderTypeE->ID, 
    OpenMetaverse\AssetType::Clothing,
    OpenMetaverse\WearableType::Pants);                                    

/*
$rootFolderResponseE = GetRootFolder($inventoryServiceUri, $userId);
$rootFolderE = $rootFolderResponseE->folder;
$rootFolderId = $rootFolderE->ID;
echo "$rootFolderId\n";

//$getFolderResponseE = GetFolder($inventoryServiceUri, $rootFolderId);
$rootFolderContentE = GetFolderContent($inventoryServiceUri, $userId, $rootFolderId);

$foldersE = $rootFolderContentE->FOLDERS;
$folderEs = $foldersE->children();

$clothingFolderE = null;
foreach ($folderEs as $folderE)
{
    if ($folderE->Name == "Objects")
    {
        $clothingFolderE = $folderE;
        break;
    }
}

if ($clothingFolderE != null)
{
    echo "Found clothing folder";
}
else
{
    exit("Did not find clothing folder");
}

$clothingFolderContentE = GetFolderContent($inventoryServiceUri, $userId, $clothingFolderE->ID);
*/
 
?>