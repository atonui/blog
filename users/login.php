<?php
// required files
include_once '../config/core.php';
include_once '../config/Database.php';
include_once '../objects/User.php';

// required headers
header("Access-Control_Allow-Origin: {$home_url}");
header("Content-Type: application/json; charset=URF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->connect();
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents('php://input'));

// set property values
$user->user_email = $data->user_email;

// check if email exists
