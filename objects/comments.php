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
    public function read_comments() {
        $query = "SELECT * FROM ".$this->table_name." WHERE comment_post_id=?";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->comment_post_id);
        $result->execute();
        return $result;
    }

    public function check_comment() {
        $query = "SELECT * FROM ".$this->table_name." WHERE comment_id=? LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->comment_id, PDO::PARAM_INT);
        $result->execute();

        if ($result->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }

    // function to create a comment for a specific post
    public function create_comment(): bool {
        $query = "INSERT INTO ".$this->table_name." (comment_post_id, comment_author, comment_email,
        comment_content, comment_status) VALUES (?,?,?,?,?)";
        $result = $this->conn->prepare($query);
        // sanitize inputs
        $this->comment_post_id = htmlspecialchars(strip_tags($this->comment_post_id));
        $this->comment_author = htmlspecialchars(strip_tags($this->comment_author));
        $this->comment_email = htmlspecialchars(strip_tags($this->comment_email));
        $this->comment_content = htmlspecialchars(strip_tags($this->comment_content));
        $this->comment_status = htmlspecialchars(strip_tags($this->comment_status));

        if ($result->execute([$this->comment_post_id, $this->comment_author, $this->comment_email,
        $this->comment_content, $this->comment_status])) {
            return true;
        } else {
            print_r($result->errorInfo());
            return false;
        }
    }

    public function delete(): bool {
        $query = "DELETE FROM ".$this->table_name." WHERE comment_id=?";
        $result = $this->conn->prepare($query);
        // $result = bindParam(1, $this->post_id, PDO::PARAM_INT);
        if($result->execute(array($this->comment_id))) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $query = "UPDATE ".$this->table_name." SET comment_content=?, comment_status=?
        WHERE comment_id=".$this->comment_id;

        $result = $this->conn->prepare($query);

        // sanitize inputs
        $this->comment_content = htmlspecialchars(strip_tags($this->comment_content));
        $this->comment_status = htmlspecialchars(strip_tags($this->comment_status));

        // execute query
        if ($result->execute([$this->comment_content, $this->comment_status])) {
            return true;
        } else {
            return false;
        }
    }

    // function to approve comments

    // function to delete comment
}