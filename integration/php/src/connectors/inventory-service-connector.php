<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

function GetRootFolder($serviceUri, $principalId, $debug = FALSE)
{
    return PostToService($serviceUri, "PRINCIPAL=" . $principalId . "&METHOD=GETROOTFOLDER", $debug);
}

/*
 * Get a folder's details (but not its contents)
 * 
 * @param string $serviceURI Inventory service URI
 * @param string $folderId UUID of folder
 * @return SimpleXmlElement folder XML element 
 */
function GetFolder($serviceUri, $folderId, $debug = FALSE)
{
    return PostToService($serviceUri, "ID=" . $folderId . "&METHOD=GETFOLDER", $debug);       
}

/*
 * Get child folders and items of a given folder.
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $principalId UUID of user
 * @param string $folderId UUID of folder
 * @return SimpleXmlElement contents XML element
 */
function GetFolderContent($serviceUri, $principalId, $folderId, $debug = FALSE)
{
    return PostToService($serviceUri, "PRINCIPAL=" . $principalId . "&FOLDER=" . $folderId . "&METHOD=GETFOLDERCONTENT", $debug);    
}

/*
 * Get child items of a given folder.
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $principalId UUID of user
 * @param string $folderId UUID of folder
 * @return SimpleXmlElement contents XML element
 */
function GetFolderItems($serviceUri, $principalId, $folderId, $debug = FALSE)
{
    return PostToService($serviceUri, "PRINCIPAL=" . $principalId . "&FOLDER=" . $folderId . "&METHOD=GETFOLDERITEMS", $debug);    
}

/*
 * Get the inventory system folder for a particular type (objects, notecards, current outfit, etc.).
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $principalId UUID of user
 * @param integer $type AssetType of folder
 * @return SimpleXmlElement folder XML element
 */
function GetFolderForType($serviceUri, $principalId, $type, $debug = FALSE)
{
    $params = array('PRINCIPAL' => $principalId, 'TYPE' => $type, 'METHOD' => "GETFOLDERFORTYPE");
    $responseE = PostToService($serviceUri, http_build_query($params), $debug);
    return $responseE->folder;    
}

/*
 * Get an item's details
 * 
 * @param string $serviceURI Inventory service URI
 * @param string $itemId UUID of folder
 * @return SimpleXmlElement item XML element.  null if no item was found. 
 */
function GetItem($serviceUri, $itemId, $debug = FALSE)
{
    $returnXml = PostToService($serviceUri, "ID=" . $itemId . "&METHOD=GETITEM", $debug);
    if (!isset($returnXml->item))
        return null;
    else
        return $returnXml->item;
}

/*
 * Add an inventory item to a user's inventory
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $itemName Name of item to add
 * @param string $itemDescription Description of item to add
 * @param string $itemId UUID of item to add.
 * @param string $assetId UUID of asset for this item.
 * @param string $userId UUID of the user
 * @param string $folderId UUID of the folder that should contain this item.
 * @param string $assetType AssetType
 * @param string $itemType InventoryType
 * @param string $wearableType WearableType
 * @return SimpleXmlElement Server response
 */
function AddInventoryItem(
    $serviceUri, $itemName, $itemDescription, $itemId, $assetId, $userId, $folderId, $assetType, $itemType, $wearableType, $debug = FALSE)
{
    $params 
        = array(
            'AssetID' => $assetId,
            'AssetType' => $assetType,
            'Name' => $itemName,
            'Owner' => $userId,
            'ID' => $itemId,
            'InvType' => $itemType,            
            'CreatorId' => $userId,
            'CreatorData' => "",
            'Description' => $itemDescription,
            'BasePermissions' => 581639,
            'CurrentPermissions' => 581632,            
            'NextPermissions' => 581639,
            'EveryOnePermissions' => 0,
            'GroupID' => "00000000-0000-0000-0000-000000000000",
            'GroupOwned' => "False",
            'GroupPermissions' => 0,
            'SalePrice' => 0,
            'SaleType' => 0,
            'Flags' => $wearableType,
            'CreationDate' => time(),
            'Folder' => $folderId,
            'METHOD' => "ADDITEM");                                 
            
    return PostToService($serviceUri, http_build_query($params), $debug);
}

/*
 * Add a new folder to a user's inventory
 * 
 * @param string $serviceUri Inventory service URI.
 * @param string $folderId Folder UUID.
 * @param string $parentFolderId Parent folder UUID.
 * @param string $userId User UUID.
 * @param integer $assetType AssetType value.
 */
function AddFolder($serviceUri, $folderName, $folderId, $parentFolderId, $userId, $assetType, $debug = FALSE)
{
    $params 
        = array(
            'Name' => $folderName,
            'ID' => $folderId,
            'ParentID' => $parentFolderId,
            'Owner' => $userId,
            'Type' => $assetType,
            'Version' => 1,
            'METHOD' => "ADDFOLDER");
            
    return PostToService($serviceUri, http_build_query($params), $debug);            
}

/*
 * Delete a folder's content.
 * This will only work for the trash folder in OpenSimulator at this time.
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $folderId UUID of folder for contents deletion
 * @return SimpleXmlElement Server response
 */
function DeleteFolderContents($serviceUri, $folderId)
{
    $params = array('METHOD' => "PURGEFOLDER", 'ID' => $folderId, $debug = FALSE);
    
    return PostToService($serviceUri, http_build_query($params), $debug);    
}

/*
 * Delete an inventory item
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $principalId User ID
 * @param string $itemId Item ID
 * @returns SimpleXmlElement Server response
 */
function DeleteInventoryItem($serviceUri, $principalId, $itemId, $debug = FALSE)
{
    $params = array('METHOD' => "DELETEITEMS", 'PRINCIPAL' => $principalId, 'ITEMS[]' => $itemId);
        
    return PostToService($serviceUri, http_build_query($params), $debug);    
}

/*
 * Delete multiple inventory items at once
 * 
 * @param string $serviceUri Inventory service URI
 * @param string $principalId User ID
 * @param array $itemIds Array of item ID strings
 * @returns SimpleXmlElement Server response
 */
function DeleteInventoryItems($serviceUri, $principalId, $itemIds, $debug = FALSE)
{
    $params = "METHOD=DELETEITEMS&PRINCIPAL=$principalId";
    
    foreach ($itemIds as $itemId)
    {
        $params .= "&ITEMS[]=$itemId";
    }
               
    return PostToService($serviceUri, $params, $debug);    
}

?>