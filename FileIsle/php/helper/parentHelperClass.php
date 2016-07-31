<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../dataUtils/folderDataClass.php';
include_once '../dataUtils/parentDataClass.php';
include_once '../validation/errorMessagingClass.php';

const DASH_PATH = '../../pages/fileIsleDashboard.php';


getParentIdFromUrl();

/*
 * This function may be used to get the parent id 
 * from the $_GET superglobal
 */
function getParentIdFromUrl()
{
    if(isset($_GET['parent']))
    {
        $parent = $_GET['parent'];

        if($parent == 'NULL')
        {
            getParentIdAndInitiateSession('NULL');
            exit();
        }
        else
        {
            $parent= $_GET['parent'];
            getParentIdAndInitiateSession($parent);
            exit();
        }
    } 
}

/*This function may be used to get the current's folder parent id and initiate
 * Parent session, so that we can populate the table according to the chosen
 * parent
 */
function getParentIdAndInitiateSession($folderName)
{
    if($folderName == 'NULL')
    {
        if (session_status() == PHP_SESSION_NONE)
        {
          session_start();
        }

        $_SESSION['parent'] = $folderName;

        header('Location:' . DASH_PATH);
    }
    else
    {
    session_start();
    $email = $_SESSION['email'];
    $parentId = getParentIdFromDb($folderName, $email);

    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }

    $_SESSION['parent'] = $parentId;

    header('Location:' . DASH_PATH);
    }    
}

function getParentId()
{
    session_start();
    return $_SESSION['parent'];
}


function updateParentSize($size)
{
    if(getParentId() !== '')
    {
        session_start();
        $email = $_SESSION['email'];
        
        $parentName = getParentNameFromDb(getParentId(), $email);
        $data = getFolderData($parentName);       
        $currentSize = $data[2];
        $updatedSize = '';
        
        $uploadedItemSize = floatval($size);
            
        $updatedSize = $uploadedItemSize + $currentSize;
        
        updateSize($updatedSize, $parentName);
    }
}