<?php

class Post {
    // database properties
    private $conn;
    private $table_name = "posts";

    // post properties
    public $post_id;
    public $post_category_id;
    public $post_title;
    public $post_author;
    public $post_date;
    public $post_image;
    public $post_content;
    public $post_tags;
    public $post_comment_count;
    public $post_status;
    public $post_author_id;
    public $post_views_count;

    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // function to read all posts
    public function read() {
        $query = "SELECT * FROM ".$this->table_name;
        $result = $this->conn->prepare($query);
        $result->execute();

        return $result;
    }

    // function to read one post
    public function read_one() {
        $query = "SELECT * FROM ".$this->table_name." WHERE post_id=?";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->post_id);
        $result->execute();

        // get retreive row
        $row = $result->fetch(PDO::FETCH_ASSOC);

        //set values to object properties
        $this->post_category_id = $row['post_category_id'];
        $this->post_title = $row['post_title'];
        $this->post_author = $row['post_author'];
        $this->post_date = $row['post_date'];
        $this->post_image = $row['post_image'];
        $this->post_content = $row['post_content'];
        $this->post_tags = $row['post_tags'];
        $this->post_comment_count = $row['post_comment_count'];
        $this->post_status = $row['post_status'];
        $this->post_author_id = $row['post_author_id'];
        $this->post_views_count = $row['post_views_count'];
    }

    public function create(){
        $query = "INSERT INTO ".$this->table_name." (post_category_id, post_title, post_author, post_image, 
        post_content, post_tags, post_comment_count, post_status, post_author_id, post_views_count)
        VALUES (?,?,?,?,?,?,?,?,?,?)";

        $result = $this->conn->prepare($query);

        // sanitize inputs
        $this->post_category_id = htmlspecialchars(strip_tags($this->post_category_id));
        $this->post_title = htmlspecialchars(strip_tags($this->post_title));
        $this->post_author = htmlspecialchars(strip_tags($this->post_author));
        // $this->post_date = htmlspecialchars(strip_tags($this->post_date));
        $this->post_image = htmlspecialchars(strip_tags($this->post_image));
        $this->post_content = htmlspecialchars(strip_tags($this->post_content));
        $this->post_tags = htmlspecialchars(strip_tags($this->post_tags));
        $this->post_comment_count = htmlspecialchars(strip_tags($this->post_comment_count));
        $this->post_status = htmlspecialchars(strip_tags($this->post_status));
        $this->post_author_id = htmlspecialchars(strip_tags($this->post_author_id));
        $this->post_views_count = htmlspecialchars(strip_tags($this->post_views_count));

        // execute query
        
        if ($result->execute([$this->post_category_id,$this->post_title,$this->post_author,
        $this->post_image,$this->post_content,$this->post_tags,$this->post_comment_count,
        $this->post_status,$this->post_author_id,$this->post_views_count])) {
            return true;
        } else {
            print_r($result->errorInfo());
            return false;
        }
    }

    public function update() {
        $query = "UPDATE ".$this->table_name." SET post_category_id=?, post_title=?, post_author=?, post_image=?, 
        post_content=?, post_tags=?, post_comment_count=?, post_status=?, post_author_id=?, post_views_count=?
        WHERE post_id=".$this->post_id;

        $result = $this->conn->prepare($query);

        // sanitize inputs
        $this->post_category_id = htmlspecialchars(strip_tags($this->post_category_id));
        $this->post_title = htmlspecialchars(strip_tags($this->post_title));
        $this->post_author = htmlspecialchars(strip_tags($this->post_author));
        // $this->post_date = htmlspecialchars(strip_tags($this->post_date));
        $this->post_image = htmlspecialchars(strip_tags($this->post_image));
        $this->post_content = htmlspecialchars(strip_tags($this->post_content));
        $this->post_tags = htmlspecialchars(strip_tags($this->post_tags));
        $this->post_comment_count = htmlspecialchars(strip_tags($this->post_comment_count));
        $this->post_status = htmlspecialchars(strip_tags($this->post_status));
        $this->post_author_id = htmlspecialchars(strip_tags($this->post_author_id));
        $this->post_views_count = htmlspecialchars(strip_tags($this->post_views_count));

        // execute query
        
        if ($result->execute([$this->post_category_id,$this->post_title,$this->post_author,
        $this->post_image,$this->post_content,$this->post_tags,$this->post_comment_count,
        $this->post_status,$this->post_author_id,$this->post_views_count])) {
            return true;
        } else {
            print_r($result->errorInfo());
            return false;
        }
    }

    public function check_id() {
        $query = "SELECT * FROM ".$this->table_name."WHERE post_id=? LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->post_id, PDO::PARAM_INT);
        $result->execute();

        if ($result->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $query = "DELETE FROM ".$this->table_name."WHERE post_id=?";
        $result = bindParam(1, $this->post_id, PDO::PARAM_INT);
        if($result->execute()) {
            return true;
        } else {
            return false;
        }
    }
}