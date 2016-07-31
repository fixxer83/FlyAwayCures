<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '/../validation/errorMessagingClass.php';

//Constants
if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');


//Function to favourites table data
function getFavouritesData($email)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $query = "Select entry_name"
            . " From file_isle.user_favourites f INNER JOIN file_Isle.user_credentials c"
            . " ON f.user_details_id = c.user_details_id WHERE email =?;";

    if(!$select = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, DB_PATH);
    }
    else
    {
        $select->bind_param("s",$email );

        $select->execute();
        
        //Variables
        $entry_name = '';

        $select->bind_result($entry_name);

        if($select->fetch())
        {
            $select->close();
            $user_details = array($entry_name);
            $select->close();
            $conn->close();
            return $user_details;
        }
        else
        {
            setErrorMsg("No files/folders are set as favourites!", DB_PATH);              
        }
    }
}

function writeItemToFavourites($data)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }   
    //Parameter
    $user_fav_id = uniqid();
    $entry_name = $data[0];
    $type = $data[1];
    $date_added = date("Y-m-d h:i:sa");
    
    $query="INSERT INTO user_favourites (user_fav_id, entry_name, type, date_added_to_fav, user_details_id)"
            . " VALUES(?, ?, ?, ?,(SELECT user_details_id From user_details Where email=?));";

    if(!$statement = $conn->prepare($query))
    {
        mysqli_close($conn);
        setErrorMsg('SQL Error: ' . $conn->error, DB_PATH);
    }
    else
    {
        resetErrorMsg();
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        
        if(isset($_SESSION['email']))
        {
          $email = $_SESSION['email'];
          $statement->bind_param("sssss", $user_fav_id, $entry_name, $type, $date_added, $email);
        }
        $statement->execute();
        header('Location:' . DB_PATH);
        mysqli_close($conn);
    }
}

function deleteItemFromFavourites($entryName)
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $query="Delete FROM user_favourites WHERE user_details_id ="
            ."(SELECT user_details_id FROM user_details WHERE email=?)"
            ."AND entry_name =?;";

    if(!$statement = $conn->prepare($query))
    {
        mysqli_close($conn);
        setErrorMsg('SQL Error: ' . $conn->error, DB_PATH);
    }
    else
    {
        resetErrorMsg();
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        
        $email='';
        if(isset($_SESSION['email']))
        {
          $email = $_SESSION['email'];
        }
        
        $statement->bind_param("ss", $email, $entryName);
        $statement->execute();
        mysqli_close($conn);
        
        header('Location:' . DB_PATH);
    }
}

//This function will be used to populate the edit details form with the current user details
function isFavourite($input)
{    
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $email='';
    if(isset($_SESSION['email']))
    {
      $email = $_SESSION['email'];
    }
    
    $check="SELECT * FROM user_favourites f INNER JOIN user_details d ON "
            . "f.user_details_id = d.user_details_id "
            . "AND f.user_details_id = d.user_details_id WHERE f.entry_name='"
            . $input . "' AND d.email ='" . $email . "';";
    
    $res_set = mysqli_query($conn,$check) or die("SQL Error: ".mysqli_error($conn));
    
    $result = mysqli_fetch_array($res_set, MYSQLI_NUM);
    
    if($result != 0) 
    {
        mysqli_close($conn);
        return true;
    }
    else
    {
        mysqli_close($conn);
        return false;
    }
}

//Function to get user's favourite items
function getTableFavourites()
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Please try again later!",DASHBOARD_PATH);
    }
    
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM user_favourites f INNER JOIN user_details d ON "
            . "f.user_details_id = d.user_details_id "
            . "AND f.user_details_id = d.user_details_id WHERE d.email ='" . $email . "';";
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, DB_PATH);
    }
    //Initialising count
    $count = 0;
    
    while($row = mysqli_fetch_assoc($result)){

        if($row["type"] == 'dir')
        {
            $count ++;

            echo "<tr class='hover_enabled');'>"
            ."<td><a href='../php/helper/parentHelperClass.php?parent=" . $row["entry_name"] . "' class='media_a'>" . $row["entry_name"] . "</a></td>"
            ."</tr>"; 
        }
        else
        {
            $count ++;
            
            echo "<tr class='hover_enabled);'>"
                . "<td>" . $row["entry_name"] . "</td>"
                . "</tr>";  
        }
    }
}

//Function to save an uploaded file to the db - Includes the blob data type
function renameEntry($newName, $currentName)
{
    session_start();
    include_once 'parentDataClass.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $conn = new mysqli(HOST, USER, PASSWORD, NAME, PORT);
    }
    catch (Exception $e)
    {
        setErrorMsg("Error adding file, please try again later!",DASHBOARD_PATH);
    }
    
    //Parameters
    $email = '';
    $date_modified = date("Y-m-d h:i:sa");
    
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }
    
    $query = "UPDATE user_favourites f SET entry_name=? WHERE entry_name=? AND f.user_details_id="
            . "(Select user_details_id From user_details d WHERE d.user_details_id = f.user_details_id AND email=?);";
    
    if(!$statement = $conn->prepare($query))
    {
        setErrorMsg('SQL Error: ' . $conn->error, DASHBOARD_PATH);
        mysqli_close($conn);
    }
    else
    {
        //Set statement and bind parameters
        $statement = $conn->prepare($query);
        $statement->bind_param("sss", $newName, $currentName, $email);
        $statement->execute();
        mysqli_close($conn);
        header('Location:' . DASHBOARD_PATH);
    }
}
