<?php

include '../dataUtils/FolderDataClass.php';
include_once '../dataUtils/parentDataClass.php';
include_once '/parentHelperClass.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

addNewFolder(getInput());

//This function will call the function checkIfFileExists() which will check whether
//The current file that the user uploaded exist or not
//If it doesn't exist it will write the file to the database and if not it won't
function addNewFolder($folder_name)
{ 
    session_start();
    
    if(checkIfFolderExists($folder_name) == false)
    {
        $parent = getParentId();
        $email = writeFolderToDb($parent,$folder_name);
        resetErrorMsg();
        $parent_name = getParentNameFromDb($parent, $email);
        getParentIdAndInitiateSession($parent_name);
    }
    else
    {
        setErrorMsg("File already exists!", PATH);
    }
}