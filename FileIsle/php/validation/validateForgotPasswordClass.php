<?php
//Imports
require('../dataUtils/dataClass.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Global Variables
//Inputted Data
$error_msg = '';
$parameter = filter_input(INPUT_GET, 'parameter');

//Initiating the validation process
conductValidation($parameter);

//The below function will be used to select the validation according to the parameter passed
function conductValidation($parameter)
{
    //Forgot Password Page Validation
    if($parameter == 'forgot_password')
    {
        return validateTextInputForgotPwd()  && validateDropDownSelection() && 
        validateEmail() && validatePassword() && matchPasswords();
    }
}

//Fetching validated form data
function conductPasswordReset($parameter){
    if(conductValidation($parameter) == 1)
    { 
        resetPasword(getFormData());
    }     
}

//Function to get the form data
function getFormData()
{
    $fields = array('security_quest', 'security_ans_input', 'email_add', 'pwd_input', 'conf_pwd_input');
    $data = array();
    $counter = 0;
    foreach($fields as $field)
       {
           if (empty(filter_input(INPUT_POST, $field)))
           {
               echo("Cannot submit an empty form!");
                return false;
           }
           else
           {
               $data[$counter] = filter_input(INPUT_POST, $field);
               
           }
           
           $counter ++;
        }
    return $data;
}

//Validating that no fields are left blank in the Forgot Password Page
function validateTextInputForgotPwd()
{
    $fields = array('security_ans_input', 'email_add', 'username'
        , 'pwd_input', 'conf_pwd_input');
    
   if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST")
   {
       foreach($fields as $field)
       {
           if (empty(filter_input(INPUT_POST, $field)))
           {
               echo("validateTextInput");
                return false;
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
          echo("Invalid email format");
          return false;
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
        if (filter_input(INPUT_POST,'security_quest') 
                == "default_val")
        {
          echo("Invalid Question");
          return false;
        }
        return true;
    }
}

//Validating password's content
function validatePassword()
{
    $password = filter_input(INPUT_POST,'pwd_input');
    if(filter_input(INPUT_SERVER,'REQUEST_METHOD') == "POST"){
        if (strlen($password) <6){
            echo("<6");
            return false;
        }
        if (strlen($password) > 12){
            echo(">12");
            return false;
        }
        if (!preg_match("#[a-zA-Z]+#", $password)){
            echo("a-zA-Z");
            return false;
        }
        if  (!preg_match("#[0-9]+#", $password)){
            echo("0-9");
            return false;
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
             echo("Pwds do not match");
             return false;
        } 
    }
    return true;
}