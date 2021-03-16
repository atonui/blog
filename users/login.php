<?php
// required headers
header("Access-Control_Allow-Origin: http://localhost/blog/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once '../config/core.php';
include_once '../config/Database.php';
include_once '../objects/User.php';
include_once '../libraries/php-jwt-master/BeforeValidException.php';
include_once '../libraries/php-jwt-master/ExpiredException.php';
include_once '../libraries/php-jwt-master/SignatureInvalidException.php';
include_once '../libraries/php-jwt-master/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->connect();
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents('php://input'));

// set property values
$user->user_email = $data->user_email;

// check if email exists and password is correct
$email_exists = $user->emailExists();

if ($email_exists && password_verify($data->user_password, $user->user_password)) {
    $token = array(
        "iat" => $issued_at,
        "exp" => $expiration_time,
        "iss" => $issuer,
        "data" => array(
            "user_id" => $user->user_id,
            "user_firstname" => $user->user_firstname,
            "lastname" => $user->user_lastname,
            "email" => $user->user_email
        )
    );

    // set okay response code
    http_response_code(200);
    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(array(
        "message" => "Successful login.",
        "jwt" => $jwt
    ));
    // login failure
} else {
    // set response code
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}

