<!DOCTYPE html>
<html>
    
    <!--Initiating Session-->
    <?php session_start();?>
       
    <head>
        <title>Fly Away Cures - Account Info</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="../CSS/mainPageStyling.css"/>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?effect=outline;">
        <script type="text/javascript" src="../JS/validateSignUp.js"></script>
    </head>
    <body>
        <div id="container001" class="container_div"> 
        <div class="main_div">
            <div id="step_2" class="step_content current">
                <div id="plan002" class="plan_trip_div">
                    <h2>Account Info</h2>
                    <div class="tbl_div">
                        <form id="flight_table" method="get" action="">
                            <table class="flight_tbl" id="acc_info_table" cellpadding="10" style="width: auto; margin: auto;">
                               <tbody>
                                    <?php 
                                        include_once '../PHP/data/recentInfoData.php';

                                        getRecentInfoEntries();
                                    ?>
                               </tbody>
                            </table>
                        </form>
                     </div>
                    <a href="mainPage.php"><input type='button' class="button_style" value="Back"></a>
                </div>
            </div>
        </div>
            </div>
    </body>
</html>