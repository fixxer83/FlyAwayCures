<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('../dataUtils/downloadDataClass.php');

function downloaadItem($data)
{
    $items = $data;          

    if(checkIfFileExists($items) == true)
    { 
        downloadCheckedItem($items);
        resetErrorMsg();
    }
    
    header('Location' . PATH);
}


function downloadItem($data)
{
    resetErrorMsg();
    $items = $data;          
    
    foreach($items as $key)
    {
        downloadCheckedItem($key);
    }
    header('Location' . PATH);
}