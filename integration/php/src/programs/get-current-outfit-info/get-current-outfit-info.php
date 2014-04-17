<?php

require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/useraccounts-service-connector.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/openmetaversetypes/enums.php";

############
### MAIN ###
############    

$options = getopt("", array("user:", "help"));

// Also print help if user doesn't put any parameters on the command line.
if (array_key_exists("help", $options) || count($argv) == 1)
{
    print "Usage: " . basename($_SERVER["SCRIPT_NAME"]) . " --user <user-uuid> [--help]\n";
    print "  user-uuid is a UUID relating to an existing user.\n";
    exit(-1);
}

if (!array_key_exists("user", $options))
{
    print "ERROR: You must specify a user ID using the --user switch\n";
    exit(-2);
}

$USER_ID = $options['user'];

if (!IsUuid($USER_ID))
{
    print "ERROR: [$USER_ID] is not a well-formed user UUID.\n";
    exit(-2);
}

# $ua = GetUserAccountById($USER_ACCOUNTS_SERVICE_URI, $USER_ID);

# if ($ua == null)
# {
#     print "ERROR: No user with ID [$USER_ID] exists in the system\n";
#     exit(-3);
# }

$currentOutfitFolderTypeE 
    = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, OpenMetaverse\AssetType::CurrentOutfitFolder);
    
$linksE = GetFolderItems($INVENTORY_SERVICE_URI, $USER_ID, (string)$currentOutfitFolderTypeE->ID);

print "Items for user " . $ua->result->FirstName . " " .  $ua->result->LastName . " $USER_ID:\n\n";

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
    
?>
