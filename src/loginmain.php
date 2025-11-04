<?php
$connection = new mysqli('localhost', 'root', '', 'revision', 3306);
if ($connection->connect_error) {
    echo "there is an error in connecting to database";
    die();
}
else{
echo "database is connected";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $password = md5($_POST['password']);
    $email = $_POST['email'];

    $query = "select * from auth where email='$email' and password='$password'";
    $res = mysqli_query($connection, $query);
}