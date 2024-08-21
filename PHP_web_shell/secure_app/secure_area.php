<?php
include('config.php');

if(!isset($_COOKIE['session_id']) || $_COOKIE['session_id'] !== session_id()) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Area</title>
</head>
<body>
    <h2>Welcome to the secure area!</h2>
    <p>You have successfully logged in.</p>
</body>
</html>
