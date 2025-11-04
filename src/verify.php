<?php
$connection = new mysqli('localhost', 'root', '', 'revision', 3306);
if ($connection->connect_error) {
    echo "there is an error in connecting to database";
    die();
}   
else{
echo "database is connected";
}

if($_SERVER['REQUEST_METHOD']=='GET'){
if(isset($_GET['token']))
{
    $usertoken=$_Get['token'];
    $sql="select * from auth where token='$usertoken'";
    $result=mysqli_query($connection,$sql);
    if($result->num_rows==0)
    {
        echo"<script>alert('token is invalid')</script>";
        exit();
    }
    while($dbtoken=mysqli_fetch_assoc($result))
     {
       $userdata=$dbtoken;
        $sql="update auth set is_verified=1, token='null' where id={$userdata['id']}";
    $result=mysqli_query($connection,$sql);
    if($result)
    {
        echo"<script>alert('email is verified ')</script>";
    }
    }
   
}
else{
    echo"<script>alert('you cannot access this file')</script>";
    header('location:main.php');
}
}
else
{
    header('location:main.php');
}
?>