<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Get the geolocation (r2r) and parse it accordingly
function getLatitudeAndLongitude($city)
{
    include_once '../r2rFunctions/r2rGeolocationAPI.php';
    
    $response = getGeoCode($city);
    $responsArr = explode(',', $response);

    $json = json_decode($response);
    
    if($responsArr[3] == '' || $responsArr[3] == null)
    {
        return NULL;
    }
    
    // Parse $latitude
    $latitude = $json->places[0]->lat;
    // Parse $longitude
    $longitude = $json->places[0]->lng;
    $coordinates = array($latitude, $longitude);
    
    return $coordinates;
}