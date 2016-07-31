<?php

include_once('../helper/favouritesHelperClass.php');
include_once('../helper/deletionHelperClass.php');
include_once('../helper/downloadsHelperClass.php');
include_once('../menuBarActions/checkedRowClass.php');
include_once('../validation/errorMessagingClass.php');


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');

//Applying the adequate operation for the set action
applyAction(getPost());

//Verifiying which action is set
function getPost()
{
    $action='';
    if(isset($_POST['add_to_fav']))
    {
        $action = 'add_to_fav'; 
    }
    else if((isset($_POST['rem_from_fav'])))
    {
        $action = 'rem_from_fav'; 
    }
    else if((isset($_POST['delete'])))
    {
        $action = 'delete';
    }
    else if((isset($_POST['download'])))
    {
        $action = 'download';
    }
    else if((isset($_POST['rename'])))
    {
        $action = 'rename';
    }
    else
    {
        setErrorMsg('We are encountering some problems, kindly try again later.'
                . ' Sorry for any inconvenience caused!', DASHBOARD_PATH);
    }
    return $action;
}

//Function to get the action session
function getAction()
{  
    session_start();
    
    if(isset($_SESSION['action']))
    {
        $action = $_SESSION['action'];
        $_SESSION['action'] = NULL;
        return $action;
    }
}

function applyAction($action)
{  
    resetErrorMsg();
    
    if($action == 'add_to_fav')
    {
        addToFavourites(getCheckedItems());
        header('Location:' . DASHBOARD_PATH);
    }
    else if($action == 'rem_from_fav')
    {
        removeFromFavourites(getCheckedItems());
        header('Location:' . DASHBOARD_PATH);    
    }
    else if($action == 'delete')
    {
        deleteItem(getCheckedItems());
        header('Location:' . DASHBOARD_PATH);    
    } 
    else if($action == 'download')
    {
        downloadItem(getCheckedItems());
        header('Location:' . DASHBOARD_PATH);    
    }
    else if($action == 'rename')
    {
        include '../helper/renameItemHelperClass.php';
        
        $newName = filter_input(INPUT_POST, 'rename_item_txt');
        
        $item = getCheckedItems();
        
        if(sizeof($item) > 1)
        {               
            setErrorMsg('Only one item needs to be selected!', DASHBOARD_PATH);
            exit();
        }
        elseif($item == '')
        {
            setErrorMsg('You need to select an item!', DASHBOARD_PATH);
            exit();
        }
        else
        {
            include_once('../validation/validateFileFolderClass.php');
            
//            session_start();
            
            $newName = '';
            
            if(isset($_POST['rename_item_txt']))
            {
                $newName = $_POST['rename_item_txt'];
            }
           
            if(validateBlankInput($newName) == true && validateCharInput($newName) == true)
            {
                attemptToRenameItem($newName, $item[0]);
                resetErrorMsg(); 
            }
            
            resetErrorMsg();
        }
    }  
    header('Location:' . DASHBOARD_PATH);    
}