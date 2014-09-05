<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/useraccounts-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('firstName');
$parser->addArgument('lastName');
        
try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

$firstName = $params->args['firstName'];
$lastName = $params->args['lastName'];

GetUserAccountByName($USER_ACCOUNTS_SERVICE_URI, $firstName, $lastName, TRUE);

?>