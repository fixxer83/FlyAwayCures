<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once './geolocationApiHelper.php';

function getCoordinates()
{
    $city = getFormData();
    return getCoordinates($city[1]);
}

drawNewMap(getCoordinates());

// Draw new map according to the coordinates specified
function drawNewMap($coordinates)
{
    if($coordinates == "" || $coordinates == null)
    {
        echo google.maps.event.addDomListener(window, 'load', initialize());
    }
    else
    {
        echo google.maps.event.addDomListener(window, 'load', initialize($coordinates[0], $coordinates[1]));
    }
}