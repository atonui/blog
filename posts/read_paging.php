<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type-: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/Utilities.php';
include_once '../config/Database.php';
include_once '../objects/Post.php';

$utilities = new Utilities();
$database = new Database();
$db = $database->connect();
$post = new Post($db);

// query posts
$stmt = $post->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

//check if more than 0 records found
if ($num > 0) {
    // posts array
    $posts_array = array();
    $posts_array["records"] = array();
    $posts_array["paging"] = array();

    // retrieve table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row data i.e this will make $row['name'] to just $name
        extract($row);

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
        array_push($posts_array['records'], $post_item);
    }
    // include paging
    $total_rows = $post->count();
    $page_url = "{$home_url}posts/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $posts_array['paging'] = $paging;

    // set response code okay
    http_response_code(200);

    echo json_encode($posts_array);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No posts found."]);
}




