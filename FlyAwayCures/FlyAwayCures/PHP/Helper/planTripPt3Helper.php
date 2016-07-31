<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../sessions/flightSessions.php';
include_once '../sessions/r2rSessions.php';
include_once '../validation/validateSelectedFlight.php';

// Setting selected flight session
setSelectedFlightSession(refactorSelectedFlightData());
setSelectedFlightPriceSession(getPriceFromSession());

// Populating the selected flight table
function refactorSelectedFlightData()
{
    $rawData[] = getHiddenLabelValue();
    $newArr = null;
    
    foreach($rawData as $singleData)
    {
        $newArr .= str_replace(',', '', $singleData);
    }

    return $newArr;
}

// Get the hidden label value
function getHiddenLabelValue()
{
   $value = filter_input(INPUT_GET, 'hidden_lbl');
   
   return $value;
}

function headToMainPage()
{
    header("location: ../../Pages/mainPage.php");
   // exit();
}

function getPriceFromSession()
{
    $data = getSelectedFlightSession();
    $price = null;

        if(strpos($data,'Price') !== false)
        {
            $price = strpos($data,'Price');
        }
    
    
    return $price;
}

function finalizeBooking()
{
    // Terminating flight related sessions
    // R2R sessions
    resetGeolocationSessions();
    resetFlightInfoSessions();
    // Flight sessions
    resetFlightCountriesSession();
    resetSelectedFlightSession();
    resetSelectedFlightPriceSession();
}