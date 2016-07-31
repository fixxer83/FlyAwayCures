<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const HTTP_REQUEST_URL = "https://rome2rio12.p.mashape.com/Geocode";
const MASH_API = "OrLZQcdsO6mshZ6QJ3Xj1qFa1G1cp1vSkQIjsn5irVKotprNND";

// Rome2Rio URL getter
function getGeoCodeUrl()
{
    return HTTP_REQUEST_URL;
}

// Mashape API key getter
function getGeolocationAPIKey()
{
    return MASH_API;
}

// Get Request
function getGeoCode($city)
{
    $context = stream_context_create(array('http'=> array('method' => 'GET', 
        'header' => 'X-Mashape-Key:' . getGeolocationAPIKey())));
    
   $json = file_get_contents(getGeoCodeUrl() . "?query=" . $city, false, $context);
   
   return $json;
}


