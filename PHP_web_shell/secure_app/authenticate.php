<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (mysqli_num_rows($result) == 1) {
        $session_id = session_id();
        setcookie('session_id', $session_id, [
            'expires' => time() + (86400 * 30),
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        header("location: secure_area.php");
    } else {
        echo "Invalid username or password.";
    }
}
?>
