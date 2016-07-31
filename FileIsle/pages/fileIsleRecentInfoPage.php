<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
session_start();

function callFunction($param)
{
  getParamater($param);
}

?>

<html>
    <head>
        <title>FileIsle-Recent Info</title>
        <!--Css Page-->
        <link rel="stylesheet" href="..//css/fileIsleDash.css"/>
        <script type="text/javascript" src="../js/popUpWindowClass.js"></script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <div id="main_audit_container_div">
            <form id="main_audit_form" name="main_form" method="post" action="../php/dataUtilsauditDataClass.php">

                <!--Files / Folders Table-->   
                <table id="audit_table" style="width:97%">

                    <thead>
                        <th>Activity Date</th>
                        <th>Activity Type</th>
                        <th>IP Address (From)</th>
                        <th>Browser Info</th>
                    </thead>

                    <tbody>
                        <?php 
                            include '../php/dataUtils/auditDataClass.php';

                            getUserAuditEntries();
                        ?>
                    </tbody>
                </table>                               
            </form>
        </div>
        <div id="btn_div">
            <a href="fileIsleDashboard.php"><input type="button" class="hidden_options" id="back_btn" name="back" value="Back"></a>
        </div>
    </body>    
</html>
