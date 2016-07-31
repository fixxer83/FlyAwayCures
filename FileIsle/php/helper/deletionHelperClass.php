<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../dataUtils/fileDataClass.php';
include_once '../dataUtils/favouritesDataClass.php';
include_once '../dataUtils/folderDataClass.php';
include_once '../dataUtils/deleteDataClass.php';
include_once '../dataUtils/parentDataClass.php';
include_once '../validation/errorMessagingClass.php';

const DASH_PATH = '../../pages/fileIsleDashboard.php';

function deleteItem($data)
{
    session_start();
    $email = '';
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }

    if(is_array($data))
    {    
        foreach($data as $item)
        {
            //If current $item is a dir (not a file)
            if(checkIfFileExists($item) == false)
            {
                //Deleting children files
                $childrenFolders = conductDirectoryLevelCleanUp($item, $email);

                if(is_array($childrenFolders))
                {
                    foreach($childrenFolders as $child)
                    {
                        removeFromFavourites($child);
                        deleteItem($child);
                    }
                }
                else
                {
                    removeFromFavourites($child);
                    deleteItem($child);                    
                }
                
                deleteDir($item, $email);
            }
            else
            {                
                removeFromFavourites($child);
                deleteFile($item,$email);
            }
        }
    }
    else
    {
        //If current $item is a dir (not a file)
        if(checkIfFileExists($data) == false)
        {
            //Deleting children files
            $children_folders = conductDirectoryLevelCleanUp($data, $email);

            foreach ($children_folders as $child)
            {
                deleteItem($child);
            }

            deleteDir($data, $email);
        }
        else
        {
            if(isFavourite($item) == true)
            {
                removeFromFavourites($item);  
            }
            deleteFile($item,$email);
        } 
    }
    header('Location' . DASH_PATH);
}

/*
 * This function may be used to fetch the parent id, get and delete the files
 * and return the children for a specified child folder 
 */
function conductDirectoryLevelCleanUp($folderName, $email)
{
    $levelParentId = getParentIdFromDb($folderName, $email);
    //Getting the current parent's children of type file and delete them
    getAndDeleteFilesForCurrentParent($levelParentId, $email);

    //Get children Folders for level 1 child
    return getChildrenFoldersForCurrentParent($levelParentId, $email);
}
