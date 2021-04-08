<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/blog/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once '../config/core.php';
include_once '../libraries/php-jwt-master/BeforeValidException.php';
include_once '../libraries/php-jwt-master/ExpiredException.php';
include_once '../libraries/php-jwt-master/SignatureInvalidException.php';
include_once '../libraries/php-jwt-master/JWT.php';
use \Firebase\JWT\JWT;

// retrieve jwt
$data = json_decode(file_get_contents("php://input"));
$jwt = isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if ($jwt) {
    try {
        $decoded_jwt = JWT::decode($jwt, $key, array('HS256'));
        http_response_code(200);

        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded_jwt->data
        ));
        // if the decoding fails it means that the token is invalid
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
    // if jwt is empty
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "Access denied."
    ));
}