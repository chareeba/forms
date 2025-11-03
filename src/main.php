<?php
$connection=new mysqli('localhost','root','','revision',3306);
if($connection->connect_error)
{
    echo "there is an error in connecting to database";
    die();
}   
     echo "database is connected";


 if($_SERVER['REQUEST_METHOD']=="POST")
     {
      
        $fullname=$_POST['fullname'];
        $password=md5($_POST['password']);
         $email=$_POST['email'];
     

        // print $name . '</br>' .$cnic . '</br>' .$password . '</br>' .$city . '</br>';
     $sql="insert into  auth(fullname,password,email)values ('$fullname','$password','$email') ";
         $result=mysqli_query($connection,$sql);
     if($result==true)
     {
        echo"<script>alert('data is inserted')</script>";
        // header("Location: record.php");
     }
     else{
        echo"<script>alert('data is not inserted')</script>";
     }
      
   }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>login page</title>
</head>
<body>
    <section class="user">
  <div class="user_options-container">
    <div class="user_options-text">
      <div class="user_options-unregistered">
        <h2 class="user_unregistered-title">Don't have an account?</h2>
        <p class="user_unregistered-text">Banjo tote bag bicycle rights, High Life sartorial cray craft beer whatever street art fap.</p>
        <button class="user_unregistered-signup" id="signup-button">Sign up</button>
      </div>

      <div class="user_options-registered">
        <h2 class="user_registered-title">Have an account?</h2>
        <p class="user_registered-text">Banjo tote bag bicycle rights, High Life sartorial cray craft beer whatever street art fap.</p>
        <button class="user_registered-login" id="login-button">Login</button>
      </div>
    </div>
    
    <div class="user_options-forms" id="user_options-forms">
      <div class="user_forms-login">
        <h2 class="forms_title">Login</h2>
        <form class="forms_form"   action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="register-form">>
          <fieldset class="forms_fieldset">
            <div class="forms_field">
              <input type="email" placeholder="Email" class="forms_field-input" name="email" required autofocus  />
            </div>
            <div class="forms_field">
              <input type="password" placeholder="Password" class="forms_field-input" name="password" required />
            </div>
          </fieldset>
          <div class="forms_buttons">
            <button type="button" class="forms_buttons-forgot">Forgot password?</button>
            <input type="submit" value="Log In" class="forms_buttons-action">
          </div>
        </form>
      </div>
      <div class="user_forms-signup">
        <h2 class="forms_title">Sign Up</h2>
        <form    class="forms_form"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="register-form">   >
          <fieldset class="forms_fieldset">
            <div class="forms_field">
              <input type="text" placeholder="Full Name" class="forms_field-input" name="fullname" required />
            </div>
            <div class="forms_field">
              <input type="email" placeholder="Email" class="forms_field-input" name="email" required />
            </div>
            <div class="forms_field">
              <input type="password" placeholder="Password" class="forms_field-input" name="password" required />
            </div>
          </fieldset>
          <div class="forms_buttons">
            <input type="submit" value="Sign up" class="forms_buttons-action">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

</body>
<script src="main.js"></script>
</html>