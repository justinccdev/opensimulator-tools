<?php

define('UUID_ZERO', '00000000-0000-0000-0000-000000000000');

/*
 * Generate a UUID as used by OpenSimulator.
 */
function GenerateUuid() 
{ 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $uuid = 
        substr($s, 0, 8) . '-' . 
        substr($s, 8, 4) . '-' . 
        substr($s, 12, 4). '-' . 
        substr($s, 16, 4). '-' . 
        substr($s, 20);
         
    return $uuid;
}

/*
 * Check if the given data is a UUID as processed by OpenSimulator.
 */
function IsUuid($data)
{
    return preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $data);
}

function ToBool($var) 
{
    //echo "Got '$var' ", is_string($var), "\n";
    if (!is_string($var)) 
        return (bool) $var;
    
    switch (strtolower($var)) 
    {        
        case '1':
        case 'true':
        case 'on':
        case 'yes':
        case 'y':
            return true;
        default:
            return false;
    }
}

function GetPrettyXML($xml) 
{
    $dom = new DOMDocument();
    // we want nice output
    $dom->preserveWhiteSpace = false;
    $dom->loadXML($xml);
    $dom->formatOutput = true;
    
    return $dom->saveXML();
}

?>
