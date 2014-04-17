<?php

require_once "../../config.php";

// XXX: No longer a constant - now specified with the --set switch on the command line.
// This corresponds to the directory name in the assets folder that corresponds to the assets set that
// we want to upload, create inventory items and create inventory links.
//$ATTACHMENTS_SET_NAME = "simple-scripted";

// XXX: Now specifying the path on the command line - this is more intuitive than having to remember
// that it resides in a fixed directory
//$ADD_ATTACHMENTS_ASSETS_PATH = "$IP/programs/addattachments/assets";

// XXX: USER_ID no longer a constant - now specified with the --user switch on the command line.
// ID of a user that is used to test adding of clothing and body parts.
// Also used to add attachments where we want to test a situation where the user also owns the attachments
//$OWNER_USER_ID = "e4f3924a-5a7c-4e1a-bee7-aa96580f2515";

// ID of a user that does not own the attachments
// Used for testing add attachments
//$NOT_OWNER_USER_ID = "e92bcf57-00e3-44b1-9e3b-586bfdcac4c3";

// ID of user for uploads
//$USER_ID = $OWNER_USER_ID;

?>