<?php


class User {
    // User properties

    public $user_id;
    public $username;
    public $user_password;
    public $user_firstname;
    public $user_lastname;
    public $user_email;
    public $user_image;
    public $role;
    public $date_created;

    // database properties
    private $conn;
    private $table_name = "users";

    // constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE A FUNCTION THAT LOOPS THROUGH DATABASE RESULTS AND PUTS THEM INTO A JSON OBJECT

    // function to read all users
    public function read() {
        $query = "SELECT * FROM ".$this->table_name;
        $results = $this->conn->prepare($query);
        $results->execute();

        return $results;
    }

    // function to return one user
    public function read_one() {
        $query = "SELECT * FROM ".$this->table_name." WHERE user_id=?";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->user_id);
        $result->execute();

        // if the user exists then the query executes
        if ($result->rowCount() > 0) {
            // retrieve rows from results
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $this->user_id = $row['user_id'];
            $this->username = $row['username'];
            // $this->user_password = $row['user_password'];
            $this->user_firstname = $row['user_firstname'];
            $this->user_lastname = $row['user_lastname'];
            $this->user_email = $row['user_email'];
            $this->user_image = $row['user_image'];
            $this->role = $row['role'];
            $this->date_created = $row['date_created'];
            return true;
        } else {
            return false;
        }
    }

    public function create(): bool
    {
        $query = "INSERT INTO ".$this->table_name." (username, user_password, user_firstname, 
                user_lastname, user_email) VALUES (?,?,?,?,?)";

        $result = $this->conn->prepare($query);
        // takasa maingizo
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->user_password = htmlspecialchars(strip_tags($this->user_password));
        $this->user_firstname = htmlspecialchars(strip_tags($this->user_firstname));
        $this->user_lastname = htmlspecialchars(strip_tags($this->user_lastname));
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));

        //execute query
        if ($result->execute([$this->username, $this->user_password, $this->user_firstname, $this->user_lastname,
            $this->user_email])) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(): bool
    {
        $query = "DELETE FROM ".$this->table_name." WHERE user_id=?";
        $result = $this->conn->prepare($query);
        if ($result->execute(array($this->user_id))) {
            return true;
        } else {
            return false;
        }
    }

    public function check_user(): bool
    {
        $query = "SELECT * FROM ".$this->table_name." WHERE user_id=? LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->bindParam(1, $this->user_id, PDO::PARAM_INT);
        $result->execute();

        if ($result->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }

    public function update(): bool
    {
        $query = "UPDATE ".$this->table_name." SET username=?, user_firstname=?, user_lastname=?,
        user_email=?, user_image=?, role=? WHERE user_id= ".$this->user_id;

        $result = $this->conn->prepare($query);

        // sanitize inputs
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->user_firstname = htmlspecialchars(strip_tags($this->user_firstname));
        $this->user_lastname = htmlspecialchars(strip_tags($this->user_lastname));
        $this->user_email = htmlspecialchars(strip_tags($this->user_email));
        $this->user_image = htmlspecialchars(strip_tags($this->user_image));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // execute query
        if ($result->execute([$this->username, $this->user_firstname, $this->user_lastname,
            $this->user_email, $this->user_image, $this->role])) {
            return true;
        } else {
            return false;
        }
    }

}