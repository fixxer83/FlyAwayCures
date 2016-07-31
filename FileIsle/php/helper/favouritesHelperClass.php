<?php

include('../menuBarActions/checkedRowClass.php');
include('../dataUtils/favouritesDataClass.php');
include('../dataUtils/fileDataClass.php');
include('../dataUtils/folderDataClass.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const PATH = '../../pages/fileIsleDashboard.php';

function addToFavourites($favourite_name)
{    
    $item = '';          
    
    foreach($favourite_name as $key)
    {
        if(getFolderData($key)== 'NULL')
        {
            $item = getFileData($key);   
        }
        else
        {
            $item = getFolderData($key);
        }
        
        if(isFavourite($key) == false)
        { 
            writeItemToFavourites($item);
            resetErrorMsg();
            header('Location' . PATH);
        }
        else
        {
            setErrorMsg("Already added as a favourite!", PATH);
        }
    }
    
    header('Location' . PATH);
}

function removeFromFavourites($data)
{
    $items = $data;          
    
    foreach($items as $key)
    {
        if(isFavourite($key) == true)
        { 
            deleteItemFromFavourites($key);
            resetErrorMsg();
        }
    }
    
    header('Location' . PATH);
}