<?php
class Comment {
    // database properties
    private $conn;
    private $table_name = "comments";

    // comment properties
    public $comment_id;
    public $comment_post_id;
    public $comment_author;
    public $comment_email;
    public $comment_content;
    public $comment_status;
    public $comment_date;

    // constructor with $db as the database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //function to read all comments for a specific post
    public function read_comment() {
        $query = "SELECT * FROM ".$this->table_name." WHERE comment_post_id=?";
        $result = $this->conn->prepare($query);
        $result = bindParam(1, $this->comment_post_id);
        $result->execute();
        return $result;
    }

    // function to create a comment for a specific post
    public function create_comment() {
        $query = "INSERT INTO ".$this->table_name." (comment_post_d, comment_author, comment_email,
        comment_content, comment_status) VALUES (?,?,?,?,?)";
        $result = $this->conn->prepare($query);
        // sanitize inputs
        $this->comment_post_id = htmlspecialchars(strip_tags($this->comment_post_id));
        $this->comment_author = htmlspecialchars(strip_tags($this->comment_author));
        $this->comment_email = htmlspecialchars(strip_tags($this->comment_email));
        $this->comment_content = htmlspecialchars(strip_tags($this->comment_content));
        $this->comment_status = htmlspecialchars(strip_tags($this->comment_status));

        if ($result->execute($this->comment_post_id, $this->comment_author, $this->comment_email,
        $this->comment_content, $this->comment_status)) {
            return true;
        } else {
            print_r($result->errorInfo());
            return false;
        }
    }

    // function to approve comments

    // function to delete comment
}