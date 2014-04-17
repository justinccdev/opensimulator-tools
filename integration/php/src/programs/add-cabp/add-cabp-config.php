<?php

require_once "../../config.php";

$ACABP_ASSETS_PATH = "$IP/programs/addclothesandbodyparts/assets";

// ID of a user that is used to test adding of clothing and body parts.
$USER_ID = "e4f3924a-5a7c-4e1a-bee7-aa96580f2515";

// Non-existing user ID for testing purposes
$DNE_USER_ID = "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa";

// Badly formed user ID for testing purposes
$BAD_USER_ID = "flushingmeadows";

// Common inventory item id prefix so that we can change this during add cabp testing.  
// Needs 3 additional hex characters on the end.
$CABP_INVENTORY_ITEM_ID_PREFIX = "99999999-9999-9999-9999-99999999c";

?>