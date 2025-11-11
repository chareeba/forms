<?php
session_start();
if(isset($_SESSION['user_id'])&& isset($_SESSION['fullname']))
    {
        echo $_SESSION['user_id'];
    echo"<script>alert('welcome to the dashboard')</script>";
    
}

else{
    echo"<script>alert('please login to access the dashboard')</script> ";
    header("location:main.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>WELCOME TO YOUR DASHBOARD</h1>
    <a href="logout.php">logout</a>
</body>
</html>

