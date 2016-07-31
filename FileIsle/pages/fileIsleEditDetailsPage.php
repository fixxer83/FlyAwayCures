<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php session_start(); 
include_once '../php/validation/validateRegistrationClass.php';?>

<html>
    <head>
        <title>FileIsle-EditDetails</title>
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

            <form id="main_form" name="main_form" method="post" onsubmit="return(validateRegistration(this));" action="../php/validation/validateEditDetailsClass.php?parameter=edit_details">
            <!--Form Elements-->
            <h1 id="edit_details">Edit Your Details</h1></br>
            <label for="f_name">First Name:</label>
            <input type="text" id="f_name" name="f_name" value="<?php if(isset($_SESSION['f_name'])){echo $_SESSION['f_name'];} ?>"/></br>
            <label for="l_name">Last Name:</label>
            <input type="text" id="l_name" name="l_name" value="<?php if(isset($_SESSION['l_name'])){echo $_SESSION['l_name'];} ?>"/></br>
            <label for="email_add">Email:</label>
            <input type="text" id="email_add" name="email_add" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?>"/></br>
            <label for="security_quest" id="sec_quest_lbl">Security Question:</label>
           
            <select id="security_quest" name="security_quest" onChange="validateSelectOpt(this);">
                <?php
                    if(isset($_SESSION['sec_quest'])){
                        $selection = '';

                        if($_SESSION['sec_quest'] == 'mother_maiden')
                        {
                            $selection = 'Mother\'s Maiden Name';
                        }
                        else if($_SESSION['sec_quest'] == 'fav_fb_team')
                        {
                           $selection = 'Favourite Football Team';
                        }
                        else if($_SESSION['sec_quest'] == 'fav_city')
                        {
                          $selection = 'Favourite City';
                        } 

                        echo("<option value='set_val' selected='selected'>Set: " . $selection . "</option></br>");  
                    }
                ?>  
                <option value="mother_maiden">Mother's Maiden Name</option>
                <option value="fav_fb_team">Favourite Football Team</option>
                <option value="fav_city">Favourite City</option>
                <option value="pet_name">Pet's Name</option>
            </select></br>
            <label for="security_ans_input" id="sec_ans_lbl">Security Answer:</label>
            <input type="text" id="security_ans_input" name="security_ans_input" value="<?php if(isset($_SESSION['sec_answer'])){echo $_SESSION['sec_answer'];} ?>"/></br></br>
            <h2 id="enter_pwd">Kindly enter your password to update details</h2></br>
            <label for="password_val" id="pwd_lbl">Password:</label>
            <input type="password" id="password_val" name="password_val"/></br></br>
            
            <!--Error Div-->
            <?php
                if(isset($_SESSION['error_msg'])){
                    echo("<div id='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                }
                else
                {
                    "<div id='error_msg'> </div></br>";
                }
            ?>      
            <!--Buttons Div-->
            <div id="button_div">
                <button type="submit" class="type_btn">Update</button>
            </div>
            
            <!--Links Div-->
            <div id="link_div">
                <a href="fileIsleDashboard.php">Back</a>
            </div>
        
        </form>

    </div>

    </body>
</html>
