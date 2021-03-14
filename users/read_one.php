<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// include database and user object files
include_once '../config/Database.php';
include_once  '../objects/User.php';

// instantiate database and user objects
$database = new Database();
$db = $database->connect();
$user = new User($db);

// check that GET variable is set
if (isset($_GET['user_id']) && $_GET['user_id']!="") {
    $user->user_id = $_GET['user_id'];

    //query the users table
    if($user->read_one()) {
        $user_item = array(
            "user_id" => $user->user_id,
            "username" => $user->username,
            "user_firstname" => $user->user_firstname,
            "user_lastname" => $user->user_lastname,
            "user_email" => $user->user_email,
            "user_image" => $user->user_image,
            "role" => $user->role,
            "date_created" => $user->date_created
        );

        // set response code to 200 okay
        http_response_code(200);
        // show users in json format
        echo json_encode($user_item);
    } else {
        // code 404, user not found
        http_response_code(404);
        echo json_encode(array("message" => "No user found"));
    }
} else {
    // code 404, user not found
    http_response_code(400);
    echo json_encode(array("message" => "No user found due to incomplete data."));
}




