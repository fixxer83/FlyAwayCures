<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../validation/errorMessagingClass.php';
include_once '../data/signInUser.php';
include_once '../sessions/userSession.php';
include_once '../Helper/recentInfoHelper.php';
include_once '../data/recentInfoData.php';

// Attempt Sign In
verifyCredentials();

/********** Getters **********/

// Getting the form data
function getFormData()
{
    $fields = array('email', 'password');
    $data = array();
    $i = 0;
    
    foreach($fields as $field)
    {
        if (empty(filter_input(INPUT_POST, $field)))
        {
            setErrorMsg("Kindly enter your credentials to login!", PATH);
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
   $fields = array('email', 'password');
   
   return $fields;
}

// Verifying that the credentials do exist in the db and are correct
function verifyCredentials()
{
    $credentials = getFormData();
        
    if(getUserData($credentials[0]) != false)
    {
        $dbData = getUserData($credentials[0]);
        $credentials[1] = md5($credentials[1]);
        
        if($dbData[0] == $credentials[0] && $dbData[1] == $credentials[1])
        {
            
            $data = array(getIpAddress(), browserNameResolver(getBrowserDetails()), 'Login');
            
            error_log($data[0] . ", " . $data[1] . ", " . $data[2]);
            
            // Setting email session
            setEmailSession($credentials[0]);
                        
            // Writing recent info entry
            writeRecentInfo($data);
            
            header('Location: ../../Pages/mainPage.php');
        }
        else
        {
            setErrorMsg('Invalid credentials!', '../../Pages/signIn.php');
        }
    }
    else
    {
        setErrorMsg('Invalid credentials!!', '../../Pages/signIn.php');
        
    }
}
