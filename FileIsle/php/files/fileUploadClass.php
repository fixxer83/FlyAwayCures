<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getFileContents()
{  
    $fileName = $_FILES["fileToUpload"]['name'];
    $tmp_name  = $_FILES["fileToUpload"]['tmp_name'];
    $fileSize = $_FILES["fileToUpload"]['size'];
    $fileType = $_FILES["fileToUpload"]['type'];
    
    if($open = fopen($tmp_name, 'r'))
    {
        $fileContent = fread($open, filesize($tmp_name));
        fclose($open);
        $fileContent = addslashes($fileContent);
    }

    $fileSize = convertFileSize($fileSize);
    
    session_start();
    $_SESSION['name'] = $fileName;
    $_SESSION['fsize'] =  $fileSize;
    $_SESSION['ftype'] = $fileType;
   
    $file[] = array();
    $file[0] = $fileName;
    $file[1] = $fileSize;
    $file[2] = $fileType;
    $file[3] = $fileContent;
    
    return $file;       
}

//Convert file size in bytes from binary to the applicable unit
function convertFileSize($fileSize, $precision = 2)
{
    //Returns the float The logarithm for $fileSize
    $float = log($fileSize, 1024);  

    //Return the caluculated file size
    return round(pow(1024, $float - floor($float)), $precision);
}