<!DOCTYPE html>
<?php
    session_start(); 
    include_once '../PHP/sessions/r2rSessions.php';
    include_once '../PHP/Helper/planTripPt1Helper.php';
?>
<html>
    <head title="MainPage">
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="../CSS/mainPageStyling.css"/>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>   
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=false"></script>
    
    <script src="../../requiredFiles/jquery-1.11.2.js"></script>
    <script src="../JS/googleMapFunctions.js"></script>
    <script src="../JS/selectOptions.js"></script>
    <script src="../JS/tabHelper.js"></script>
    <script src="../JS/menuFunctions.js"></script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
</head>
<body>
    <div id="container001" class="container_div">          
        
        <!--Menu Bar-->
        <div id="nav_bar_cont" class="nav_bar_container">
            <nav class="nav_class">
                <div class="h_div">
                    <img id="logo" src="../images/logo.png" style="width: 50px; height: 50px;" alt="logo">
                    <h1>Fly Away Cures</h1>
                </div>
                <ul id="menu_bar">
                    <li id="plan"><a href="../PHP/Helper/planTripPt1Helper.php?restart" id="plan_trip">Plan Trip</a></li>
                    
                    <li><a href="../Pages/savedTripsPage.php">Saved Trips</a></li>
                    
                    <li><a href="../Pages/accountInfoPage.php">Account Info</a></li>
                    
                    <li id="plan"><a href="../PHP/Helper/signOutHelper.php?signOut" id="plan_trip">Sign Out</a></li>
                    <li id="username" class="user_name"><a class="user_name" href="#"><?php echo $_SESSION['email_add']; ?></a></li>
                </ul>
            </nav>
        </div>
            
            <div class="main_div">
                 
                <div class="detailsDiv">
                    <!--Tab Options-->
                    <ul class="steps">
                        <?php
                            // Showing tabs according to the sessions set
                            showTabsAccordingToSession();
                        ?> 
                    </ul>
                    
                    <script>                                            
                        $(window).on('load', function()
                        {
                            $(document).ready(function()
                            {
                                var tab_id = $('.step_link').attr('data-tab');
                                // Adjust content div's class name
                                $('.step_content').removeClass('current');
                                // Add the current li's equivalent content div
                                $("#"+tab_id).addClass('current');
                            });
                        });
                    </script>
                    

                    <!--Plan Your Trip 1 of 3-->
                    <div id="step_1" class="step_content current">
                        <div id="plan001" class="plan_trip_div">
                            <h2>Plan Your Trip - 1 of 3</h2>                            
                            
                            <!-- Error Message Div -->
                            <?php
                                if(isset($_SESSION['error_msg'])){
                                    echo("<div id='error_msg001' class='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                                }
                                else
                                {
                                    "<div id='error_msg001' class='error_msg'> </div></br>";
                                }
                            ?>
                                    
                            <form id="plan001_form" method="post" action="../PHP/validation/validateStepOne.php">
                                <div id="trip_info_div1">
                                    <input type="text" class="inputs" id="depart_country" placeholder="Departing Country" name="departing_country" required/>
                                    <input type="text" class="inputs" id="destination_country" placeholder="Destination City" name="destination_city" required/>
                                    <input placeholder="Outward Date" type="text" onfocus="(this.type='date')" class="inputs" id="outward_date" name="out_date" required/>
                                    <input placeholder="Return Date" type="text" onfocus="(this.type='date')" class="inputs" id="return_date" name="return_date" required/>
                                </div>

                                <div id="btn_div">
                                    <input type="submit" id="next_btn" class="button_style" value="Next"/>
                                </div>
                            </form>
                        </div>
                        
                        <div id="map1" class="map_canvas">
                            <script>
                                var map1 = google.maps.event.addDomListener(window, 'load', initializeMap(0,0,12,'map1'));
                                
                                // Refreshing map on page enter
                                $("#step_1").one("mouseenter", function(){ initializeMap(0,0,12,'map1');});
                            </script>
                        </div>
                    </div>                 
                    
                    <!--Plan Your Trip 2 of 3-->
                    <div id="step_2" class="step_content">
                        <div id="plan002" class="plan_trip_div">
                            <h2>Plan Your Trip - 2 of 3</h2>
                            <!-- Error Message Div -->
                            <?php
                                if(isset($_SESSION['error_msg']))
                                {
                                    echo("<div id='error_msg002' class='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                                }
                                else
                                {
                                    "<div id='error_msg'> </div></br>";
                                }
                            ?>
                            <div class="tbl_div">
                                <form id="flight_table" method="get" action="../PHP/Helper/planTripPt3Helper.php">
                                    <table class="flight_tbl" id="flight_tbl" cellpadding="13" style="width: auto; margin: auto;">
                                       <tbody>
                                            <?php
                                                include '../PHP/Helper/planTripPt2Helper.php';
                                                include_once '../PHP/jsonParser/r2rParseSearch.php';

                                                $data = array();
                                                $flight_info = array();

                                                if(isset($_SESSION['outward']) && isset($_SESSION['destination']))
                                                {
                                                    array_push($data, $_SESSION['outward']);
                                                    array_push($data, $_SESSION['destination']);
                                                    array_push($data, $_SESSION['out_date']);
                                                    array_push($data, $_SESSION['in_date']);

                                                    $flight_info = getItineraries($data);

                                                    foreach($flight_info as $flight)
                                                    {
                                                        echo $flight;
                                                    }
                                                }
                                            ?>
                                       </tbody>
                                    </table>
                                    
                                    <!--Hidden label to hold selected flight value-->
                                    <input type="hidden" id="hidden_lbl" name="hidden_lbl" value="">
                                </form>
                                
                                <script>
                                    // Getting checked row
                                    $("input[type=checkbox]").on("click", function()
                                    {
                                        getRowAndShowMap();
                                        
                                        var row = getParsedRowData();
                                        
                                        //hideButton(row);
                                        
                                        setHiddenLabel(row);
                                    });

                                    // Limiting checkbox selection and buttons shown to 1
                                    singleSelection();                                
                                </script>
                                
                            </div>
                        </div>
                            <div id="map2" class="map_canvas">
                                <script>                                    
                                                                      
                                    // Getting coordinates
                                    var getCoord = function()
                                    {
                                        var lat = '<?php echo $_SESSION['latitude']?>';
                                        var lng = '<?php echo $_SESSION['longitude']?>';
                                        
                                        outputMap(lat,lng);
                                    };
                                    
                                    // Outputting marked map
                                    var outputMap = function(lat,lng)
                                    {
                                        // Initializing marked map with the set coordinates
                                        google.maps.event.addDomListener(window, 'load', initializeMap(lat,lng,12,'map2'));
                                    };
                                    
                                    // Refreshing map on page enter
                                   $("#step_2").one("mouseenter", function(){ getCoord();});
                                </script>
                            </div>
                        </div>
                        <!--Plan Your Trip 3 of 3-->
                        <div id="step_3" class="step_content">
                            <div id="plan003" class="plan_trip_div">
                                <h2>Plan Your Trip - 3 of 3</h2> 
                                <div class="tbl_div">
                                    <form id="plan003_form" method="post" action="../PHP/validation/validateSelectedFlight.php">
                                        <table id="sel_flight_tbl" class="flight_tbl" cellpadding="13" style="width: auto; margin: 0 auto;">
                                                <?php 
                                                    if(isset($_SESSION['selected_flight']))
                                                    {
                                                        echo $_SESSION['selected_flight'];
                                                    }
                                                ?>
                                        </table>
                                    </form>
                                </div>
                                <div id='final_settings'>
                                    <label for="no_of_ppl">Number of Persons: </label>
                                    <input type="text" class="sml_input" id="no_of_ppl" name="no_ppl"><br>
                                    <label class='imp_label'>Total Price: €</label>
                                    <input type="hidden" id="hid_ttl_price" name="total_price" value="">
                                    <label id="price_lbl" class='imp_label'></label><br>
                                    
                                    <a href="../PHP/Helper/planTripPt1Helper.php?restart" id="plan_trip"><input type='submit' class="button_style" value="Restart"></a>
                                    <input type='submit' class="button_style" id="checkout_btn" value="Checkout">
                                    
                                    <script>
                                        $("#step3").on("click", function()
                                        {
                                            $('#price_lbl').text(getSelectedFlightPrice());
                                        });
                                        
                                        $('#no_of_ppl').keyup(function(){
                                            
                                            computePrice();
                                        });
                                        
                                        $('#checkout_btn').on("click", function()
                                        {
                                            var data = getSelectedFlightData();
                                                                                       
                                            window.location.href = "../PHP/validation/validateSelectedFlight.php?coordinates=" + data[0] + 
                                                    "&header=" + data[1] + "&row=" + data[2] + "&numOfPpl=" + data[3] + "&totalPrice=" + data[4];
                                            
                                        });
                                    
                                    </script>
                                    
                                    
                                </div>
                                
                                <div id='error_msg002' class='error_msg2' style='display: inline-block; text-align:center'></div>
                                
                                <!-- Error Message Div -->
                                <?php
                                    if(isset($_SESSION['error_msg']))
                                    {
                                        echo("<div id='error_msg003' class='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                                    }
                                    else
                                    {
                                        "<div id='error_msg'> </div></br>";
                                    }
                                ?>
                                
                            </div>
                        </div> 
                    </div> 
                </div>
            </div>
    </body>
</html>