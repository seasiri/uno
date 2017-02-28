<?php
//DATABASE CONNECTION VARIABLES
$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/private/config.ini'); 
$host = "localhost"; // Host name
$username = "root"; // Mysql username
$password = "sunnykung"; // Mysql password
$db_name = $config['dbname']; // Database name

//DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

$tbl_prefix = ""; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
$tbl_members = $tbl_prefix."authentication";
$tbl_attempts = $tbl_prefix."loginattempts";
