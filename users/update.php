<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include required files
include_once '../config/Database.php';
include_once '../objects/User.php';

// create database and user objects
$database = new Database();
$db = $database->connect();
$user = new User($db);

// capture json data
$data = json_decode(file_get_contents("php://input"));

// identify user to be updated
$user->user_id = $data->user_id;

// check if user exists then update them
if ($user->check_user()) {
    // set user the property values
    $user->username = $data->username;
    $user->user_firstname = $data->user_firstname;
    $user->user_lastname = $data->user_lastname;
    $user->user_email = $data->user_email;
    $user->user_image = $data->user_image;
    $user->role = $data->role;

    // update the post
    if ($user->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "User details updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update user details."));
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "This user does not exist."));
}