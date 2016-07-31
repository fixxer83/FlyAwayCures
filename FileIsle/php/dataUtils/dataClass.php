<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include($_SERVER["DOCUMENT_ROOT"]. "\\CMT3313CW1\FileIsle\php\user\userSessionClass.php");
//include("../sessions/userSessionClass.php");

//Constants
const HOST = 'localhost';
const PORT = '3306';
const USER = 'root';
const PASSWORD = '';
const DBNAME = 'file_isle';

//Function to verify whether data inputted in the login exists in our database
function logInUser($data)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
         $conn = new mysqli(HOST, USER, PASSWORD, DBNAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Registration error, please try again later!",
                "../../pages/fileIsleRegistrationPage.php");
         exit();
    }
    $query = "SELECT username_val, d.email FROM file_isle.user_credentials c INNER JOIN file_isle.user_details d "
            . "ON c.user_details_id = d.user_details_id WHERE c.username_val=? AND c.password_val=?;";

    if(!$select = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, "../../pages/fileIsleLoginPage.php");
    }
    else
    {
        $user_input = $data[0];
        //Encrypting password
        $encrypted_pwd = md5($data[1]);
        
        $username_val = '';
        $email = '';
        
        $select->bind_param("ss", $user_input, $encrypted_pwd);
        $select->execute();
        $select->bind_result($username_val, $email);
        //Fetching result
        $select->fetch();
        $select->close();
        
        if($username_val != '' && $email != '')
        {
            $userData = array($username_val, $email);
            $conn->close();
            return $userData;
        }
        else
        {
            $conn->close();
            return "User not found!";              
        }
    }
}

//Function to register a new user
function registerUser($data)
{
   mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
         $conn = new mysqli(HOST, USER, PASSWORD, DBNAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Registration error, please try again later!",
                "../../pages/fileIsleRegistrationPage.php");
         exit();
    }
    
    //Parameters
    $user_details_id = uniqid();
    $f_name = $data[0];
    $l_name = $data[1];
    $email = $data[2];
    $sec_quest = $data[3];
    $sec_answer = $data[4];
    $user_id = uniqid();
    $username_val = $data[5];
    //Encrypting password
    $password_val = md5($data[6]);
    $creaion_timestamp = date("Y-m-d h:i:sa");
    $status = 1;

    $query1 = "INSERT INTO user_details (user_details_id, f_name, l_name, email, sec_quest, sec_answer) VALUES(?,?,?,?,?,?);";
    
    $query2 = "INSERT INTO user_credentials (user_id, username_val, password_val, " 
        . "creation_timestamp, status, user_details_id) VALUES(?,?,?,?,?,?);";
        
    //Executing Query 1
    if(!$statement1=$conn->prepare($query1) || !$statement2=$conn->prepare($query2))
    {
        setErrorMsg('SQL Error: ' . $conn->error, "../../pages/fileIsleRegistrationPage.php");
        $statement1->close();
        $conn->close();
    }
    else
    {
        //Prepare both statements
        $statement1 = $conn->prepare($query1);
        $statement2 = $conn->prepare($query2);
        
        //Bind both querie's parameters
        $statement1->bind_param("ssssss", $user_details_id, $f_name, $l_name, 
                $email, $sec_quest, $sec_answer);
       
        $statement2->bind_param("ssssis", $user_id, $username_val, 
                $password_val, $creaion_timestamp, $status, $user_details_id);
        
        //Execute both queries
        $statement1->execute();
        $statement2->execute();
        
        header('Location: ../../pages/fileIsleDashboard.php');
        initiateUserSessionAndSetVar($username_val, $email);
        $statement1->close();
        $statement2->close();
        $conn->close();
    }
    exit();
}

function isUserAlreadyRegistered($data)
{
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
         $conn = new mysqli(HOST, USER, PASSWORD, DBNAME, PORT);
    }
    catch (Exception $e )
    {
        setErrorMsg("Registration error, please try again later!",
                "../../pages/fileIsleRegistrationPage.php");
         exit();
    }

    $check1="SELECT * FROM user_details where email ='".$data[2]."';";
    
    $res_set1 = mysqli_query($conn,$check1);
    $result1 = mysqli_fetch_array($res_set1, MYSQLI_NUM);
    
    $check2="SELECT * FROM user_credentials where username_val='".$data[5]."';";

    $res_set2 = mysqli_query($conn,$check2);
    $result2 = mysqli_fetch_array($res_set2, MYSQLI_NUM);
    
    if($result1[0] > 1 || $result2[0] > 1) 
    {
        mysqli_close($conn);
        return "User Already in Exists";
    }
    else
    {
        return false;
    }
}