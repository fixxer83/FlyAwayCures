<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getIpAddress()
{
    $ipAddress = getenv('HTTP_CLIENT_IP')?:
    getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?:
    getenv('HTTP_FORWARDED_FOR')?:
    getenv('HTTP_FORWARDED')?:
    getenv('REMOTE_ADDR');
    
    return $ipAddress;
}

/*
 * This function may be used to get the user-agent / the browser that is being
 * used by the client
 */
function getBrowserDetails()
{
    return $_SERVER['HTTP_USER_AGENT'];
}


function browserNameResolver($browserName)
{
    if(strpos($browserName,'AppleWebKit/537.36 (KHTML, like Gecko) Chrome'))
    {
        return "Google Chrome";
    }
    else if(strpos($browserName,'Firefox'))
    {
       return "Mozilla FireFox"; 
    }
    else if(strpos($browserName,'Mozilla/5.0 (compatible, MSIE') || strpos($browserName, 'compatible; MSIE 6.0; Windows NT'))
    {
       return "Internet Explorer"; 
    }
    else if(strpos($browserName,'Opera/9.80') || strpos($browserName,'Opera/12.00'))
    {
       return "Opera"; 
    }
    else if(strpos($browserName,'(Macintosh; U; PPC Mac OS X 10_') || strpos($browserName,'Mozilla/5.0 (iPad'))
    {
       return "Safari"; 
    }
    else if(strpos($browserName,'Android'))
    {
       return "Android"; 
    }
    else if(strpos($browserName,'iPhone'))
    {
       return "iPhone"; 
    }
    else
    {
        return "Unidentified Browser";
    }
}
