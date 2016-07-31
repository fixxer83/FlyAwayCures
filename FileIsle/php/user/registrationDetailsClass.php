<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Killing the current set session
function killRegDetailsSession()
{
    unset($_SESSION['f_name_reg']);
    unset($_SESSION['l_name_reg']);
    unset($_SESSION['email_reg']);
    unset($_SESSION['sec_quest_reg']);
    unset($_SESSION['sec_answer_reg']);
    unset($_SESSION['username_reg']);
    unset($_SESSION['password_reg']);
    unset($_SESSION['conf_pwd_reg']);
    exit();
}

//Setting session on edit user details page call
function initiateRegDetailsSessionAndSetVar($data)
{
    session_start();
    $_SESSION['f_name_reg'] = $data[0];
    $_SESSION['l_name_reg'] = $data[1];
    $_SESSION['email_reg'] = $data[2];
    $_SESSION['sec_quest_reg'] = $data[3];
    $_SESSION['sec_answer_reg'] = $data[4];
    $_SESSION['username_reg'] = $data[5];
}
