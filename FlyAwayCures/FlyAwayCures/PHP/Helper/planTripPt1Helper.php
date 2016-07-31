<?php

include_once($_SERVER["DOCUMENT_ROOT"]. "/CMT3313CW2/FlyAwayCures/PHP/jsonParser/r2rParseGeolocation.php");

// Showng the tabs according to the session enabled / user step progress
function showTabsAccordingToSession()
{
    if(!isset($_SESSION['outward']) && !isset($_SESSION['destination']))
    {
        echo"<li class='step_link current' data-tab='step_1' id='step1'>Step 1 of 3</li>";        
    }
    else if(isset($_SESSION['outward']) && isset($_SESSION['destination']) && !isset($_SESSION['selected_flight']))
    {
        echo"<li class='step_link current' data-tab='step_2' id='step2'>Step 2 of 3</li>";
    }
    else if(isset($_SESSION['selected_flight']))
    {
        echo "<li class='step_link current' data-tab='step_3' id='step3'>Step 3 of 3</li>";
    }
}

// Restart Planning Trip from step 1
function restartPlanning()
{
    session_start();
    
    header('location: ../../Pages/mainPage.php');
    
    if(isset($_SESSION['outward']))
    {
        unset($_SESSION['outward']);
    }
    
    if(isset($_SESSION['destination']))
    {
        unset($_SESSION['destination']);
    }
    
    if(isset($_SESSION['from_country']))
    {
        unset($_SESSION['from_country']);
    }
  
    if(isset($_SESSION['to_country']))
    {
        unset($_SESSION['to_country']);
    }
    
    if(isset($_SESSION['selected_flight']))
    {
        unset($_SESSION['selected_flight']);
    }
    
    if(isset($_SESSION['flight_price']))
    {
        unset($_SESSION['flight_price']);
    }
}

if (isset($_GET['restart']))
{
    restartPlanning();
}
