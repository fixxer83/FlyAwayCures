<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Function to get connection parameters
function getConnectionParameters()
{
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $name = 'fly_away_cures';
    $port = '3306';
    
    $params = array($host, $user, $password, $name, $port);
    
    return $params;
}