<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include_once '../validation/errorMessagingClass.php';

//Constants
if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');

//Function to get user data
function getUserData($email)
{
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
    
    $query = "Select f_name, l_name, sec_quest, sec_answer, c.password_val"
            . " From file_isle.user_details d INNER JOIN file_Isle.user_credentials c"
            . " ON d.user_details_id = c.user_details_id WHERE email =?;";

    if(!$select = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
    }
    else
    {
        $select->bind_param("s",$email );

        $select->execute();
        
        //Variables
        $f_name = $l_name = $sec_quest = $sec_answer = $password_val = '';

        $select->bind_result($f_name, $l_name, $sec_quest,
                $sec_answer, $password_val);

        if($select->fetch())
        {
            $select->close();
            $user_details = array($f_name, $l_name, $sec_quest, $sec_answer, $password_val);
            $select->close();
            $conn->close();
            
            return $user_details;
        }
        else
        {
            setErrorMsg("User not found!", DASHBOARD_PATH);              
        }
    }
}

//Function to save an uploaded file to the db - Includes the blob data type
function writeFileToDb($parentId, $data)
{
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
    $user_files_id = uniqid();
    $file_name = $data[0];
    $file_size = $data[1];
    $file_type = $data[2];
    $content = $data[3];
    $email = $_SESSION['email'];
    $date_added = date("Y-m-d h:i:sa");
    $parent_path = $target_path = '';
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    //Checking whether the $parentId is null or not
    if($parentId == 'NULL')
    {
        $parentId = 'NULL';
        $target_path = "uploads" . stripslashes($file_name);        
    }else
    {
        $parent_path = getParentPathFromDb($parentId, $email);
        $target_path = "uploads" . stripslashes($parent_path) . "/" . basename($file_name);
    }
    
    $query="INSERT INTO user_files (user_files_id, file_name, file_size, file_type,"
            . "date_added, parent_id, user_details_id, file_data, path) VALUES(?, ?, ?, ?, ?, ?, "
            . "(SELECT user_details_id From user_details Where email=?), ?, ?);";

    if(!$statement = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
    }
    else
    {
        //Set statement and bind parameters
        $statement = $conn->prepare($query);        
        $statement->bind_param("sssssssss", $user_files_id, $file_name, $file_size, $file_type, 
                $date_added, $parentId, $email, stripslashes($content), $target_path);
        $statement->execute();
        mysqli_close($conn);
    }
    return $email;
}

//This function will be used to populate the edit details form with the current user details
function checkIfFileExists($data)
{
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
    
    $email = '';
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    
    /*$data contains other rows other than the file name the is array function
     * Will verify and get the right column if $data is array
     */
    if(is_array($data))
    {
        $check="SELECT * FROM user_files f, user_details d WHERE f.file_name ='" 
            . $data[0] ."' AND f.user_details_id = d.user_details_id AND d.email ='" . $email . "';";  
    }
    else
    {
     $check="SELECT * FROM user_files f, user_details d WHERE f.file_name ='" 
            . $data ."' AND f.user_details_id = d.user_details_id AND d.email ='" . $email . "';";     
    }
    
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

//This function may be used to delete a file according to the file and email specified
function deleteFile($fileName,$email)
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
    
    $query="Delete FROM user_files WHERE user_details_id ="
            ."(SELECT user_details_id FROM user_details WHERE email=?)"
            ."AND file_name =?;";

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
        
        $statement->bind_param("ss", $email, $fileName);
        
        $statement->execute();
        
        header('Location:' . DB_PATH);

        mysqli_close($conn);
    }
}

//Function to get file data for the current user
function getFileData($fileName)
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
    
    $query = "SELECT file_name, file_type, file_size, date_added, date_modified FROM user_files f INNER JOIN user_details d"
            . " ON f.user_details_id = d.user_details_id WHERE f.file_name ='" . $fileName . "';";
    
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
        $fileData = array($row['file_name'], $row['file_type'], $row['file_size'], $row['date_added'], $row['date_modified']);

        mysqli_close($conn);

        return $fileData;
    }
}


//Function to save an uploaded file to the db - Includes the blob data type
function renameFile($newName, $currentName)
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
   
    $query = "UPDATE user_files f SET file_name=?, date_modified=? WHERE file_name=? AND f.user_details_id="
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