<?php

require __DIR__ . './config/cors.php';
require __DIR__ . './config/params.php';
require __DIR__ . './utils/render.php';
require __DIR__ . './utils/header.php';

$request_painel = $_SERVER;
$request_body = file_get_contents('php://input');
$request_replace_path = str_replace(HOST_PATH, "", $request_painel['REQUEST_URI']);
$request_endpoint = BASE_URL . $request_replace_path;
$request_api = curl_init();

try {
    curl_setopt($request_api, CURLOPT_URL, $request_endpoint);
    
    if (isset($request_painel) && $request_painel['REQUEST_METHOD'] == 'POST') :
        curl_setopt($request_api, CURLOPT_POST, true);
        curl_setopt($request_api, CURLOPT_POSTFIELDS, $request_body);
    else :
        curl_setopt($request_api, CURLOPT_CUSTOMREQUEST, $request_painel['REQUEST_METHOD']);
    endif;
    
    curl_setopt($request_api, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'authorization: Bearer ' . getToken()
    )); 

    curl_setopt($request_api, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($request_api);
    
    $code_http = curl_getinfo($request_api, CURLINFO_HTTP_CODE);

    response_send($code_http, $response);
} catch (Exception $e) {
    response_send(500, $e->getMessage(), true);
}finally{
    curl_close($request_api);
}