<?php

include_once 'connection.php';
include_once '../sessions/userSession.php';

//Constants
const SIGN_UP_PATH2 = '../../Pages/signUp.php';


// Function to add a new user to the db
function SignUpUser($data) 
{
	ob_Start();
	
    session_start();
    
    $params = getConnectionParameters();
    
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        // Create new connection
        $conn = new mysqli($params[0],$params[1],$params[2],$params[3],$params[4]);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",  SIGN_UP_PATH2);
        exit();
    }
    // Parameters
    $user_email_id = uniqid();
    $user_details_id = uniqid();
    $full_name = $data[0];
    $password = md5($data[2]);
    $registration_date = date("Y-m-d h:i:sa");
    $user_email_address = $data[1];
    
    $query1 = "INSERT INTO user_email (user_email_id, user_email_address) VALUES(?,?);";
    $query2 = "INSERT INTO user_details (user_details_id, full_name, password_val, registration_date, user_email_id) VALUES(?, ?, ?, ?, ?);";
    
    if(!$statement1=$conn->prepare($query1) || !$statement2=$conn->prepare($query2))
    {
        setErrorMsg('SQL Error: ' . $conn->error, SIGN_UP_PATH2);
        $statement1->close();
        $statement2->close();
        $conn->close();
        exit();
    }
    else
    {
        // Prepare both statements
        $statement1 = $conn->prepare($query1);
        $statement2 = $conn->prepare($query2);
        
        // Bind both query's parameters
        $statement1->bind_param("ss", $user_email_id, $user_email_address);
        $statement2->bind_param("sssss", $user_details_id, $full_name, $password, $registration_date, $user_email_id);
        
        // Execute both queries
        $statement1->execute();
        $statement2->execute();
        
        //Initiating user sessions
        setEmailSession($user_email_address);
        
        header('Location: ../../Pages/mainPage.php');
        $statement1->close();
        $statement2->close();
        $conn->close();
    }
    exit();
}