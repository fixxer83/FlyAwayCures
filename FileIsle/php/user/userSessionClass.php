<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getParam($param)
{
   if($param == 'boom')
   {
       killCurrentSession();
   }
}

function killCurrentSession()
{
    session_start();
    session_destroy();
    session_unset();
    unset($_SESSION["logged_in"]);
    unset ($_SESSION["username"]);
    unset ($_SESSION["email"]);
    unset ($_SESSION["upload"]);
    
    if(isset($_SESSION['error_msg']))
    {
        unset ($_SESSION['error_msg']);
    }
    if(isset($_SESSION['action']))
    {
        unset ($_SESSION['action']);
    }
    
    if(isset($_SESSION['parent']))
    {
        unset ($_SESSION['parent']);
    }
    
    header('Location: ../../pages/fileIsleLoginPage.php');
    exit();
}

function initiateUserSessionAndSetVar($username, $email)
{
    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
}
