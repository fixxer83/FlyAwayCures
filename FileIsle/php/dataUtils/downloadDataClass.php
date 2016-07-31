<?php

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


function downloadCheckedItem($fileName)
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
    
    $email = '';
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
    }

    //If current $item is a dir (not a file)
    if(checkIfFileExists($fileName) == false)
    {
        exit();
    }
    else
    {
        $query = "SELECT file_name, file_type, file_size, file_data FROM user_files f, user_details d WHERE f.file_name ='"
                . $fileName ."' AND f.user_details_id = d.user_details_id AND d.email ='" . $email . "';";

        $result = mysqli_query($conn,$query) or die('Error, query failed');
        
        $row = mysqli_fetch_assoc($result);
        
        $name = $row['file_name'];
        $type = $row['file_type'];
        $size = $row['file_size'];
        $file = $row['file_data'];
                
        mysqli_close($conn);
        
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $name);
        header("Content-transfer-encoding: binary");
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header("Content-length: $size");       
        echo $file;
    }
    exit();
}