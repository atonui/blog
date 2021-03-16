<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include files
include_once '../config/core.php';
include_once '../config/Database.php';
include_once '../objects/Post.php';

// instantiate database and post objects
$database = new Database();
$db = $database->connect();
$post = new Post($db);

if (isset($_GET['s'])) {
// grab search keywords
    $keywords = isset($_GET['s']) ? $_GET['s'] : "";

// run query
    $result = $post->search($keywords);
    $count = $result->rowCount();

    if ($count > 0) {
        $post_array = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $post_item = array(
                "post_id" => $row['post_id'],
                "post_category_id" => $row['post_category_id'],
                "post_title" => $row['post_title'],
                "post_author" => $row['post_author'],
                "post_date" => $row['post_date'],
                "post_image" => $row['post_image'],
                "post_content" => $row['post_content'],
                "post_tags" => $row['post_tags'],
                "post_comment_count" => $row['post_comment_count'],
                "post_status" => $row['post_status'],
                "post_author_id" => $row['post_author_id'],
                "post_views_count" => $row['post_views_count']
            );
            array_push($post_array, $post_item);
        }
        // set ok response code
        http_response_code(200);
        // show data
        echo json_encode($post_array);
    } else {
        // no records found
        http_response_code(404);

        echo json_encode(array("message" => "No posts found"));
    }
} else {
    http_response_code(404);

    echo json_encode(array("message" => "No search term set."));
}
