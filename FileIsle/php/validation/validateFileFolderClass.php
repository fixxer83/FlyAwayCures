<?php
//Imports
include_once '../dataUtils/folderDataClass.php';
include_once '../helper/parentHelperClass.php';
//include 'errorMessagingClass.php';
session_start();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
if(!defined('PATH')) define('PATH', '../../pages/fileIsleDashboard.php');

//Initiating the validation process
addNewFolder();


function getInput()
{
   $field = "folder_name_txt";
   return filter_input(INPUT_POST, $field);
}

//The below function will be used to conduct the validation process
function conductValidation()
{
    return validateBlank() && validateInput();
}

//This function will add a new folder if and only if the conditions specified are met
function addNewFolder()
{
    resetErrorMsg();
    
    if(conductValidation() == 1)
    {
        if(checkIfFolderExists(getInput()) == false)
        {   $parent = getParentId();
            resetErrorMsg();
            writeFolderToDb($parent,getInput());
        }
        else
        {
            setErrorMsg("A folder with that name already exists!", PATH);           
        }
    }
}

function validateBlank()
{    
    if (empty(getInput()))
    {
        setErrorMsg("Name cannot be left blank!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
    }
        
    return true;
}

//Validating the folder name field is not left blank
function validateInput()
{
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST")
    {
        if (!preg_match('~^[-a-z0-9_-]+$~i', getInput()))
        {
            setErrorMsg("Kindly note that you may only enter any alphanumeric character (a-z, 1-9) and - _ only!", PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
        }
     }

    return true;
}

function validateBlankInput($input)
{
    session_start();
    
    if (empty($input) || $input = '')
    {
        setErrorMsg("Name cannot be left blank!", PATH);
        return false;
    }
    else
    {
        resetErrorMsg();
        return true;
    }
}

//Validating the folder name field is not left blank
function validateCharInput($input)
{
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') == "POST")
    {
        if (!preg_match('~^[-a-z0-9_-]+$~i', $input))
        {
            setErrorMsg("Kindly note that you may only enter any alphanumeric character (a-z, 1-9) and - _ only!", PATH);
            return false;
        }
        else
        {
            resetErrorMsg();
            return true;
        }
     }
}
  