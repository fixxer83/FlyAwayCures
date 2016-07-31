<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Constants
const PATH = '../../Pages/mainPage.php';
include_once 'errorMessagingClass.php';
include_once '../data/selectedFlightData.php';
include_once '../Helper/planTripPt1Helper.php';
include_once '../Helper/planTripPt3Helper.php';

attempToAddSelectedFlight();

function attempToAddSelectedFlight()
{
    $flight_data = getSelectedFlightData();

    if(validateForm($flight_data) == true)
    {
        // Write selected flight to the db
        addSelectedFlight($flight_data);
        
        /* 
         * Restart from the beginning after the 
         * selected flight is written to the db
         */
        restartPlanning();
    }
}

function getSelectedFlightData()
{
	ob_start();
    $id = $buffer_data['coordinates'] = $_GET['coordinates'];
    $head = $buffer_data['header'] = $_GET['header'];
    $row = $buffer_data['row'] = $_GET['row'];
    $numOfPpl = $buffer_data['numOfPpl'] = $_GET['numOfPpl'];
    $totalPrice = $buffer_data['totalPrice'] = $_GET['totalPrice'];
    
    $selectedFlightData = array($id, $head, $numOfPpl, $totalPrice, $row);
  
    return $selectedFlightData;
}

// Function to validate fields mainly for blank hidden field
function validateForm($data)
{
    // Get Elements
    $rawSelectedFlightData = $data;
    $numOfPpl = $rawSelectedFlightData[2];
   
    // Check if blank values are detected
    if(empty($numOfPpl) || is_numeric($numOfPpl) == false)
    {
        // Prompt user
        setErrorMsg("Kindly enter the number of travellers!", PATH);

        return false;
    }
    else
    {
        resetErrorMsg();
    }
    
    return true;    
}