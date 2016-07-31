<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php include '../php/validation/validateRegistrationClass.php';?>

<html>
    <head>
        <title>FileIsle-Login</title>
        <link rel="stylesheet" href="..//css/fileIsle.css"/>
        <script type ="text/javascript" src="..//js/validationFile.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <div id="logo_div">
            <img id="logo_img" src="..//logos/logo003.png" alt="fileIsleLogo" style="width:150;height:150px" border="0"/>
        </div>
        
        <div id="main_form_div">

            <form id="main_form" method="post" onsubmit="return(validateLogin(this));" action="..//php/validation/validateLoginClass.php?parameter=login">
                <!--Form Elements-->
                <h1 id="login_acc">Login</h1></br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"/></br>
                <label for="pwd_input">Password:</label>
                <input type="password" id="pwd_input" name="pwd_input"/></br></br>

                <!-- Error Div -->
                <?php
                    if(isset($_SESSION['error_msg'])){
                        echo("<div id='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                    }
                    else
                    {
                        "<div id='error_msg'> </div></br>";
                    }
                ?>  
                
                <!--Button Elements-->
                <div id="button_div">
                    <button type="submit" id="login_btn">Login</button>
                </div> 

                <!--Link Elements-->
                <div id="link_div">
                    <a href="fileIsleRegistrationPage.php">Register</a>
                    <a href="fileIsleForgotPasswordPage.html">Forgot Password</a>
                </div>

            </form>
        </div>
    </body>
</html>
