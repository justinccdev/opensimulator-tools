<?php

require_once "add-attachments-config.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

#################
### FUNCTIONS ###
#################

/*
 * Add inventory links to the "Current Outfit" folder which v3 viewers inspect to add attachments to the
 * avatar.
 * 
 * @param string $currentOutfitFolderId UUID of the user's current outfit folder.
 * @param AttachmentData[] $attachments Contains asset and inventory IDs added in previous stages for each attachment.
 */
function AddAttachmentInventoryLinks($currentOutfitFolderId, $attachments)
{
    global $INVENTORY_SERVICE_URI, $USER_ID;
    
    foreach ($attachments as $attachment)
    {
        $assetId = $attachment->assetId;
        $itemId = $attachment->itemId;        
                
        $itemName = "$itemId test attachment";
        $linkId = GenerateUuid();     
         
        echo "Adding item link [$itemName] $linkId pointing to $itemId for $USER_ID\n";        

        AddInventoryItem(
            $INVENTORY_SERVICE_URI, 
            $itemName, 
              "", 
            $linkId,
            $itemId,
            $USER_ID, 
            $currentOutfitFolderId, 
            OpenMetaverse\AssetType::Link, 
            OpenMetaverse\InventoryType::Object,
            0);   
            
         $attachment->linkId = $linkId;
    } 
}    
   
?>