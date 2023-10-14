<?php

function response_send($code, $response, $converte = false){    
    http_response_code($code);
    $body = $converte ? json_encode($response) : $response;
    echo $body;
}