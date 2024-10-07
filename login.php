<?php
session_start();
$correct_username = "admin"; // Change this for your setup
$correct_password = "password123"; // Change this for your setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Injection vulnerability
    $conn = new mysqli("localhost", "root", "", "ws"); // Adjust as necessary
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
         echo "<script>window.location.href = 'sql_con.html';</script>";
    } else {
        echo "<script>window.location.href = 'error1.html';</script>";
    }
    $conn->close();
}
?>
