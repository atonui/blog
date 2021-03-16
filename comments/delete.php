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

$database = new Database();
$db = $database->connect();
$comment = new Comment($db);

// get comment_id to delete
$data = json_decode(file_get_contents("php://input"));
$comment->comment_id = $data->comment_id;

// check if comment exists then delete it
if ($comment->check_comment()) {
    if ($comment->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Comment deleted"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete comment."));
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Comment does not exist."));
}
