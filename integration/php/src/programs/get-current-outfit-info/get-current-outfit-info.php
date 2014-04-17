<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/connectors/useraccounts-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

############
### MAIN ###
############    

$parser = new Console_CommandLine();

$parser->addArgument('userUUIDs', array('multiple' => true));
        
try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
}

$userIds = $params->args['userUUIDs'];

foreach ($userIds as $userId)
{
    if (!IsUuid($userId))
    {
        print "ERROR: [$userId] is not a well-formed user UUID.\n";
        continue;
    }

    $ua = GetUserAccountById($USER_ACCOUNTS_SERVICE_URI, $userId);
    
    if ($ua == null)
    {
        print "ERROR: No user with ID [$USER_ID] exists in the system\n";
        continue;
    }
    
    $currentOutfitFolderTypeE 
        = GetFolderForType($INVENTORY_SERVICE_URI, $userId, OpenMetaverse\AssetType::CurrentOutfitFolder);
        
    $linksE = GetFolderItems($INVENTORY_SERVICE_URI, $userId, (string)$currentOutfitFolderTypeE->ID);
    
    print "Items for user " . $ua->result->FirstName . " " .  $ua->result->LastName . " $userId:\n\n";
    
    foreach ($linksE->ITEMS->children() as $linkE)
    {
        // Chase down linked item details
        $itemE = GetItem($INVENTORY_SERVICE_URI, (string)$linkE->AssetID);           
        $assetType = (string)$itemE->AssetType;
        $invType = (string)$itemE->InvType;
        
        // Wearable flags only occupy the lowest byte of flags
        $wearableType = intval((string)$itemE->Flags) & 0xff;    
        
        print $linkE->getName() . "\n";
        print "Name           : $linkE->Name\n";
        print "Description    : $linkE->Description\n";
        print "Link ID        : $linkE->ID\n";
        print "Inventory ID   : $linkE->AssetID\n";
        print "Asset ID       : $itemE->AssetID\n";    
        print "Asset Type     : " . OpenMetaverse\AssetType::GetName($assetType) . " ($assetType)\n";
        print "Inventory Type : " . OpenMetaverse\InventoryType::GetName($invType) . " ($invType)\n";    
        print "Wearable Type  : ";
        
        // Wearabletype flags only apply to wearables
        if ($invType == OpenMetaverse\InventoryType::Wearable)
            print OpenMetaverse\WearableType::GetName($wearableType) . " ($wearableType)\n";
        else
            print "n/a\n";
        
        print "\n";
    } 
}
    
?>
