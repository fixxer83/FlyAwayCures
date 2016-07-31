<!DOCTYPE html>
<html>
    
    <!--Initiating Session-->
    <?php session_start();?>
       
    <head>
        <title>FlyAwayCures</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="../CSS/index.css" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?effect=outline;">
        <script type="text/javascript" src="../JS/validateSignUp.js"></script>
    </head>
    <body>
        <div class="wrapper_div">
            
            <div class="container_div">
               
                <div id="sign_up_div" class="sign_div">
                    
                    <div class="h_div">
                        <img id="logo" src="../images/logo.png" alt="logo" style="width: 100px; height: 100px;">
                        <h1>Fly Away Cures</h1>
                        <h2>Sign-Up</h2>  
                    </div>
                    

                    <!--onsubmit="return(validateSignUp(this));"-->

                    <div id="detailsDiv">
                        <form id="sign_up_form" method="post" action="../PHP/validation/validateSignUp.php">
                            <input type="text" id="full_name_txt" class="inputs" placeholder="full name" name="fullname" required/></br>
                            <input type="email" id="email_txt" class="inputs" placeholder="email" name="email" required/></br>
                            <input type="password" id="pwd_txt" class="inputs" placeholder="password" name="password" required/></br>
                            <input type="password" id="conf_pwd_txt" class="inputs" placeholder="confirm password" name="confirm_password" required/></br>
                            <input type="submit" id="submit_btn" class="button_style" value="Submit"/></br>
                        </form>

                        <!-- Error Message Div -->
                    <?php
                        if(isset($_SESSION['error_msg'])){
                            echo("<div id='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                        }
                        else
                        {
                            "<div id='error_msg'> </div></br>";
                        }
                    ?>  

                        <a href="signIn.php">Sign In</a>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>