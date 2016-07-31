<?php

include_once($_SERVER["DOCUMENT_ROOT"]. "/CMT3313CW2/FlyAwayCures/PHP/r2rFunctions/r2rSearchAPI.php");

// Get flight info
function getItineraries($flight_data)
{
    // Preprocessing vars
    $response = getSearch($flight_data);
    $json = json_decode($response);
    $itineraries = array();
    $counter = 1;
    $country_from = $country_to = $airportGeoLocation = null;
    
    // Iterating through the JSON object
    foreach($json->routes as $myRoutes )
    {
        $route = $myRoutes->name;
        $airports = $json->airports;

        // Flights
        if(strpos($route,"Fly") !== false)
        {
            array_push($itineraries,"<tr class='flight_info' nowrap><th colspan='30'>");
        
            array_push($itineraries, " Distance: " . $myRoutes->distance);

            foreach($myRoutes->segments as $mySegments)
            {
                if($mySegments->kind == "flight")
                {
                    $country_from = $flight_data[0] . " (" .$mySegments->sCode . ")";
                    $country_to = $flight_data[1] . " (" .$mySegments->tCode . ")";
                    
                    // Converting date format
                    $outwardDate = date("d-m-y", strtotime($flight_data[2]));
                    $inwardDate = date("d-m-y", strtotime($flight_data[3]));
                    
                    // Airports
                    foreach($airports as $myAirports)
                    {                          
                        if($myAirports->code == $mySegments->tCode)
                        {
                            $airportGeoLocation = $myAirports->pos;
                        }
                    }
                    
                    // Conducting operations on the $airportGeoLocation for better parsing
                    $geoLocation = str_replace(",", "|", $airportGeoLocation);
                    // Table headers
                    array_push($itineraries, " Outward Flight Date: " . $outwardDate . " Inward Flight Date: " . $inwardDate . " From: " .$country_from . " ");
                    array_push($itineraries, "To: " . $country_to . "</tr>");

                    array_push($itineraries,"<tr class='header_class' nowrap><th><td> </td><td>Price</td><td>Flight Out</td><td>Airline</td><td>Time</td>"
                            . "<td>Flight In</td><td>Airline</td><td>Time</td><td colspan='3'>Additional Stops</td></th></tr>");
                    
                    foreach($mySegments->itineraries as $myItineraries)
                    {
                        // More flight information
                        foreach($myItineraries->legs as $myLegs)
                        {
                            $counter++;
                            
                            array_push($itineraries,"<tr class='flights_tbl' id='" . $outwardDate . "_" . $inwardDate . "_"
                                . $country_from . '_' .  $country_to . '_' . $geoLocation . "_" . $counter . "'>"
                                . "<td><input type='submit' class='hidden_row_btn' id='hid_btn" . $counter . "' value='Select'></td>"
                                . "<td><input type='checkbox' name='checked[]' id='chk_$counter' value='" . $counter . "' style='width: 50px;'></td>");
                            
                            array_push($itineraries, "<td>" . "€ " . $myLegs->indicativePrice->price . "</td>");

                            foreach($myLegs->hops as $myHops)
                            {                                
                                array_push($itineraries, "<td>" . $myHops->flight. "</td>");
                                array_push($itineraries, "<td>" . $myHops->airline . "</td>");
                                array_push($itineraries, "<td>" . $myHops->sTime . " - " . $myHops->tTime . "</td>");
                            }
                            
                            array_push($itineraries, "</tr>");
                        }
                    }
                }
                $counter++;
            }
        }
    }
    
    return $itineraries;
}



