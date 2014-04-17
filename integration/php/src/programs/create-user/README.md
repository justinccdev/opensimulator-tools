# README #

This example script creates a new user using the details given.

## Requirements ##
The OpenSimulator ROBUST instance must have

[UserAccountService]
    AllowCreateUser = true

Set in Robust.ini or another appropriate config file.

See [1] for more information.

## Example ##
$ php create-user.php Timmy Mallet test
postFields:FirstName=Timmy&LastName=Mallet&Password=test&METHOD=createuser
Return code:200
Return data: (below)
<?xml version="1.0"?>
<ServerResponse>
  <result type="List">
    <FirstName>Timmy</FirstName>
    <LastName>Mallet</LastName>
    <Email></Email>
    <PrincipalID>58bb3a7e-5501-49f0-98bc-94fa3a1cb074</PrincipalID>
    <ScopeID>00000000-0000-0000-0000-000000000000</ScopeID>
    <Created>1397771334</Created>
    <UserLevel>0</UserLevel>
    <UserFlags>0</UserFlags>
    <LocalToGrid>True</LocalToGrid>
    <ServiceURLs>HomeURI*;GatekeeperURI*;InventoryServerURI*;AssetServerURI*;</ServiceURLs>
  </result>
</ServerResponse>

## References ##
[1] http://opensimulator.org/wiki/UserManipulation
