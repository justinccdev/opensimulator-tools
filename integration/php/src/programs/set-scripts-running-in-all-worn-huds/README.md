# README #

## Introduction ##

This is a script to set all scripts to run in attached HUDs.  

For a long time, HUDs were dispensed with scripts set not to run.  This was not detected because OpenSimulator 0.7.3-extended was ignoring
this flag and running scripts anyway.

OpenSimulator 0.7.4-extended, on the other hand, does not ignore this flag.

Thus, setallscriptsrunning.php exists to do an out-of-band adjustment of all existing serialized HUD assets to set all false Running flags
to true.  This kind of operation is not normally recommended because serialized objects are meant to be immutable.

## Configuration ##

setallscriptsrunning.php relies on the service endpoint settings in config.php.  By default these are for localhost:8003.  You will need to adjust
these if running this script anywhere else.

For the script to change assets successfully, you will need to set

[AssetService]
    AllowRemoteDelete = true
    AllowRemoteDeleteAllTypes = true

in Robust.ini.

## Preconditions ##

When executing setallscriptsrunning.php, you need to ensure that no users are logged in to the system and that no users will log in
whilst it is executing.

## Execution ##

Executing setallscriptsrunning.php on its own will do a dry-run and print a summary of how many states it would change at the end.

To actually perform the changes, add a --exec flag.

After execution, you need to clear the caches of all simulators using the ROBUST service with the command

fcache clear

on the console.  You can also manually delete 

$OPENSIM/bin/assetcache/

OpenSimulator will rebuild this if it isn't present

## Checking ##

You can check if the script has succeeded either by running it again without the exec flag, or by dumping and manually inspecting
HUD assets (as printed out by the script on the console).
