<?php

function getHeaderAuth(){
    $headers = null;

    if (isset($_SERVER['Authorization'])) :
        $headers = trim($_SERVER["Authorization"]);
    elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) :
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    elseif (function_exists('apache_request_headers')) :
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        
        if (isset($requestHeaders['Authorization'])) :
            $headers = trim($requestHeaders['Authorization']);
        endif;
    endif;

    return $headers;
}

function getToken() {
    $headers = getHeaderAuth();

    if (!empty($headers)) :
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) :
            return $matches[1];
        endif;
    endif;
    return null;
}