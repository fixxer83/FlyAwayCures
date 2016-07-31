<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function conductUserLogin($userData)
{
    include '../dataUtils/dataClass.php';
    include '../dataUtils/auditDataClass.php';
    include '../helper/auditHelperClass.php';
    
    $auditData = array(getIpAddress(), browserNameResolver(getBrowserDetails()), 'Login');
    
    $userDetails = logInUser($userData);
    if($userDetails != "User not found!")
    {
      initiateUserSessionAndSetVar($userDetails[0], $userDetails[1]);  
    }
    else if($userDetails == "User not found!")
    {
        setErrorMsg('User not found!', PATH);
        exit();
    }
    
    header('Location: ../../pages/fileIsleDashboard.php');
    
    writeAuditEntry($auditData);
}