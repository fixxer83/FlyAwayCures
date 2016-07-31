<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const HTTP_SEARCH_URL = "https://rome2rio12.p.mashape.com/Search";
const MASH_SEARCH_API = "OrLZQcdsO6mshZ6QJ3Xj1qFa1G1cp1vSkQIjsn5irVKotprNND";

// Rome2Rio URL getter
function getSearchUrl()
{
    return HTTP_SEARCH_URL;
}

// Mashape API key getter
function getSearchAPIKey()
{
    return MASH_SEARCH_API;
}

// GET Search Request
function getSearch($countries)
{
    $context = stream_context_create(array('http'=> array('method' => 'GET', 
        'header' => 'X-Mashape-Key:' . getSearchAPIKey())));
    
   $json = file_get_contents(getSearchUrl() . "?currency=EUR&dName=" . $countries[1] . "&oName=" . $countries[0], false, $context);
   
   return $json;
}