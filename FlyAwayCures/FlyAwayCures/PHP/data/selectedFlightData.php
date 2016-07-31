<?php

include_once 'connection.php';
include_once '../sessions/userSession.php';

//Constants
const SIGN_UP_PATH = '../../Pages/signUp.php';


// Function to add a new user to the db
function addSelectedFlight($data) 
{
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
        setErrorMsg("Please try again later!",  SIGN_UP_PATH);
        exit();
    }
    // Parameters
    /*flight_headers*/
    $flight_headers_id = uniqid();
    $coordinates = $data[0];
    $flight_header = $data[1];
    
    /*selected_flight*/
    $selected_flight_id = uniqid();
    $num_of_ppl = $data[2];
    $total_price = $data[3];
    
    /*Parsing data*/
    $selected_flight_data = explode('|', $data[4]);
    
    $fetchedData = prepareParams($selected_flight_data);
    
    $price = $fetchedData[0];
    $flight_out = $fetchedData[1];
    $airline_out = $fetchedData[2];
    $time_out = $fetchedData[3];
    $flight_in = $fetchedData[4];
    $airline_in = $fetchedData[5];
    $time_in = $fetchedData[6];
    $add_steps = $fetchedData[7];

    $email = $_SESSION['email_add'];
    
    $booking_date = date("Y-m-d h:i:sa");
    $user_email_address = $data[1];
    
    $query1 = "INSERT INTO flight_headers(flight_headers_id, flight_header, airport_coordinates) VALUES(?,?,?);";
    $query2 = "INSERT INTO selected_flight(selected_flight_id, price, flight_out, airline_out, time_out, "
            . "flight_in, airline_in, time_in, num_of_ppl, total_price, additional_steps, booking_date, flight_headers_id, user_details_id)"
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (SELECT user_details_id From user_details d INNER JOIN user_email e ON "
            . "d.user_email_id = e.user_email_id Where user_email_address=?));";
    
    if(!$statement1=$conn->prepare($query1) || !$statement2=$conn->prepare($query2))
    {
        setErrorMsg('SQL Error: ' . $conn->error, SIGN_UP_PATH);
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
        $statement1->bind_param("sss", $flight_headers_id, $flight_header, $coordinates);
        $statement2->bind_param("ssssssssssssss", $selected_flight_id, $price, $flight_out, $airline_out, $time_out, 
                $flight_in, $airline_in, $time_in, $num_of_ppl, $total_price, $add_steps, $booking_date, $flight_headers_id, $email);
        
        // Execute both queries
        $statement1->execute();
        $statement2->execute();
        
        header('Location: ../../Pages/mainPage.php');
        $statement1->close();
        $statement2->close();
        $conn->close();
    }
}


function prepareParams($data)
{    
    $fetchedData = array();
    
    foreach($data as $cell)
    {
        array_push($fetchedData, $cell);
    }
    
    for($i=count($fetchedData); $i==7; $i++)
    {
       array_push($fetchedData, '0');
    }
    
    return $fetchedData;
}