<?php
//Imports
//include($_SERVER["DOCUMENT_ROOT"]. "\\CMT3313CW1\FileIsle\php\dataUtils\dataClass.php");
include("errorMessagingClass.php");
include("nameResolutionClass.php");

session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
const PATH = '../../pages/fileIsleLoginPage.php';

$parameter = filter_input(INPUT_GET, 'parameter');

//Initiating the validation process
conductLogin($parameter);

//The below function will be used to select the validation according to the parameter passed
function conductValidation($parameter)
{
    //Login Page Validation
    if($parameter == 'login')
    {
        return validateTextInputLogin();
    }
}

//Fetching validated form data
function conductLogin($parameter){
    
    if(conductValidation($parameter) == true)
    {
        include '../helper/loginHelperClass.php';
        
        conductUserLogin(getFormData());
        exit();
    }     
}

//Function to get the form data
function getFormData()
{
    $fields = array('username', 'pwd_input');
    $data = array();
    $counter = 0;
    
    foreach($fields as $field)
       {
           if (empty(filter_input(INPUT_POST, $field)))
           {
               setErrorMsg("Cannot submit an empty form", PATH);
               exit();
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

//Validating that no fields are left blank in the Login Page
function validateTextInputLogin()
{
    $fields = array('username', 'pwd_input');
       
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

