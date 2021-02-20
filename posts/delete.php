<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include files
include_once '../config/database.php';
include_once '../objects/post.php';

// create database and post objects

$database = new Database();
$db = $database->connect();
$post = new Post($db);

// get post_id to delete
$data = json_decode(file_get_contents("php://input"));
$post->post_id = $data->post_id;

// check if the post exists then delete it.
if ($post->check_id()) {
    if ($post->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Post was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete post."));
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Post does not exist."));
    print_r($post->post_id); 
}