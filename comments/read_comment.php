<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// include files
include_once '../config/database.php';
include_once '../objects/comments.php';

// check for GET variable
if (isset($_GET['post_id']) && $_GET['post_id'] !="") {
    // create database and comment object
    $database = new Database();
    $db = $database->connect();
    $comment = new Comment($db);

    $comment->comment_post_id = htmlspecialchars(strip_tags($_GET['post_id']));
    $result = $comment->read_comments();
    $count = $result->rowCount();
    // if comments are found
    if ($count > 0) {
        $comments_array = array();
        //$comments_array['records'] = array(); //placeholder array or working memory array
        //retrieve the posts using fetch()
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // extract($row) will make $row['comment_content'] to $comment_content
            // extract($row);
            $comment_item = array(
                "comment_id" => $row['comment_id'],
                "comment_post_id" => $row['comment_post_id'],
                "comment_author" => $row['comment_author'],
                "comment_email" => $row['comment_email'],
                "comment_content" => $row['comment_content'],
                "comment_status" => $row['comment_status'],
                "comment_date" => $row['comment_date']
            );
            // push rows one by one into the comments_array['records'] as a placeholder
            array_push($comments_array, $comment_item);
        }

        http_response_code(200);
        echo json_encode($comments_array);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No comments found."));
    }
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Insufficient data to complete transaction."));
}
