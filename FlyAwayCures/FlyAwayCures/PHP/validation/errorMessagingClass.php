<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//This function may be used to set the error message text
function setErrorMsg($error, $header)
{
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }
    
     if(isset($_SESSION['error_msg']))
    {
        $_SESSION['error_msg'] = $error;
    }
    else
    {
        $_SESSION['error_msg'] = $error;
    }
    
    header('Location:' . $header);
}

//Unsets the session which consequently resets the error message
function resetErrorMsg()
{
    if(!isset($_SESSION['error_msg']))
    {
       // session_start();
    }
        
    if(isset($_SESSION['error_msg']))
    {
        unset($_SESSION['error_msg']);
    }
}