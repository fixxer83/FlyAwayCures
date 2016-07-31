<?php
//Imports
include($_SERVER["DOCUMENT_ROOT"]. "\\CMT3313CW1\FileIsle\php\dataUtils\dataClass.php");
include '/../user/registrationDetailsClass.php';
include("errorMessagingClass.php");
include("nameResolutionClass.php");

session_start();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
const PATH = '../../pages/fileIsleRegistrationPage.php';
//Variables
$parameter = filter_input(INPUT_GET, 'parameter');

//Initiating the validation process
conductRegistration($parameter);

//The below function will be used to select the validation according to the parameter passed
function conductValidation($parameter)
{
    //Registration Page Validation
    if ($parameter == 'registration')
    {

        return validateTextInputRegistration() && validateForAlphaChars() 
        && validateEmail() && validateDropDownSelection() && validatePassword()
        && matchPasswords();
    }
}

//Attempting to conduct registration
function conductRegistration($parameter)
{
    if(conductValidation($parameter) == 1)
    {
        //Getting user inputted data
        $data = getFormData();
        
        if(isUserAlreadyRegistered($data) == false)
        {
            setErrorMsg(registerUser($data), PATH);
        }
        else
        {
            setErrorMsg('Error registering user!', PATH);
        }
    }
}

//Function to get the form data
function getFormData()
{
    $fields = array('f_name', 'l_name', 'email_add', 'security_quest',
        'security_ans_input','username', 'pwd_input');
    
    $data = array();
    $counter = 0;
    foreach($fields as $field)
       {
           if (empty(filter_input(INPUT_POST, $field)))
           {
               setErrorMsg("Cannot submit an empty form", PATH);
           }
           else
           {
               
               $data[$counter] = filter_input(INPUT_POST, $field);
               resetErrorMsg();
           }
           
           $counter ++;
        }  
    return $data;
}

//Validating that no fields are left blank in the Registration Page
function validateTextInputRegistration()
{
    $fields = array('f_name', 'l_name', 'email_add', 'security_ans_input','username', 'pwd_input', 'conf_pwd_input');
    
   if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST")
   {
       foreach($fields as $field)
       {
           if (empty(filter_input(INPUT_POST, $field)))
           {
               setErrorMsg(resolveName($field) . " cannot be left blank!", PATH);
               return false;
           }
           else
           {
               resetErrorMsg();
           }
           
        }
   }
  return true;  
}

//Validating first and last name fields for non-alpha chars
function validateForAlphaChars()
{
    $fields = array('f_name', 'l_name');
    
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST")
    {
       foreach($fields as $field)
       {
           if (!preg_match('/^[a-zA-Z\s]+$/',filter_input(INPUT_POST, $field)))
           {
                setErrorMsg("First and Last names cannot contain numbers!", PATH);
                return false;
           }
           else
           {
               resetErrorMsg();
           }
       }
    }
   return true; 
}

//Validating email address
function validateEmail()
{
    $email = filter_input(INPUT_POST,'email_add');
    // check if e-mail address is well-formed
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST")
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          setErrorMsg("Invalid email!", PATH);
          return false;
        }
        else
        {
            resetErrorMsg();
        }
    }
    return true;
}

//Validating drop down selection
function validateDropDownSelection()
{
    // check if the selected value is the default one
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST")
    {           
        if (filter_input(INPUT_POST,'security_quest') == "default_val")
        {
          setErrorMsg("Kindly select a security question!", PATH);
          return false;
        }
        else
        {
            resetErrorMsg();
        }
    }
    return true;
}

//Validating password's content
function validatePassword()
{
    $password = filter_input(INPUT_POST,'pwd_input');
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST"){
        if (strlen($password) <6 || strlen($password) >12)
        {
         setErrorMsg("Password should be between 6 - 12 characters!", PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
        }
        if (!preg_match("#[a-zA-Z]+#", $password)){
            setErrorMsg("Password should contain both upper case and lower case letters!", PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
        }
        if  (!preg_match("#[0-9]+#", $password)){
            setErrorMsg("Password should contain both upper case (A) and lower case (a) letters!", PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
        }
    }
    return true;
}

//Verify that both password / confirm password fields match accordingly
function matchPasswords()
{
    $password = filter_input(INPUT_POST,'pwd_input');
    $conf_password = filter_input(INPUT_POST,'conf_pwd_input');
    // check if e-mail address is well-formed
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST")
    {
         if($password != $conf_password)
        {
             setErrorMsg("Passwords do not match!", PATH);
             return false;
        }else
        {
            resetErrorMsg();
        }
    }
    return true;
}