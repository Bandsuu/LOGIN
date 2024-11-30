<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_POST ["name"]) ){
    die("Name is required!");
}
if ( ! filter_var ($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid Email is required");
}

if (strlen($_POST["password"]) < 8) {
    die ("Password must be at least 8 Characters");
}
if (! preg_match("/[a-z]/i", $_POST["password"])){
    die("Password must contatin atleast one letter");
}
if (! preg_match("/[0-9]/i", $_POST["password"])){
    die("Password must contatin at least one number");
}
if ($_POST["password"] != $_POST["confirm-password"]) {
    die("Password must match");
}

$password_hash = password_hash( $_POST["password"], PASSWORD_DEFAULT);

$mysqli = require  __DIR__ . "/database.php";

$sql = "INSERT INTO users (fullname, email, password_hash)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
                  
                  
if ($stmt->execute()) {

    
    header("Location: signup-success.html");
    exit;
                    
} else {
                    
if ($mysqli->errno === 1062) {
    die("email already taken");
} else {
    die($mysqli->error . " " . $mysqli->errno);
        }
}