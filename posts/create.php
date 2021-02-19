<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include files
include_once '../config/database.php';
include_once '../objects/post.php';

// get database connection
$database = new Database();
$db = $database->connect();

// create post object
$post = new Post($db);

// capture posted data
$data = json_decode(file_get_contents("php://input"));

// echo print_r($data);

if (!empty($data)) {
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

    print_r($post->post_content);

    // create the post
    if ($post->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Post was created"));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create post"));
    }
// if data is incomplete
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create post. Data was incomplete."));
}

