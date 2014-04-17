<?php

require_once "add-attachments-config.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

#################
### FUNCTIONS ###
#################

/*
 * This is a convenience function that kills the whole script if it detects failure.
 */
function AddObjectInventoryItem(
    $inventoryServiceUri, $itemName, $itemDesc, $itemId, $assetId, $userId, $folderId)
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
            OpenMetaverse\AssetType::Object,
            OpenMetaverse\InventoryType::Object,
            0);
            
    if ($responseE->RESULT == "False")
    {
        exit("Failure when adding \"$itemName\" $itemId to folder $folderId for $userId\n");
    }  
    
    return $responseE;             
}

/*
 * Add inventory items for the given attachments
 * 
 * @param $attachments Array of AttachmentData classes containing IDs for previously added assets.
 */
function AddAttachmentInventoryItems($attachments)
{
    global $INVENTORY_SERVICE_URI, $USER_ID;
    
    $objectsFolderTypeE = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, OpenMetaverse\AssetType::Object);   
    
    $i = 0;
    foreach ($attachments as $attachment)
    {
        $assetId = $attachment->assetId;
        
        $itemId = GenerateUuid();
        $itemName = "$itemId test attachment";         
         
        echo "Adding item [$itemName] $itemId, asset $assetId for $USER_ID\n";        

        AddObjectInventoryItem(
            $INVENTORY_SERVICE_URI,
            $itemName, 
              "", 
            $itemId,
            $assetId,
            $USER_ID, 
            (string)$objectsFolderTypeE->ID);
            
         $attachment->itemId = $itemId;
    }     
}
 
?>