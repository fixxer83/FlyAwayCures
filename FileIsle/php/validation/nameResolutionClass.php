<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// This function simply translates the name of a given element in a more readable way
function resolveName($field)
{
    if($field == "f_name"){
        
        return "First Name";
        
    }else if($field == "l_name"){
        
        return "Last Name";
        
    }else if($field == "email_add"){
        
        return "Email Address";
        
    }else if($field == "security_ans_input"){
        
        return "Security Answer";
        
    }else if($field == "username"){
        
        return "Username";
        
    }else if($field == "pwd_input"){
        
        return "Password";
        
    }else if($field == "conf_pwd_input"){
        
        return "Confirm Password";
        
    }
}
