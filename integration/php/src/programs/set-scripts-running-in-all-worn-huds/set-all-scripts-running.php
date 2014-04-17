<?php

require_once "../../config.php";
require_once "$IP/connectors/useraccounts-service-connector.php";
require_once "$IP/connectors/avatar-service-connector.php";
require_once "$IP/connectors/inventory-service-connector.php";
require_once "$IP/connectors/asset-service-connector.php";
require_once "$IP/openmetaversetypes/enumsprimitive.php";

$options = getopt("", array("exec", "help"));

if (array_key_exists("help", $options))
{
    print "Usage: " . basename($_SERVER["SCRIPT_NAME"]) . " [--exec] [--help]\n";
    print "If --exec is not specified then a dry-run will be performed.\n";
    exit(-1);
}

// Stats
$usersAdjusted = 0;
$itemsAdjusted = 0;
$itemsNotFound = 0;
$assetsNotFound = 0;
$statesAdjusted = 0;

$userServiceResponseE = GetAllUserAccounts($USER_ACCOUNTS_SERVICE_URI);
//$userAccountEs = GetUserAccount($USER_ACCOUNTS_SERVICE_URI, "Justin", "Clark-Casey");

// Difficult to be more specific here since we get <account0>, <account1>, etc in return.
$userAccountEs = $userServiceResponseE->xpath("//node()[starts-with(name(), 'account')]");

foreach ($userAccountEs as $userAccountE)
{  
    $userId = (string)$userAccountE->PrincipalID;
    print "Assessing " . $userAccountE->FirstName . " " . $userAccountE->LastName . " " . $userId . "\n";
    $userRequiresAdjustment = FALSE;
       
    $avatarE = GetAvatar($AVATAR_SERVICE_URI, $userId);
    
    $hudItemEs = $avatarE->xpath("//node()[starts-with(name(), '_ap_')]");
    
    foreach ($hudItemEs as $hudItemE)
    {
        $name = $hudItemE->getName();
        $nameComponents = explode("_", $name);
        $attachmentPoint = (int)$nameComponents[2];
        
         // Make sure that we're dealing with a HUD
         if ($attachmentPoint >= OpenMetaverse\AttachmentPoint::HUDCenter2 
            && $attachmentPoint <= OpenMetaverse\AttachmentPoint::HUDBottomRight)
        {
            $hudItemId = (string)$hudItemE;       
            print "Item " . $hudItemId . " point " . $attachmentPoint . " is HUD\n";
            $hudItemE = GetItem($INVENTORY_SERVICE_URI, $hudItemId);
            
            if ($hudItemE == null)
            {
                print "No item found for " . $hudItemId . ".  Skipping!\n";
                $itemsNotFound++;
                continue;
            }
                         
            $assetId = (string)$hudItemE->AssetID;
                
            print "Item " . $hudItemE->Name . " ". $hudItemId . " references asset " . $assetId . "\n";
            
            $assetE = GetAsset($ASSET_SERVICE_URI, $assetId);
            
            if ($assetE == null)
            {
                print "No asset found for " . $assetId . ".  Skipping!\n";
                $assetsNotFound++;
                continue;
            }
            
            $assetDataString = base64_decode((string)$assetE->Data);
              //print GetPrettyXML($assetDataString);
            
              $assetDataE = new SimpleXmlElement($assetDataString);
            $scriptStateEs = $assetDataE->xpath("/SceneObjectGroup/GroupScriptStates/SavedScriptState/State/ScriptState");
            
            print "Script asset " . $assetId . " has " . count($scriptStateEs) . " script state(s).\n";
            
              $adjustCount = 0;
               
            foreach ($scriptStateEs as $scriptStateE)
            {
                if ((string)$scriptStateE->Running == "False")
                {                    
                    $adjustCount++;
                    $scriptStateE->Running = "True";
                }
                  
                  //print_r($scriptStateE);
              }
            
            print "Will adjust " . $adjustCount . " Running entries from False to True.\n";
            
            if ($adjustCount > 0)
            {
                $userRequiresAdjustment = TRUE;
                $itemsAdjusted++;
                $statesAdjusted += $adjustCount;
              
                //print GetPrettyXML($assetDataE->asXML());
                 
                if (array_key_exists("exec", $options))
                { 
                    print "Deleting " . $assetId . "\n";              
                    DeleteAsset($ASSET_SERVICE_URI, $assetId);
                    
                    print "Adding updated " . $assetId . "\n";
                    $assetE->Data = base64_encode($assetDataE->asXML());              
                    AddAsset($ASSET_SERVICE_URI, $assetE->asXML());
                      
                      // Retreive updated asset
    //                  $updatedAssetE = GetAsset($ASSET_SERVICE_URI, $assetId);
    //                $updatedAssetDataString = base64_decode((string)$updatedAssetE->Data);
    //                print "Updated asset " . $assetId;
                    //print GetPrettyXML($updatedAssetDataString);
                }              
            }
        }
    }        

    if ($userRequiresAdjustment)
        $usersAdjusted++;
}

if (!array_key_exists("exec", $options))
    print "Did not execute any operations in this dry run.  Use the --exec option to actually execute.\n";

print "Users requiring adjustment : " . $usersAdjusted . "\n";
print "Items requiring adjustment : " . $itemsAdjusted . "\n";
print "Items not found            : " . $itemsNotFound . "\n";
print "Assets not found           : " . $assetsNotFound . "\n";
print "States adjusted            : " . $statesAdjusted . "\n";

?>