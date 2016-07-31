<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'connection.php';


// Function to get user's saved flights
function getUserSavedFlights()
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
        setErrorMsg("Please try again later!",  SIGN_UP_PATH);
        exit();
    }
    
    $email = null;
    
    if(isset($_SESSION['email_add']))
    {
        $email = $_SESSION['email_add'];
    }

        $sql = "SELECT * FROM flight_headers h, user_email e, selected_flight f INNER JOIN user_details d ON "
                . "f.user_details_id = d.user_details_id WHERE h.flight_headers_id = f.flight_headers_id AND "
                . "d.user_email_id = e.user_email_id AND e.user_email_address ='" . $email . "';" ;
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, PATH);
    }
    else if(mysqli_num_rows(mysqli_query($conn,$sql)) == 0)
    {
        echo "<label>You currently have no saved trips!</label></br>";
        
        return false;
    }
    
    while($row = mysqli_fetch_assoc($result))
    {
        $price = str_replace("EUR", "€", $row["price"]);
        $totalPrice = str_replace("EUR", "€", $row["total_price"]);
        $bookingDate = strtotime($row["booking_date"]);
        $additionalStops = null;
        
        if($row["additional_steps"]  == 0)
        {
            $additionalStops = '-';
        }

        echo "<tr class='flight_info' nowrap><th colspan='30'>" . $row['flight_header'] . "</th></tr>"
            . "<tr class='header_class' nowrap><td>Price</td><td>Flight Out</td><td>Airline</td><td>Time</td>"
            . "<td>Flight In</td><td>Airline</td><td>Time</td><td>Additional Stops</td><td>Total Price</td><td>"
            . "Booking Date</td><td>Persons</td></tr>"
            . "<tr class='flights_tbl'>"
            . "<td>" . $price . "</td>"
            . "<td>" . $row["flight_out"] . "</td>"
            . "<td>" . $row["airline_out"] . "</td>"
            . "<td>" . $row["time_out"] . "</td>"
            . "<td>" . $row["flight_in"] . "</td>"
            . "<td>" . $row["airline_in"] . "</td>"
            . "<td>" . $row["time_in"] . "</td>"
            . "<td>" . $additionalStops . "</td>"
            . "<td>" . $totalPrice . "</td>"
            . "<td>" . date('d-M-y', $bookingDate) . "</td>"
            . "<td>" . $row["num_of_ppl"] . "</td>"
            . "</tr>";  
    }
}
