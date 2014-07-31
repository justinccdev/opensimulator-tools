<?php

require_once 'Console/CommandLine.php';
require_once "../../../config.php";
require_once "../../../utils.php";
require_once "$IP/connectors/useraccounts-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('firstName');
$parser->addArgument('lastName');
$parser->addArgument('password');
$parser->addArgument('email', array('optional' => true));
$parser->addArgument('UUID', array('optional' => true));
        
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
$password = $params->args['password'];

if (isset($params->args['email']))
    $email = $params->args['email'];
else
    $email = NULL;

if (isset($params->args['UUID']))
    $userId = $params->args['UUID'];
else
    $userId = NULL;

if ($userId !== NULL && !IsUuid($userId))
{
    print "ERROR: [$userId] is not a well-formed user UUID.\n";
    exit(-1);
}

CreateUserAccount($USER_ACCOUNTS_SERVICE_URI, $firstName, $lastName, $password, $email, $userId, TRUE);