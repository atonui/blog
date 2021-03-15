<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include files
include_once '../config/Database.php';
include_once '../objects/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

// get the user id to delete
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    $user->user_id = $data->user_id;
    //check if user exists then delete
    if ($user->check_user()) {
        if ($user->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "User deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete user."));
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "User does not exist."));
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Insufficient data supplied."]);
}