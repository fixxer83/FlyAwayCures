<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.*/

echo $name = $_FILES['file']['name'];
$tmp_name = $_FILES['file']['tmp_name'];
fopen($tmp, 'b');
//die();
//
move_uploaded_file($tmp_name, 'upload/'. $name );
//
?>


<form action="testPage.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file"><br><br>
    <input type="submit" value="Submit">    
</form>