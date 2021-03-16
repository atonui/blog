<?php

// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// home page url
$home_url = "http://localhost/blog/";
date_default_timezone_set('Africa/Nairobi');

// page given in URL parameter, default page is 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// set number of records per page
$records_per_page = 5;

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// jwt variables
$key = "example_key";
$issued_at = time();
$expiration_time = $issued_at + (3600); // valid for 1 hour
$issuer = $home_url;
