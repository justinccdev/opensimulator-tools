# HACKING #

All connector functions (e.g. AddInventoryItem() on
inventory-service-connector.php) take a final optional debug boolean (false by
default).  If set to true, this will print the outgoing and incoming responses
to/from the ROBUST service.
