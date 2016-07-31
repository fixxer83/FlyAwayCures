<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// setter to set the geolocation session
function setGeolocationSessions($geolocation)
{
    session_start();
    
    if(!isset($_SESSION['latitude']))
    {
        $_SESSION['latitude'] = $geolocation[0];
    }
    
    if(!isset($_SESSION['longitude']))
    {
        $_SESSION['longitude'] = $geolocation[1];
    }
}

// Resetting the geolocation sessions
function resetGeolocationSessions()
{
    session_start();
    if(isset($_SESSION['latitude']))
    {
        unset($_SESSION['latitude']);
    }
    
    if(isset($_SESSION['longitude']))
    {
        unset($_SESSION['longitude']);
    }
}

// Setting the countries session
function setFlightInfoSession($flightInfo)
{
    //session_start();
    
    if(!isset($_SESSION['outward']))
    {
        $_SESSION['outward'] = $flightInfo[0];
    }
    
    if(!isset($_SESSION['destination']))
    {
        $_SESSION['destination'] = $flightInfo[1];
    }
    
    if(!isset($_SESSION['out_date']))
    {
        $_SESSION['out_date'] = $flightInfo[2];
    }
    
    if(!isset($_SESSION['in_date']))
    {
        $_SESSION['in_date'] = $flightInfo[3];
    }
}

function getFlightInfoSession()
{
    $session_arr = array();
    if(!isset($_SESSION['outward']))
    {
        array_push($session_arr, $_SESSION['outward']);
    }
    
    if(!isset($_SESSION['destination']))
    {
        array_push($session_arr, $_SESSION['destination']);
    }
    
    if(!isset($_SESSION['out_date']))
    {
        array_push($session_arr, $_SESSION['out_date']);
    }
    
    if(!isset($_SESSION['in_date']))
    {
        array_push($session_arr, $_SESSION['in_date']);
    }
    
}

// Resetting the geolocation sessions
function resetFlightInfoSessions()
{
    session_start();
    
    if(isset($_SESSION['outward']))
    {
        unset($_SESSION['outward']);
    }
    
    if(isset($_SESSION['destination']))
    {
        unset($_SESSION['destination']);
    }
    
    if(isset($_SESSION['out_date']))
    {
        unset($_SESSION['out_date']);
    }
    
    if(isset($_SESSION['in_date']))
    {
        unset($_SESSION['in_date']);
    }
}

// Get flights array session
function getCountriesSessions()
{ $countries = array();
    if(isset($_SESSION['outward']) && isset($_SESSION['destination']))
    {
        array_push($countries,$_SESSION['outward']);
        array_push($countries,$_SESSION['destination']);
    }
    else
    {
        return null;
    }
}

// Get flights array session
function getFlightSessions()
{
    if(isset($_SESSION['flights_arr']))
    {
        return $_SESSION['flights_arr'];
    }
    else
    {
        return null;
    }
}