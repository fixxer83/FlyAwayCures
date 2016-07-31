<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Setting the flight countries sessions
function setFlightCountriesSession($countries)
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    if(isset($_SESSION['from_country']))
    {
        $_SESSION['from_country'] = $countries[0];
    }
    
    if(isset($_SESSION['to_country']))
    {
        $_SESSION['to_country'] = $countries[0];
    }
    
}

// Resetting the flight countries sessions
function resetFlightCountriesSession()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    if(isset($_SESSION['from_country']))
    {
        unset($_SESSION['from_country']);
    }
    
    if(isset($_SESSION['to_country']))
    {
        unset($_SESSION['to_country']);
    }
}

// Set Selected Flight Session
function setSelectedFlightSession($selectedFlightData)
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    $_SESSION['selected_flight'] = $selectedFlightData;
     
}

// Get Selected Flight Session
function getSelectedFlightSession()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    $selFlight = $_SESSION['selected_flight'];
    
    return $selFlight;
}

// Resetting the flight countries sessions
function resetSelectedFlightSession()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    if(isset($_SESSION['selected_flight']))
    {
        unset($_SESSION['selected_flight']);
    }
}

function setSelectedFlightPriceSession($price)
{
	ob_start();
	
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    $_SESSION['flight_price'] = $price;
	
	ob_start();    
    header("location: ../../Pages/mainPage.php");
}

function resetSelectedFlightPriceSession()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    if(isset($_SESSION['flight_price']))
    {
        unset($_SESSION['flight_price']);
    }
}
