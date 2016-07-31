<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('PATH')) define('PATH', '../../pages/fileIsleDashboard.php');

//include_once '../validation/errorMessagingClass.php';

//Function to get user data
//function getUserData($email)
//{
//    $conn = new mysqli('localhost', 'root', '', 'file_isle', '3306');
//    
//    if ($conn->connect_error > 0)
//    {
//        die("Connection failed: " . $conn->connect_error);
//    }
//    
//    $query = "Select f_name, l_name, sec_quest, sec_answer, c.password_val"
//            . " From file_isle.user_details d INNER JOIN file_Isle.user_credentials c"
//            . " ON d.user_details_id = c.user_details_id WHERE email =?;";
//
//    if(!$select = $conn->prepare($query))
//    {
//        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
//    }
//    else
//    {
//        $select->bind_param("s",$email );
//
//        $select->execute();
//        
//        //Variables
//        $f_name = $l_name = $sec_quest = $sec_answer = $password_val = '';
//
//        $select->bind_result($f_name, $l_name, $sec_quest,
//                $sec_answer, $password_val);
//
//        if($select->fetch())
//        {
//            $select->close();
//            $user_details = array($f_name, $l_name, $sec_quest, $sec_answer, $password_val);
//            $select->close();
//            $conn->close();
//            return $user_details;
//        }
//        else
//        {
//            setErrorMsg("User not found!", DASHBOARD_PATH);              
//        }
//    }
//}

//Function to add an audit entry to the audit table
function writeAuditEntry($data)
{
    session_start();

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
    $audit_id = uniqid();
    $ip_address = $data[0];
    $browser_info = $data[1];
    $activity_type = $data[2];
    $activity_date = date("Y-m-d h:i:sa");
    $email='';
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    
    $query="INSERT INTO audit_table (audit_id, activity_date, activity_type, ip_address, "
            . "browser_info, user_details_id) VALUES(?, ?, ?, ?, ?, "
            . "(SELECT user_details_id From user_details Where email=?));";

    if(!$statement = $conn->prepare($query))
    {
        mysqli_close($conn);
        setErrorMsg('SQL Error: ' . $conn->error, PATH);
    }
    else
    {
        resetErrorMsg();
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        
        $statement->bind_param("ssssss", $audit_id, $activity_date, $activity_type, $ip_address, $browser_info, $email);
        
        $statement->execute();
        
        header('Location:' . DB_PATH);

        mysqli_close($conn);
        //exit();
    }
}

//Function to get user's files
function getUserAuditEntries()
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Error adding file, please try again later!",DASHBOARD_PATH);
    }
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }

        $sql = "Select activity_date, activity_type, ip_address, browser_info From audit_table a
             JOIN user_details d ON a.user_details_id = d.user_details_id
                WHERE d.email ='" . $email . "' LIMIT 10;";
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, PATH);
    }
    
    while($row = mysqli_fetch_assoc($result)){

        echo "<tr class='hover_enabled'>"
            . "<td>" . $row["activity_date"] . "</td>"
            . "<td>" . $row["activity_type"] . "</td>"
            . "<td>" . $row["ip_address"] . "</td>"
            . "<td>" . $row["browser_info"] . "</td>"
            . "</tr>";  
    }
}
















//This function may be used to delete all the audit entries for a paticular user
function clearAuditEntries($fileName,$email)
{
    $conn = new mysqli('localhost', 'root', '', 'file_isle', '3306');
    
    if ($conn->connect_error) {
      return die("Connection failed: " . $conn->connect_error);
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


function readUploadedFile($data)
{
    
}



