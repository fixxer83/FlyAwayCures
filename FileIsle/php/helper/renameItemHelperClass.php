<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../dataUtils/favouritesDataClass.php';
//include '../validation/validateFileFolderClass.php';

const PATH = '../../pages/fileIsleDashboard.php';

function attemptToRenameItem($newName, $item_name)
{
    session_start();
    
        if(checkIfFileExists($item_name) == true)
        {
           if(checkIfFileExists($newName) == true)
            {
               setErrorMsg('Name enetered already exists, please try again! ', PATH);
               exit(); 
            }
            else
            { 
              renameEntry($newName, $item_name);  
              renameFile($newName, $item_name);
              resetErrorMsg();
              exit();
            }
        }
        else
        {
            if(checkIfFolderExists($newName) == true)
            {
                setErrorMsg('Name enetered already exists, please try again! ', PATH);
                exit();
            }
            else
            {
                renameFolder($newName, $item_name);
                renameEntry($newName, $item_name);
                resetErrorMsg();
                exit(); 
            }

        }
    }
