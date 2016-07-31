<?php

include_once '/../validation/errorMessagingClass.php';

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Constants
if(!defined('HOST')) define('HOST', 'localhost');
if(!defined('PORT')) define('PORT', '3306');
if(!defined('USER')) define('USER', 'root');
if(!defined('PASSWORD')) define('PASSWORD', '');
if(!defined('NAME')) define('NAME', 'file_isle');
if(!defined('DASHBOARD_PATH')) define('DASHBOARD_PATH', '../../pages/fileIsleDashboard.php');

//Function to get user's folders
function getTableFolders($parentId, $count)
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
    
    if($parentId == 'NULL')
    {
        $sql = "Select dir_name, dir_size, type, date_added, date_modified From file_isle.user_dir f
         JOIN user_details d ON f.user_details_id = d.user_details_id
            WHERE d.email ='" . $email . "' AND f.parent_id IS NULL;";
    }
    else
    {
        $sql = "Select dir_name, dir_size, type, date_added, date_modified From file_isle.user_dir f
         JOIN user_details d ON f.user_details_id = d.user_details_id
            WHERE d.email ='" . $email . "' AND f.parent_id='" . $parentId . "' ;"; 
    }
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, PATH);
    }
        
   while($row = mysqli_fetch_assoc($result)){
      
       $dirName = $row["dir_name"];
       
        $count ++;
        echo "<tr class='hover_enabled'>"
            ."<td><input type='checkbox' name='checked[]' value='" .$row["dir_name"] . "' id='" .$row["dir_name"] . "' style='width: 100px; text-align: center'></td>"
            . "<td><a href='../php/helper/parentHelperClass.php?parent=" . $row["dir_name"] . "' class='media_a'>" . $row["dir_name"] . "</a></td>"
            . "<td>" . $row["type"] . "</td>"
            . "<td>" . $row["dir_size"] . "</td>"
            . "<td>" . $row["date_added"] . "</td>"
            . "<td>" . $row["date_modified"]. "</td>"
        . "</tr>";  
    }
    
    return $count;
}

//Function to get user's files
function getTableFiles($parentId)
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
    
    if($parentId == 'NULL')
    {
        $sql = "Select file_name, file_size, file_type, date_added, date_modified From file_isle.user_files f
             JOIN user_details d ON f.user_details_id = d.user_details_id
                WHERE d.email ='" . $email . "' AND f.parent_id IS NULL;";
    }
    else
    {
        $sql = "Select file_name, file_size, file_type, date_added, date_modified From file_isle.user_files f
             JOIN user_details d ON f.user_details_id = d.user_details_id
                WHERE d.email ='" . $email . "' AND f.parent_id='" . $parentId . "' ;";  
    }
    
    $result = mysqli_query($conn,$sql);
    
    if($result === FALSE)
    {
        setErrorMsg($sql, PATH);
    }
    
    //Initialising count
    $count = 0;
    
    while($row = mysqli_fetch_assoc($result)){

        $count ++;

        echo "<tr class='hover_enabled'>"
            ."<td><input type='checkbox' name='checked[]' value='" . $row["file_name"] . "' id='" 
                . $row["file_name"] . "' style='width: 100px; text-align: center'></td>"
            . "<td>" . $row["file_name"] . "</td>"
            . "<td>" . $row["file_type"] . "</td>"
            . "<td>" . $row["file_size"] . "</td>"
            . "<td>" . $row["date_added"] . "</td>"
            . "<td>" . $row["date_modified"]. "</td>"
            . "</tr>";  
    }
    
    return $count;
}