<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once './errorMessagingClass.php';
include_once '../jsonParser/r2rParseGeolocation.php';
include_once '../jsonParser/r2rParseSearch.php';
include_once '../sessions/r2rSessions.php';


// Constants
const MAIN_PAGE_PATH = '../../Pages/mainPage.php';

getGeolocationAndSetSessions();

// this function will be used to
function getGeolocationAndSetSessions()
{
    $data = getFormData();  
    //$geolocation = array();
	
	ob_start();
    
    if(validateStep() == 1)
    {
        resetFlightInfoSessions();
        resetGeolocationSessions();
        
        $geolocation = getLatitudeAndLongitude($data[1]);
        
        if($geolocation[0] == '' || $geolocation[1] == '')
        {
            setErrorMsg('Your search yielded no results, please try again', MAIN_PAGE_PATH);
            exit();
        }
        else
        {
            setGeolocationSessions($geolocation);
            setFlightInfoSession($data);
            header('Location: ' . MAIN_PAGE_PATH);
        }
    }
}

// This function will be used to call the validation functions applicable for the page
function validateStep()
{
    resetErrorMsg();
    
    return (validateCountries()
            && validateDates());
}

// Getters
function getFormData()
{
    $fields = array('departing_country', 'destination_city', 'out_date', 'return_date');
    $data = array();
    $i = 0;
    
    foreach($fields as $field)
    {
        if (empty(filter_input(INPUT_POST, $field)))
        {
            setErrorMsg("Invalid data, please input all fields!", MAIN_PAGE_PATH);
            
        }
        else
        {
            $data[$i] = filter_input(INPUT_POST, $field);
            resetErrorMsg();
        }
        
        $i++;
    }  
    return $data;
}

// Getting the form elements
function getFormElements()
{
   $fields = array('departing_country', 'destination_city', 'out_date', 'return_date');
   
   return $fields;
}

// Validating countries
function validateCountries()
{
    $data = getFormData();
    $countries = array($data[0], $data[1]);
    
    foreach($countries as $country)
    {
        if(!preg_match('/^[a-zA-Z\s]+$/', $country))
        {
            setErrorMsg("Departing country & destination city can only contain letters!", MAIN_PAGE_PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
            return true;
        }
    }
}

// Validating dates
function validateDates()
{
    $data = getFormData();
    $dates = array($data[2], $data[3]);
    $today = date("Y-m-d");
    
    if($dates[0] < $today)
    {
       setErrorMsg("Outward date is in the past!", MAIN_PAGE_PATH);
       return false; 
    }
    else
    {
        resetErrorMsg();
    }
    
    if($dates[1] < $today)
    {
       setErrorMsg(" Return date is in the past!", MAIN_PAGE_PATH);
       return false; 
    }
    else
    {
        resetErrorMsg();
    }
    
    if($dates[0] > $dates[1])
    {
       setErrorMsg("Return date is smaller than the outward date!", MAIN_PAGE_PATH);
       return false;
    }
    else
    {
        resetErrorMsg();
    }
    
    resetErrorMsg();
    return true;
}