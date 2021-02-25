<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and post object files
include_once '../config/database.php';
include_once '../objects/post.php';

// instantiate database and post objects
$database = new Database();
$db = $database->connect();

$post = new Post($db);

// query the posts table
$result = $post->read();
$count = $result->rowCount();

// if more than 0 records are found

if ($count > 0) {
    // posts array
    $posts_array = array();
    $posts_array['records'] = array();

    // retrieve the posts using fetch()
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['post_content'] to $post_content
        extract($row);
        $post_item = array(
            "post_id" => $post_id,
            "post_category_id" => $post_category_id,
            "post_title" => $post_title,
            "post_author" => $post_author,
            "post_date" => $post_date,
            "post_image" => $post_image,
            "post_content" => $post_content,
            "post_tags" => $post_tags,
            "post_comment_count" => $post_comment_count,
            "post_status" => $post_status,
            "post_author_id" => $post_author_id,
            "post_views_count" => $post_views_count
        );
        array_push($posts_array['records'], $post_item);
    }

    // set response code - 200 ok
    http_response_code(200);

    // show posts data in json format
    echo json_encode($posts_array);
} else {
    // set response code to 404 not found
    http_response_code(404);
    // tell the user that no posts were found
    echo json_encode(array("message"=>"No posts found."));
}