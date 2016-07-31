<?php

include_once '../dataUtils/editUserDetailsClass.php';
include_once '../validation/errorMessagingClass.php';
include_once '../user/editUserDetailsSessionClass.php';
session_start();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

initiateEditUserDetailsProcess();

function initiateEditUserDetailsProcess()
{
    resetErrorMsg();
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
        $user_details = getUserData($email);
        
        initiateUserDetailsSessionAndSetVar($user_details);
        
        header('Location: ../../pages/fileIsleEditDetailsPage.php');
        resetErrorMsg();
        exit();
        setErrorMsg('$email' . ' Unable to retrieve details, please try again later', '../../pages/fileIsleEditDetailsPage.php');
    }
    else
    {
        //session_start();
        setErrorMsg('$email' . ' Unable to retrieve details, please try again later', '../../pages/fileIsleEditDetailsPage.php');
    }  
}

