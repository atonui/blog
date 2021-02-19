<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and post object files
include_once '../config/database.php';
include_once '../objects/post.php';

// instantiate database and post objects
$database = new Database();
$db = $database->connect();

$post = new Post($db);

//check GET variable is set
if (isset($_GET['post_id']) && $_GET['post_id']!="") {
    $post->post_id = $_GET['post_id'];

    // query the posts table
    $result = $post->read_one();

    // if more than 0 records are found

    if ($post->post_title!=null) {

            $post_item = array(
                "post_id" => $post->post_id,
                "post_category_id" => $post->post_category_id,
                "post_title" => $post->post_title,
                "post_author" => $post->post_author,
                "post_date" => $post->post_date,
                "post_image" => $post->post_image,
                "post_content" => $post->post_content,
                "post_tags" => $post->post_tags,
                "post_comment_count" => $post->post_comment_count,
                "post_status" => $post->post_status,
                "post_author_id" => $post->post_author_id,
                "post_views_count" => $post->post_views_count
            );

        // set response code - 200 ok
        http_response_code(200);

        // show posts data in json format
        echo json_encode($post_item);
    } else {
        // set response code to 404 not found
        http_response_code(404);
        // tell the usee that no posts were found
        echo json_encode(array("message"=>"No post found."));
    }
}