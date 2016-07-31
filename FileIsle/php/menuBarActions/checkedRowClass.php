<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getCheckedItems()
{
    $checked = '';
    if(isset($_POST['checked']))
    {
        $checked = $_POST['checked'];   
    }
    
    return $checked;   
}
