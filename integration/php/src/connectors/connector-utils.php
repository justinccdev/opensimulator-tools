<?php

function GetFromService($uri, $debug = FALSE)
{
    $ch = curl_init($uri);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if ($debug)
        PrintReturnDebugInfo($result, $info);
    
    if ($result == null)
        return $result;
    else    
        return new SimpleXmlElement($result);    
}

function PostToService($uri, $postFields, $debug = FALSE)
{
    if ($debug)
        echo "postFields:$postFields\n";
    
    $ch = curl_init($uri);
    
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if ($debug)
        PrintReturnDebugInfo($result, $info);   
    
    return new SimpleXmlElement($result);    
}

function DeleteFromService($uri, $debug = FALSE)
{
    $ch = curl_init($uri);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if ($debug)
        PrintReturnDebugInfo($result, $info);   
    
    return new SimpleXmlElement($result);         
}

function PrintReturnDebugInfo($curlResult, $curlInfo)
{
    echo "Return code:" . $curlInfo['http_code'] . "\n";
    echo "Return data: (below)\n" . GetPrettyXML($curlResult) . "\n";    
}

function CheckUuid($varName, $value)
{
    if (!IsUuid($value))
        throw new InvalidArgumentException("$varName '$value' is not a valid UUID");    
}
?>
