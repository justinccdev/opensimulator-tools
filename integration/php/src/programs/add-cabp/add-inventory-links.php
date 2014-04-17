<?php

require_once "add-cabp-config.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

############
### MAIN ###
############  

$currentOutfitFolderTypeE 
    = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, OpenMetaverse\AssetType::CurrentOutfitFolder);
    
$currentOutfitFolderId = (string)$currentOutfitFolderTypeE->ID; 
    
// Unfortunately, at the moment users created via ROBUST do not have a "Current Outfit" folder until the
// first time they log in with a version 3 viewer.  If this hasn't happened, then add this folder manually.
if ($currentOutfitFolderId == null)
{    
    print "Current outfit folder not present for $USER_ID, creating.\n";
    
    $currentOutfitFolderId = GenerateUuid();
    
    $rootFolderE = GetRootFolder($INVENTORY_SERVICE_URI, $USER_ID);
    
    AddFolder(
        $INVENTORY_SERVICE_URI, 
         "Current Outfit", 
        $currentOutfitFolderId, 
        (string)$rootFolderE->folder->ID, 
        $USER_ID, 
        OpenMetaverse\AssetType::CurrentOutfitFolder);
}
    
// FIXME: If we were using proper folder php side structures we wouldn't have to re-retireve the folder
// even if we had just created it.
$currentOutfitContentE 
    = GetFolderContent($INVENTORY_SERVICE_URI, $USER_ID, $currentOutfitFolderId);

// For now, just delete any existing links    
//foreach ($currentOutfitContentE->ITEMS->children() as $itemE)
//{
//    DeleteInventoryItem($INVENTORY_SERVICE_URI, $USER_ID, (string)$itemE->ID);
//}

$idEs = $currentOutfitContentE->xpath("ITEMS//ID");
$ids = array_map(function($idE) { return (string)$idE; }, $idEs);
DeleteInventoryItems($INVENTORY_SERVICE_URI, $USER_ID, $ids);

// Unfortunately this won't work on current OpenSimulator since there is no config parameter to allow
// purging of non-trash folder contents.
//DeleteFolderContents($INVENTORY_SERVICE_URI, (string)$currentOutfitFolderTypeE->ID);

// eyes
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "my eyes 2", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "000",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);
    
// skin
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 skin", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "001",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);
    
// shape
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "master kids shape", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "002",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);
    
// hair
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "short black hair", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "003",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);    
    
// top    
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 top", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "004",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);    
    
// pants  
AddInventoryItem(
    $INVENTORY_SERVICE_URI, 
    (string)time() . "student male kit 1 pants", 
    "", 
    GenerateUuid(),
    $CABP_INVENTORY_ITEM_ID_PREFIX . "005",
    $USER_ID, 
    $currentOutfitFolderId, 
    OpenMetaverse\AssetType::Link, 
    OpenMetaverse\InventoryType::Wearable,
    0);      
             
?>