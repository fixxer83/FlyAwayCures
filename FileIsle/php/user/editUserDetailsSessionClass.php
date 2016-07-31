<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Killing the current set session
function killUserDetailsSession()
{
    unset ($_SESSION["f_name"]);
    unset ($_SESSION["l_name"]);
    unset ($_SESSION["email"]);
    unset ($_SESSION["sec_question"]);
    unset ($_SESSION["sec_answer"]);
    exit();
}

//Setting session on edit user details page call
function initiateUserDetailsSessionAndSetVar($data)
{
    session_start();
    $_SESSION['f_name'] = $data[0];
    $_SESSION['l_name'] = $data[1];
    $_SESSION['sec_quest'] = $data[2];
    $_SESSION['sec_answer'] = $data[3];
    
    header("Location: ../../pages/fileIsleEditUserDetails.php");
}

