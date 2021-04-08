<?php

class Post
{
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
    // database properties
    private $conn;
    private $table_name = 'posts';

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE A FUNCTION THAT LOOPS THROUGH DATABASE RESULTS AND PUTS THEM INTO A JSON OBJECT

    // function to read all posts
    public function read()
    {
        $query = 'SELECT * FROM '.$this->table_name.' ORDER BY post_date DESC';
        $result = $this->conn->prepare($query);
        $result->execute();

        return $result;
    }

    // function to read one post
    public function read_one()
    {
        $query = 'SELECT * FROM '.$this->table_name.' WHERE post_id=?';
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->post_id);
        $result->execute();

        // retrieve rows from results
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

    public function create()
    {
        $query = 'INSERT INTO '.$this->table_name.' (post_category_id, post_title, post_author, post_image, 
        post_content, post_tags, post_comment_count, post_status, post_author_id, post_views_count)
        VALUES (?,?,?,?,?,?,?,?,?,?)';

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

        if ($result->execute([$this->post_category_id, $this->post_title, $this->post_author,
            $this->post_image, $this->post_content, $this->post_tags, $this->post_comment_count,
            $this->post_status, $this->post_author_id, $this->post_views_count, ])) {
            return true;
        }
        // print_r($result->errorInfo());
        return false;
    }

    public function update(): bool
    {
        $query = 'UPDATE '.$this->table_name.' SET post_category_id=?, post_title=?, post_author=?, post_image=?, 
        post_content=?, post_tags=?, post_comment_count=?, post_status=?, post_author_id=?, post_views_count=?
        WHERE post_id='.$this->post_id;

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

        if ($result->execute([$this->post_category_id, $this->post_title, $this->post_author,
            $this->post_image, $this->post_content, $this->post_tags, $this->post_comment_count,
            $this->post_status, $this->post_author_id, $this->post_views_count, ])) {
            return true;
        }
        print_r($result->errorInfo());

        return false;
    }

    public function check_id(): bool
    {
        $query = 'SELECT * FROM '.$this->table_name.' WHERE post_id=? LIMIT 1';
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->post_id, PDO::PARAM_INT);
        $result->execute();

        if ($result->fetchColumn()) {
            return true;
        }

        return false;
    }

    public function delete(): bool
    {
        $query = 'DELETE FROM '.$this->table_name.' WHERE post_id=?';
        $result = $this->conn->prepare($query);
        // $result = bindParam(1, $this->post_id, PDO::PARAM_INT);
        if ($result->execute([$this->post_id])) {
            return true;
        }

        return false;
    }

    public function search($keywords)
    {
        $query = 'SELECT * FROM '.$this->table_name.' WHERE post_title LIKE ? OR post_author LIKE ?
        OR post_content LIKE ? OR post_tags LIKE ? ORDER BY post_id DESC';

        $results = $this->conn->prepare($query);
        // sanitize inputs
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        // bind parameters
        $results->bindParam(1, $keywords);
        $results->bindParam(2, $keywords);
        $results->bindParam(3, $keywords);
        $results->bindParam(4, $keywords);
        //execute query
        $results->execute();

        return $results;
    }

    // read posts with pagination
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = 'SELECT * FROM '.$this->table_name.' ORDER BY post_date DESC LIMIT ?,?';
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $result->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $result->execute();

        return $result;
    }

    // used for paging products
    public function post_count()
    {
        $query = 'SELECT COUNT(*) as total_rows FROM '.$this->table_name;
        $result = $this->conn->prepare($query);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}
