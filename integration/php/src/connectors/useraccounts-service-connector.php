<?php

require_once "$IP/utils.php";
require_once "$IP/connectors/connector-utils.php";

/*
 * Create a user account
 * 
 * @param string $serviceUri URI of user account service
 * @param string $firstName First name of user
 * @param string $lastName Last name of user
 * @param string $password Password for user
 * @param string $email Optional e-mail for user.
 * @param string $uuid  Optional uuid for user.  If not set then a random UUID will be generated for the account.
 * 
 * @returns null if no user was found, otherwise returns user account XML
 */
function CreateUserAccount($serviceUri, $firstName, $lastName, $password, $email = NULL, $uuid = NULL, $debug = FALSE)
{
    $params
        = array(
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Password' => $password,
            'METHOD' => 'createuser');
            
    if ($email !== NULL)
        $params['Email'] = $email;
    
    if ($uuid !== NULL)
        $params['PrincipalID'] = $uuid;
    
    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);
    
    // At the moment we take advantage of the fact that only errors will return a <result>
    if ($responseXml->result == "null")
        return null;
    else
        return $responseXml;    
}

/*
 * Get a user account
 * 
 * @param string $serviceUri URI of user account service
 * @param string $firstName First name of user
 * @param string $lastName Last name of user
 * 
 * @returns null if no user was found, otherwise returns user account XML
 */
function GetUserAccountByName($serviceUri, $firstName, $lastName, $debug = FALSE)
{
    $params 
        = array(
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'METHOD' => "getaccount");
            
    $responseXml = PostToService($serviceUri, http_build_query($params), $debug);
    
    // At the moment we take advantage of the fact that only errors will return a <result>
    if ($responseXml->result == "null")
        return null;
    else
        return $responseXml;
}

/*
 * Get a user account
 * 
 * @param string $serviceUri URI of user account service
 * @param string $uuid User UUID
 * 
 * @returns null if no user was found, otherwise returns user account XML
 */
function GetUserAccountById($serviceUri, $id, $debug = FALSE)
{
    $params 
        = array(
            'UserID' => $id,
            'METHOD' => "getaccount");
               
    $responseXml =  PostToService($serviceUri, http_build_query($params), $debug);
    
    // At the moment we take advantage of the fact that only errors will return a <result>
    if ($responseXml->result == "null")
        return null;
    else
        return $responseXml;
}       

// Get all user accounts
function GetAllUserAccounts($serviceUri, $debug = FALSE)
{
    $params 
        = array(
            'query' => "% %",
            'METHOD' => "getaccounts");
            
    return PostToService($serviceUri, http_build_query($params), $debug);
}

?>