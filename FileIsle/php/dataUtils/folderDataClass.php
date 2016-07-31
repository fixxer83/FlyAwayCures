<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
//const DB_HOST = 'localhost';
//const DB_PORT = '3306';
//const DB_USER = 'root';
//const DB_PASSWORD = '';
//const DB_NAME = 'file_isle';

if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');

//Constants
//const DASHBOARD_PATH = '../../pages/fileIsleDashboard.php';

//Function to write a folder to the db
function writeFolderToDb($parentId, $folderName)
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

    //Parameters
    $user_dir_id = uniqid();
    $dir_name = $folderName;
    $dir_size = 0;
    $type = 'dir';
    $date_added = date("Y-m-d h:i:sa");
    $is_fav = 0;
    $parent_path = $target_path = '';
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    //Checking whether the $parentId is null or not
    if($parentId == 'NULL')
    {
        $parentId = 'Null';
        $target_path = "uploads" . stripslashes($dir_name);        
    }else
    {
        $parent_path = getParentPathFromDb($parentId, $email);
        $target_path = "uploads" . stripslashes($parent_path) . "/" . basename($dir_name);
    }
    
    $query="INSERT INTO user_dir (user_dir_id, dir_name, dir_size, type, date_added, parent_id, is_fav, user_details_id, path)"
                . " VALUES(?, ?, ?, ?, ?, ?, ?, (SELECT user_details_id From user_details Where email=?), ?);";

    if(!$statement = $conn->prepare($query))
    {
        mysqli_close($conn);
        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
    }
    else
    {
        resetErrorMsg();
        //Set statement, bind parameters and execute
        $statement = $conn->prepare($query);
        $statement->bind_param("ssisssiss", $user_dir_id, $dir_name, $dir_size, $type, $date_added, $parentId, $is_fav, $email, $target_path);
        $statement->execute();
        mysqli_close($conn);
        
        header('Location:' . DASHBOARD_PATH);

        return $email;    
    }
}

//This function will be used to verify if the folder specified exists in the db
function checkIfFolderExists($folderName)
{   
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
        exit();
    }
    
    $email = '';
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    
    $check="SELECT * FROM user_dir f INNER JOIN user_details d ON "
            . "f.user_details_id = d.user_details_id WHERE f.dir_name ='" 
            . $folderName ."' AND f.user_details_id = d.user_details_id AND d.email ='" . $email . "';";
    
    $res_set = mysqli_query($conn,$check) or die("SQL Error: ".mysqli_error($conn));
    $result = mysqli_fetch_array($res_set, MYSQLI_NUM);
    
    if($result[0] > 1) 
    {
        mysqli_close($conn);
        return true;
    }
    else
    {
        mysqli_close($conn);
        return false;
    }
}

/*
 * This function may be used to delete a directory (folder) according to the 
 * directory name and email specified
 */
function deleteDir($dirName,$email)
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
    
    $query="Delete FROM user_dir WHERE user_details_id ="
            ."(SELECT user_details_id FROM user_details WHERE email=?)"
            ."AND dir_name =?;";

    if(!$statement = $conn->prepare($query))
    {
        mysqli_close($conn);
        setErrorMsg('SQL Error: ' . $conn->error, DB_PATH);
    }
    else
    {
        resetErrorMsg();
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        
        $statement->bind_param("ss", $email, $dirName);
        
        $statement->execute();
        
        header('Location:' . DB_PATH);

        mysqli_close($conn);
    }
}

//Function to get folder data for the current user
function getFolderData($dirName)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("We've encountered and error, please try again later!",DASHBOARD_PATH);
    }
    
    $query = "SELECT dir_name, type, dir_size, date_added, date_modified FROM user_dir f INNER JOIN user_details d"
            . " ON f.user_details_id = d.user_details_id WHERE f.dir_name ='" . $dirName . "';";
    
    $result = mysqli_query($conn,$query);
    
    if($result === FALSE)
    {
        header('Location:' . DB_PATH);

        mysqli_close($conn);
    }
    
    $row = mysqli_fetch_assoc($result);
    
    if($row == 'NULL' || $row == '')
    {
        return 'NULL';
    }
    else
    {
        $DirData = array($row['dir_name'], $row['type'], $row['dir_size'], $row['date_added'], $row['date_modified']);

        mysqli_close($conn);

        return $DirData;
    }
}

//Function to save an uploaded file to the db - Includes the blob data type
function renameFolder($newName, $currentName)
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
   
    $query = "UPDATE user_dir f SET dir_name=?, date_modified=? WHERE dir_name=? AND f.user_details_id="
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
        $statement->bind_param("ssss", $newName, $date_modified, $currentName, $email);
        $statement->execute();
        mysqli_close($conn);
        header('Location:' . DASHBOARD_PATH);
    }
}