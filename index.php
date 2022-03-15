<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once ('../Request.php');
require_once("vendor/autoload.php");
use App\Database\Database;

$database = Database::connect();

Request::init();
