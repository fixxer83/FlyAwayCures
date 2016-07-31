<?php

include_once 'connection.php';

//Constants
const SIGN_UP_PATH = '../../Pages/signUp.php';

function getUserData($email)
{  
    $params = getConnectionParameters();
    try
    {
        // Create new connection
        $conn = new mysqli($params[0],$params[1],$params[2],$params[3],$params[4]);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",  SIGN_UP_PATH);
        exit();
    }
    // Check connection
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    } 
    $query = "SELECT user_email_address, d.password_val FROM user_email e "
            . "INNER JOIN user_details d ON e.user_email_id = d.user_email_id "
            . "WHERE user_email_address='" . $email . "';" ;
    
    $result = mysqli_query($conn, $query);
    $data = null;
    
    if(!$result)
    {
        mysqli_close($conn);
        return false;
    }
    else
    {
        while($row = $result->fetch_assoc())
        {
            $data = array($row['user_email_address'], $row['password_val']);
        }
    
        mysqli_close($conn);
        return $data;    
    }
}