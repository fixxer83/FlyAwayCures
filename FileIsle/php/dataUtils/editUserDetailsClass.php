<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//include_once '../../php/user/editUserDetailsSessionClass.php';
//include("../sessions/userSessionClass.php");

//Constants
const HOST = 'localhost';
const PORT = '3306';
const USER = 'root';
const PASSWORD = '';
const DBNAME = 'file_isle';

//Constants
const PATH = '../../pages/fileIsleEditDetailsPage.php';

//Function to get user data
function getUserData($email)
{
    $conn = new mysqli(HOST, USER, PASSWORD, DBNAME, PORT);
    
    if ($conn->connect_error > 0)
    {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $query = "Select f_name, l_name, sec_quest, sec_answer, c.password_val "
            . "From file_isle.user_details d INNER JOIN file_Isle.user_credentials c "
            . "ON d.user_details_id = c.user_details_id WHERE email =?;";

    if(!$select = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, "../../pages/fileIsleLoginPage.php");
    }
    else
    {
        $select->bind_param("s",$email );

        $select->execute();
        
        //Variables
        $f_name = $l_name = $sec_quest = $sec_answer = $password_val = '';

        $select->bind_result($f_name, $l_name, $sec_quest,
                $sec_answer, $password_val);

        if($select->fetch())
        {
            $select->close();
            $user_details = array($f_name, $l_name, $sec_quest, $sec_answer, $password_val);
            $select->close();
            $conn->close();
            return $user_details;
        }
        else
        {
            setErrorMsg("User not found!", "../../pages/fileIsleLoginPage.php");              
        }
    }
}

//Function to set user data
function setUserData($data, $orig_email)
{
    $conn = new mysqli(HOST, USER, PASSWORD, DBNAME, PORT);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $check="SELECT * FROM file_isle.user_details where email ='" . $data[2] . "';";
    $res_set = mysqli_query($conn,$check);
    $result = mysqli_fetch_array($res_set, MYSQLI_NUM);
    
    if($result[0] > 1) 
    {
        mysqli_close($con);
        return "Error";
    }
    else
    {
        //Set statement and bing parameters
        $statement = $conn->prepare("Update user_details Set f_name = ?, l_name=?, email=?, sec_quest=?, sec_answer=? Where email=?");
        $statement->bind_param($f_name, $l_name, $email, $sec_quest, $sec_answer, $original_email);

        //Parameters
        $f_name = $data[0];
        $l_name = $data[1];
        $email = $data[2];
        $sec_quest = $data[3];
        $sec_answer = $data[4];
        $original_email = $orig_email;
        
        mysqli_close($conn);
        header('Location: ../../pages/fileIsleDashboard.php');

        exit();

        mysqli_close($con);
        return "Error Registering User<br/>";
    }
}

//This function will be used to populate the edit details form with the current user details
function setUserDetailForm()
{
    setErrorMsg("HELLO", PATH);
    
    mail("andreamontesin@gmail.com", "test", "test");
    
    $email = $_SESSION['email'];
    $userData = array();
    $userData[1] = getUserData($email, $_SESSION['email']);
    
    $prod_name = htmlentities($product_data['f_name']);
    
    $fields = array('seasonid'    => $row['seasonid'],
                   'episodenum'  => $row['episodenum']
   );

    
    header("Location: ../../pages/fileIsleEditDetailsPage.php");
}



