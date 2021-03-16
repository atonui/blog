<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  // include files
include_once '../config/Database.php';
include_once '../objects/User.php';

// create database and user objects
$database = new Database();
$db = $database->connect();
$user = new User($db);

//capture posted json data

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $user->user_email = $data->user_email;
    if (!$user->emailExists()) {
        $user->username = $data->username;
        $user->user_password = $data->user_password;
        $user->user_firstname = $data->user_firstname;
        $user->user_lastname = $data->user_lastname;

        // create the user
        if ($user->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "User created"));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create user"));
        }
        // if the data is incomplete
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Email already exists."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data was incomplete."));
}
