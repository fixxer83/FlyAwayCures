<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'parentDataClass.php';
include_once 'fileDataClass.php';
include_once 'folderDataClass.php';
include_once 'favouritesDataClass.php';

/*Will get all the children of type file and delete them accordingly for the
 * Current $parent_id
 */
function getAndDeleteFilesForCurrentParent($parentId, $email)
{  //Fetching all the children for the current parent
   $children = getChildrenFilesForCurrentParent($parentId, $email);
   
    if(is_array($children))
    {  
        //Looping through all children
        foreach($children as $child)
        {
            /*Checking if the child exists in the file table (if it exist then surely
             * the particular child is of type file) and will delete the existing ones accordingly
             */
            if(checkIfFileExists($child) !== false)
            {
                if(isFavourite($child) !== false)
                {
                    removeFromFavourites($child);
                }
                
                deleteFile($child, $email);
            }
        }
    }
    else
    {
       /*Checking if the child exists in the file table (if it exist then surely
        * the particular child is of type file) and will delete the existing ones accordingly
        */
       if(checkIfFileExists($child) !== false)
       {
           deleteFile($child, $email);
       }
       
    }
}


/*Will get all the children of type folder and delete them accordingly for the
 * Current $parent_id
 */
function getFoldersForCurrentParent($parentId, $email)
{  //Fetching all the children for the current parent
   $children = getChildrenFoldersForCurrentParent($parentId, $email); 
   
   return $children;
}

/*Will get all the children of type folder and delete them accordingly for the
 * Current $parent_id
 */
function getAndDeleteLeavesForCurrentParents($parentId, $email)
{  //Fetching all the children for the current parent
   $children = getChildrenFoldersForCurrentParent($parentId, $email); 
   
   //Looping through all children
   foreach($children as $child)
   {
       /*Checking if children exist in the file table (if they exist then surely
        * the particular child is of type file) and deleting the ones that exist
        * accordingly
        */
       if(checkIfFileExists($child) !== false)
       {
           deleteFile($child, $email);
       }
   }
}