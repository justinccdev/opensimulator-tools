# README #

This is example PHP code for configuring an avatars clothing externally from
OpenSimulator ROBUST whilst they are not logged in.

This example code consists of 3 files,

1. add-assets.php
2. add-inventory-items.php
3. add-inventory-links.php

If the example assets are already present there is no need to subsequently run
add-assets.php though doing so will be harmless.  If the example inventory
items are already present for a particular user then there is no need to run
add-inventory-items.php.  Doing so may not be harmless (not yet tested).

The code assumes that a user already exists, so you will need to change the
$USER_ID global variable in config.php appropriately.  There is also a
$CABP_INVENTORY_ITEM_ID_PREFIX which will also need to be changed if the code
is run against a ROBUST service more than once, since inventory IDs must be
unique across all avatar inventories.
