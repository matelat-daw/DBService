<?php
function callService($url, $postData = [])
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($postData)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response === false) {
        return ['status' => 500, 'message' => 'Error al llamar al servicio'];
    }
    return json_decode($response, true);
}