<?php

require_once "attachment-classes.php";
require_once "add-assets.php";
require_once "add-inventory-items.php";
require_once "add-inventory-links.php";
require_once "$IP/connectors/useraccounts-service-connector.php";

############
### MAIN ###
############    

$options = getopt("", array("assets", "inventory", "all", "user:", "set:", "help"));

// Also print help if user doesn't put any parameters on the command line.
if (array_key_exists("help", $options) || count($argv) == 1)
{
    print "Usage: " . basename($_SERVER["SCRIPT_NAME"]) . " --assets|--inventory|--all --user <user-uuid> --set <set-path> [--help]\n";
    print "  user-uuid is a UUID relating to an existing user.\n";
    print "  set-path is a path which contains attachment data extracted from an OAR/IAR.\n"; 
    print "  --assets will upload attachment assets (this only needs to be done once for a set.\n";
    print "  --inventory will create inventory items and links for the configured user.\n";
    print "  --all will add both assets and inventory for the configured user.\n";
    print "  One of --assets, --inventory or --all must be specified.\n";
    exit(-1);
}

$doAssets = false;
$doInventory = false;

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

$ua = GetUserAccountById($USER_ACCOUNTS_SERVICE_URI, $USER_ID);

if ($ua == null)
{
    print "ERROR: No user with ID [$USER_ID] exists in the system\n";
    exit(-3);
}

if (!array_key_exists("set", $options))
{
    print "ERROR: You must specify an attachment set.\n";
    exit(-2);
}

$ATTACHMENTS_SET_PATH = $options['set'];

if (!is_dir($ATTACHMENTS_SET_PATH))
{
    print "ERROR: Attachment data dir $attachmentSetPath does not exist.\n";
    exit(-2);
}

if (array_key_exists("assets", $options))
    $doAssets = true;

if (array_key_exists("inventory", $options))
    $doInventory = true;

if (array_key_exists("all", $options))
{
    $doAssets = true;
    $doInventory = true;
}

if (!$doAssets && !$doInventory)
{
    print "ERROR: You must specific one or more of --assets, --inventory or --all.\n";
    exit(-2);
}

$attachments = GetAttachmentObjectAssetsInDir();

if ($doAssets)
{
    echo "Adding attachments from $ATTACHMENTS_SET_PATH for user $USER_ID\n";
    echo "===========\n";
    
    AddAttachmentObjectAssets($attachments);
    
    # Add associated non-object assets for attachments
    # XXX: Could store this in a map somewhere so that AddAssetsFromDir() can automatically upload all
    # no object assets.  
    # You should be able to add any asset type with this function, as long as you don't care about adding
    # an asset description.  This is never seen by the user anyway - it's on the item description that will
    # ever be seen. 
    AddAssetsFromDir("_texture.jp2", OpenMetaverse\AssetType::Texture);
    AddAssetsFromDir("_script.lsl", OpenMetaverse\AssetType::LSLText);
}

if ($doInventory)
{
    AddAttachmentInventoryItems($attachments);
    
    // Add object inventory links.  We are assuming that the current outfit folder already exists.  It will
    // not for a freshly created avatar but program/addclothesandbodyparts will add it when run.     
    $currentOutfitFolderTypeE 
        = GetFolderForType($INVENTORY_SERVICE_URI, $USER_ID, OpenMetaverse\AssetType::CurrentOutfitFolder);
        
    $currentOutfitFolderId = (string)$currentOutfitFolderTypeE->ID; 
    
    AddAttachmentInventoryLinks($currentOutfitFolderId, $attachments);
}   

print "===========\n";
print "Used attachments from $ATTACHMENTS_SET_PATH\n";
print "\n";

foreach ($attachments as $attachment)
{
    if ($doAssets)
    {
        print "Added object attachment asset\n";
        print "AssetID [$attachment->assetId]\n";
        print "\n";
    }
        
     if ($doInventory)
     {
        print "Added attachment\n";
        print "UserID  [$USER_ID]\n";
        print "AssetID [$attachment->assetId]\n";
        print "ItemID  [$attachment->itemId]\n";
        print "LinkID  [$attachment->linkId]\n";
        print "\n";
     }
}

print "fin\n";

?>