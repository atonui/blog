<?php
// required headers
header("Access-Control-Allow-Origin: *");;
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// include necessary files
include_once '../config/Database.php';
include_once '../objects/Comment.php';

// create database and comment objects
$database = new Database();
$db = $database->connect();
$comment = new Comment($db);

// capture json data

$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    // populate comment variables
    $comment->comment_post_id = $data->comment_post_id;
    $comment->comment_author = $data->comment_author;
    $comment->comment_email = $data->comment_email;
    $comment->comment_content = $data->comment_content;
    $comment->comment_status = $data->comment_status;

    if ($comment->create_comment()) {
        http_response_code(201);
        echo json_encode(array("message" => "Comment was created"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create comment"));
    }
    // if data is incomplete
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create comment. Data was incomplete."));
}