<?php
//Imports
include_once 'errorMessagingClass.php';

include_once '../data/SignUpNewUser.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Constants
const PATH = '../../Pages/signUp.php';

// Attempting Sign-Up process
attemptSignUp();

// The below function will be used to select the validation according to the parameter passed
function conductSignUpValidation()
{
    resetErrorMsg();
    // Conducting validation on the user input
    return (validateBlanks() &&
            validateFullName()&&
            validateEmail() &&
            validatePasswords() &&
            validateForExistingUser());
}

// Attempting to conduct registration
function attemptSignUp()
{
    session_start();
    
    if(conductSignUpValidation() == 1)
    {
        // Getting user inputted data
        $data = getFormData();
        
        SignUpUser($data); 
        
        return true;
    }
    
    return false;
}

/********** Getters **********/

// Getting the form data
function getFormData()
{
    $fields = array('fullname', 'email', 'password', 'confirm_password');
    $data = array();
    $i = 0;
    
    foreach($fields as $field)
    {
        if (empty(filter_input(INPUT_POST, $field)))
        {
            setErrorMsg("All fields are required!", PATH);
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
   $fields = array('fullname', 'email', 'password', 'confirm_password');
   
   return $fields;
}

/********** Functions **********/

// Function to validate fields for blanks
function validateBlanks()
{
    // Get Elements
    $fields = getFormElements();
    
    foreach($fields as $field)
    {
        // Check if blank values are detected
        if(empty(filter_input(INPUT_POST, $field)))
        {
            // Prompt user
            setErrorMsg($field . " is required!", PATH);
            
            return false;
        }
        else
        {
            resetErrorMsg();
        }
    }
    return true;    
}

// Function to validate for unallowed characters in the full name field
function validateFullName()
{
    // Get Form Data
    $fields = getFormData();
    
    // Pattern to match
    if(!preg_match('/^[a-zA-Z\s]+$/', $fields[0]))
    {
        setErrorMsg("Full name may only contain letters!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
        return true;
    }
}

// Function to validate email address
function validateEmail()
{
    // Get Form Data
    $fields = getFormData();
    
    /* 
     * Check user input against the 
     * FILTER_VALIDATE_EMAIL filter 
     */
    if (!filter_var($fields[1], FILTER_VALIDATE_EMAIL))
    {
        setErrorMsg("Please enter a valid email!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
        return true;
    }
}

// Function to validate password/s
function validatePasswords()
{
    $fields = getFormData();
    
    if($fields[2] != $fields[3])
    {
        setErrorMsg("Passwords do not match!", PATH);
        return false; 
    }
    else
    {
        resetErrorMsg();
    }
    if(strlen($fields[2]) <6 || strlen($fields[2]) >12)
    {
        setErrorMsg("Passwords must between 6 and 12 characters long!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
    }
    if(!preg_match("`^[a-zA-Z0-9_]{1,}$`", $fields[2]))
    {
        setErrorMsg("Your password should only contain upper and lower case letters (A-Z) and digits (0-9)!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
    }
    
    return true;
 }
 
 // Chacking if user already exists in the db
 function validateForExistingUser()
 {
     include_once '../data/signInUser.php';
     
     $fields = getFormData();
         
    if(getUserData($fields[1]) != false)
    {
        setErrorMsg('Please, try again!', '../../Pages/signUp.php');
        return false;
    }
    else
    {
        return true;        
    }
 }