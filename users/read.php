<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and user object files
include_once '../config/Database.php';
include_once '../objects/User.php';

// instantiate database and user objects
$database = new Database();
$db = $database->connect();
$user = new User($db);

// query users table
$results = $user->read();
$count = $results->rowCount();

if ($count > 0) {
    //create arrays to hold data from database records
    $users_array = array();

    //retrieve records using fetch()
    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['user_id'] to $user_id
        $users_item = array(
            "user_id" => $row['user_id'],
            "username" => $row['username'],
            // "user_password" => $row['user_password'],
            "user_firstname" => $row['user_firstname'],
            "user_lastname" => $row['user_lastname'],
            "user_email" => $row['user_email'],
            "user_image" => $row['user_image'],
            "role" => $row['role'],
            "date_created" => $row['date_created']
        );
        array_push($users_array, $users_item);
    }
    // show 200 ok and respond with json of users
    http_response_code(200);
    echo json_encode($users_array);
} else {
    http_response_code(404);
    // tell the user that no posts were found
    echo json_encode(array("message"=>"No users found."));
}
