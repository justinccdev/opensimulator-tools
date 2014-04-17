# README #

## Introduction ##

This is example PHP code for configuring an avatar's attachments externally via
OpenSimulator ROBUST services whilst they are not logged in.

Different attachment sets are contained in subfolders of the assets folder
(e.g. assets/simple-scripted).  Both user id and attachment set are specified
on the command line.

You will also need to edit parameters in the root config.php file if your
ROBUST services are not running on localhost with the default port (8003).

Finally, run the add-attachments.php file to perform attachments upload.

This file takes arguments to control whether assets are freshly uploaded,
inventory items created and which user and attachment set this is done for.  As
with clothing and bodyparts, it's only necessary to upload attachment assets
once, though repeated uploading of an existing asset ID will not cause a
failure - it will simply be ignored.

Please use the --help option on add-attachments.php for more details.

## Bugs ##

Although this is much more sophisticated than the cabp code, it is still only
intended as an example code.

As example code, a general major issue is that errors are rarely handled.
Partly this is because OpenSimulator's responses to errors are poor.  This
could be mitigated by performing various checks before certain actions (e.g.
checking that the user ID named actually exists).  Some of these may require
additional methods to be implemented on the connectors.

A specific major issue is that the code does not properly handle objects within
attachments.  These would currently be created as attachments in their own
right (inventory items and links) with unknown effects.  Resolving this may
require specifying the attachment serialized object assets manually, or by
analyzing addition information in the IAR which will indicate which asset IDs
are attachments and which are objects in attachment object inventories
(preferable).  

## Attachment sets ##

* assets/simple          - A very simple single prim HUD with no contents.  For testing.
* assets/simple-scripted - A very simple single prim HUD with a single script.  For testing.

## Examples ##

Example add-attachments.php invocations

$ php add-attachments.php --all --user 03ada8dd-b293-46ea-9d52-9c1a671f1434 --set assets/simple

Uploads assets and create inventory items and links using the assets in the
folder assets/simple/ for the user with ID 03ada8dd-b293-46ea-9d52-9c1a671f1434

$ php add-attachments.php --inventory --user 47387e0a-3ae3-4534-9627-f7f153f27ec2 --set assets/simple

Creates inventory items and links for the user
47387e0a-3ae3-4534-9627-f7f153f27ec2 using data from assets/simple/ with the
assumption that the asset data has already been uploaded (e.g. through the
command above).  There is actually no problem with uploading the asset data
multiple times since OpenSimulator will ignore subsequent upload attempts for
existing asset IDs.
