<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include files
include_once '../config/Database.php';
include_once '../objects/Comment.php';

// create database and comment objects
$database = new Database();
$db = $database->connect();
$comment = new Comment($db);

// capture json dat
$data = json_decode(file_get_contents("php://input"));

// set comment_id
$comment->comment_id = $data->comment_id;

// check if comment exists
if ($comment->check_comment()) {
    //grab property values
    $comment->comment_content = $data->comment_content;
    $comment->comment_status = $data->comment_status;

    // update the comment
    if ($comment->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Comment updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update comment."));
    }

} else {
    http_response_code(404);
    echo json_encode(array("message" => "This comment does not exist."));
}