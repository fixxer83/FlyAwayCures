<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'connection.php';
include_once($_SERVER["DOCUMENT_ROOT"]. "/CMT3313CW2/FlyAwayCures/PHP/validation/errorMessagingClass.php");

//Constants
const SIGN_IN_PATH = '../../Pages/signIn.php';

//Function to add an audit entry to the audit table
function writeRecentInfo($data)
{
    session_start();
    
    $params = getConnectionParameters();
    
    try
    {
        // Create new connection
        $conn = new mysqli($params[0],$params[1],$params[2],$params[3],$params[4]);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",  SIGN_IN_PATH);
        exit();
    }
    //Parameters
    $activity_id = uniqid();
    $ip_address = $data[0];
    $browser_name = $data[1];
    $activity_type = $data[2];
    $activity_date = date("Y-m-d h:i:sa");
    $email = $_SESSION['email_add'];
   
    $query="INSERT INTO recent_info_table (activity_id, activity_date, activity_type, ip_address, "
            . "browser_name, user_details_id) VALUES(?, ?, ?, ?, ?, "
            . "(SELECT d.user_details_id From user_details d, user_email e Where e.user_email_id = d.user_email_id AND e.user_email_address=?));";

    if(!$statement = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, SIGN_IN_PATH);
        mysqli_close($conn);
    }
    else
    {
        resetErrorMsg();
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        
        $statement->bind_param("ssssss", $activity_id, $activity_date, $activity_type, $ip_address, $browser_name, $email);
        
        $statement->execute();
        
        header('Location: ../../Pages/mainPage.php');

        mysqli_close($conn);
    }
}

//Function to get user's files
function getRecentInfoEntries()
{        
    $params = getConnectionParameters();
    
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        // Create new connection
        $conn = new mysqli($params[0],$params[1],$params[2],$params[3],$params[4]);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",  '../../Pages/mainPage.php');
        exit();
    }
    
    if(isset($_SESSION['email_add']))
    {
        $email = $_SESSION['email_add'];
    }

        $sql = "Select activity_date, activity_type, ip_address, browser_name FROM recent_info_table r, user_email e " 
                . "INNER JOIN user_details d ON e.user_email_id = d.user_email_id "
                . "WHERE e.user_email_address ='" . $email . "' LIMIT 10;";
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, '../../Pages/mainPage.php');
    }
    
    echo "<tr class='header_class' nowrap><td>Activity Date</td><td>Activity Type</td><td>IP Address</td><td>Browser Name</td></tr>";
    
    while($row = mysqli_fetch_assoc($result))
    {
        $activityDate =  date("d - M - Y , G:i",  strtotime($row["activity_date"]));

        echo "<tr class='flights_tbl'>"
            . "<td>" . $activityDate . "</td>"
            . "<td>" . $row["activity_type"] . "</td>"
            . "<td>" . $row["ip_address"] . "</td>"
            . "<td>" . $row["browser_name"] . "</td>"
            . "</tr>";  
    }
}