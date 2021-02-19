<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include required files
include_once '../config/database.php';
include_once '../objects/post.php';

//create database and post objects
$database = new Database();
$db = $database->connect();

$post = new Post($db);

// capture json data
$data = json_decode(file_get_contents("php://input"));

// identify the post to be modified by id
$post->post_id = $data->post_id;

// check if the given post_id exists in the database
if ($post->check_id()) {
    // set the post property values
$post->post_category_id = $data->post_category_id;
$post->post_title = $data->post_title;
$post->post_author = $data->post_author;
// $post->post_date = $data->post_date;
$post->post_image = $data->post_image;
$post->post_content = $data->post_content;
$post->post_tags = $data->post_tags;
$post->post_comment_count = $data->post_comment_count;
$post->post_status = $data->post_status;
$post->post_author_id = $data->post_author_id;
$post->post_views_count = $data->post_views_count;

// update the product
if($post->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Post updated."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update post."));
}
    
} else {
    http_response_code(404);
    echo json_encode(array("message" => "This post does not exist."));
}
