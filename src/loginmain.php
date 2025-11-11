<?php
session_start();
$connection = new mysqli('localhost', 'root', '', 'revision', 3306);
if ($connection->connect_error) {
    echo "there is an error in connecting to database";
    die();
}
echo "database is connected";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    // var_dump($password);
    $sql = "select * from auth where email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 0) {
        echo "<script>alert('Email not registered')</script>";
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    //md5 password check
    if ($row['password'] != $password) {
        echo "<script>alert('Incorrect Password')</script>";
        exit();
    }
    if ($row['is_verified'] == 0) {
        echo "<script>alert('Email not verified. Please verify your email.')</script>";
        exit();
    }
    echo "<script>alert('Login Successful')</script>";
    //session start
        
        //encrypted user id in session
        $_SESSION['user_id'] = base64_encode($row['id']);
        $_SESSION['username']= base64_encode($row['fullname']);
        $_COOKIE['user_id'] = base64_encode($row['id']);
        $_COOKIE['username']= base64_encode($row['fullname']);
        echo $_SESSION['user_id'];
        echo $_SESSION['username'];
        header("Location: dashboard.php");
} else {
}