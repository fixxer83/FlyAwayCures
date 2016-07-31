<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setEmailSession($email)
{
    session_start();
    
    if(!isset($_SESSION['email_add']))
    {
        $_SESSION['email_add'] = $email;
    }
}

