<?php
function sendRequest($url, $method, $data = null) {
    $ch = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "DELETE":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // OPTIONS:
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // EXECUTE:
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return json_encode(array('response' => $result, 'http_code' => $httpCode));
}
?>
