<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($_GET['signOut']))
{
    signOutUser();
}

function signOutUser()
{
    session_start();
    $_SESSION = array();
    session_destroy();
    
    header('location: ../../Pages/signIn.php');
}