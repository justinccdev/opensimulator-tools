README

== USAGE ==

This is a program to test retrieval of textures from an OpenSimulator simulator instance.

To extract a list of texture ids for testing, you can use the command.

mysql -NB -u<user> -p<password> <db-name> -e "select id from assets where AssetType=0;" > textures.txt

against the ROBUST service database (assuming a grid configuration).

To perform the test, you will also need a valid capability URL.  You can obtain one by logging in a viewer 
and then executing "show caps" on the region console.  One of the lines will be something like

GetTexture                             /CAPS/301b5e2e-cf41-4b80-ad32-0123ca399ef3/

but with a different UUID.  These are only available for logged in viewers since they are uniquely
generated for a particular user and then disposed of when they log out or move away from the simulator.

The CAPS part is combined with the standard HTTP url to get the full GetTexture
capability URL.  Assuming that the simulator is at 127.0.0.1 with an HTTP port of 9000 (which is the default), 
this will be 

http://localhost:9000/CAPS/301b5e2e-cf41-4b80-ad32-0123ca399ef3/

This is used with the TextureLoadTest.exe, that has the usage syntax

TextureLoadTest.exe <GetTexture-capability-URL> <texture-ids-file>

For instance,

./TextureLoadTest.exe http://localhost:8002/CAPS/301b5e2e-cf41-4b80-ad32-0123ca399ef3/ textures.txt
