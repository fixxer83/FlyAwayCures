<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$db = new mysqli('localhost', 'root', '', 'file_isle', '3306');
//Constants
const CURRENT_PATH = '../../pages/fileIsleDashboard.php';

if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');

function getParentForCurrentFile($fileName, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT parent_id FROM user_files f, user_details d WHERE f.file_name ='" 
            . $fileName ."' AND f.user_details_id = d.user_details_id "
            . "AND d.email ='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    if(mysqli_fetch_assoc($result) != NULL)
    {
        $row = mysqli_fetch_assoc($result);
        return $row["parent_id"];   
    }
    else
    {
        return false;
    } 
}


function getParentForCurrentFolder()
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT user_dir_id FROM user_dir f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.dir_name='" . $folderName . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    $row = mysqli_fetch_assoc($result);
    
    return $row["user_dir_id"];
}

/*This function may be used to fetch the current folder's parent id 
 * based on the folder name passed
 */
function getParentIdFromDb($folderName, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT user_dir_id FROM user_dir f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.dir_name='" . $folderName . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    $row = mysqli_fetch_assoc($result);
    
    return $row["user_dir_id"];
}

/*This function may be used to fetch the current folder's parent name 
 * based on the parent id passed
 */
function getParentNameFromDb($parentId, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT dir_name FROM user_dir f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.user_dir_id='" . $parentId . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    $row = mysqli_fetch_assoc($result);
    
    return $row["dir_name"];
}


function getChildrenFilesForCurrentParent($parentId, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT file_name FROM user_files f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.parent_id='" . $parentId . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    while ($row = mysqli_fetch_assoc($result))
    {
        $children[] = $row['file_name'];
    }
    
    return $children; 
}

function getChildrenFoldersForCurrentParent($parentId, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT dir_name FROM user_dir f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.parent_id='" . $parentId . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    $children ='';
    
    while ($row = mysqli_fetch_assoc($result))
    {
        $children[] = $row['dir_name'];
    }
    
    return $children; 
}

/*This function may be used to fetch the current folder's parent name 
 * based on the parent id passed
 */
function getParentPathFromDb($parentId, $email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $sql = "SELECT path FROM user_dir f INNER JOIN user_details d ON "
        . "f.user_details_id = d.user_details_id WHERE f.user_dir_id='" . $parentId . "'" 
        . " AND f.user_details_id = d.user_details_id AND d.email='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    $row = mysqli_fetch_assoc($result);
    
    return $row["path"];
}

//Function to save an uploaded file to the db - Includes the blob data type
function updateSize($updatedSize, $parentName)
{
    session_start();
    include_once 'parentDataClass.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Error adding file, please try again later!",DASHBOARD_PATH);
    }
    //Parameters
    $email = '';
    $date_modified = date("Y-m-d h:i:sa");
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
   
    $query = "UPDATE user_dir f SET dir_size=?, date_modified=? WHERE dir_name=? AND f.user_details_id="
            . "(Select user_details_id From user_details d WHERE d.user_details_id = f.user_details_id AND email=?);";
    
    if(!$statement = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
        mysqli_close($conn);
    }
    else
    {
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        $statement->bind_param("isss", $updatedSize, $date_modified, $parentName, $email);
        $statement->execute();
        mysqli_close($conn);
        header('Location:' . DASHBOARD_PATH);
    }
}