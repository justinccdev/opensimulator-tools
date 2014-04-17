<?php

// Gathers data about UUIDs, etc. assigned to attachments as we go through the setup process.
class AttachmentData
{
    /*
     * Asset ID for the attachment.
     */
    public $assetId;
    
    /*
     * Asset data.  This will only be set if attachment assets are being added. 
     */
    public $assetData;
    
    /*
     * Item ID for the attachment.
     */
    public $itemId;
    
    /*
     * Item link ID in Current Outfit folder for the attachment.
     */
    public $linkId;
}

?>