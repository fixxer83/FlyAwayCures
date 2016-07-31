<?php

include_once '../dataUtils/fileDataClass.php';
include_once '../dataUtils/parentDataClass.php';
include_once '../files/fileUploadClass.php';
include_once '/parentHelperClass.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const CURRENT_PATH = '../../pages/fileIsleDashboard.php';

//Calling the function to add a file
fileAddition(getFileContents());

/*
 *This function will call the function checkIfFileExists() which will check whether
 *The current file that the user uploaded exist or not If it doesn't exist it will 
 * write the file to the database and if not it won't function fileAddition($data)
 */
function fileAddition($data)
{ 
    session_start();
    
    if(checkIfFileExists($data) == false)
    {
        $parent = getParentId();
        $email = writeFileToDb($parent, $data);
        resetErrorMsg();
        $parent_name = getParentNameFromDb($parent, $email);
        getParentIdAndInitiateSession($parent_name);
        updateParentSize($data[1]);
    }
    else
    {
        setErrorMsg("File already exists!", CURRENT_PATH);
    }
}