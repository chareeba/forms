<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    header("Location: dashboard.php");
    exit();
}

require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$connection = new mysqli('localhost', 'root', '', 'revision', 3306);
if ($connection->connect_error) {
    echo "there is an error in connecting to database";
    die();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM auth WHERE email='$email'";
    $result = mysqli_query($connection, $query);
    $num = mysqli_num_rows($result);

    if ($num == 0) {
        echo "<script>alert('Email not registered. Please sign up first.')</script>";
    } else {
        $row = mysqli_fetch_assoc($result);

        // Generate password reset token
        $reset_token = bin2hex(random_bytes(16));
        $tz= new DateTimeZone('Asia/karachi');
        $expiry= new DateTime('now',$tz);
        $expiry->modify('+1 hour');
        $reset_token_expiry = $expiry->format('Y_m_d H:i:s');

        // Store reset token in database
        $update_query = "UPDATE auth SET reset_token='$reset_token', reset_token_expiry='$reset_token_expiry' WHERE email='$email'";
        $update_result = mysqli_query($connection, $update_query);

        if ($update_result) {
            // Send password reset email
            $mail = new PHPMailer();
            try {
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "areebatariq961@gmail.com";
                $mail->Password = " ";
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;
                $mail->setFrom("areebatariq961@gmail.com");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Password Reset Request";
                $mail->Body = "Click the link to reset your password: <a href='http://localhost/forms/src/reset-password.php?token=$reset_token'>Reset Password</a><br><br>This link will expire in 1 hour.";
                $mail->send();
                echo "<script>alert('Password reset link has been sent to your email')</script>";
            } catch (Exception $e) {
                echo "<script>alert('Error sending email: {$e->getMessage()}')</script>";
            }
        } else {
            echo "<script>alert('Error processing request. Please try again.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Forgot Password</title>
</head>

<body>
    <section class="user">
        <div class="user_options-container">
            <div class="user_options-text">
                <div class="user_options-unregistered">
                    <h2 class="user_unregistered-title">Remember your password?</h2>
                    <p class="user_unregistered-text">If you remember your password, you can go back to the login page and access your account.</p>
                    <button class="user_unregistered-signup" onclick="window.location.href='main.php'">Back to Login</button>
                </div>

                <div class="user_options-registered">
                    <h2 class="user_registered-title">Need an account?</h2>
                    <p class="user_registered-text">Don't have an account yet? Sign up now and join our community.</p>
                    <button class="user_registered-login" onclick="window.location.href='main.php'">Sign Up</button>
                </div>
            </div>

            <div class="user_options-forms" id="user_options-forms" style="transform: translate3d(0, -50%, 0);">
                <div class="user_forms-login" style="opacity: 1; visibility: visible;">
                    <h2 class="forms_title">Reset Password</h2>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 30px; line-height: 1.5;">Enter your email address and we'll send you a link to reset your password.</p>
                    <form class="forms_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <fieldset class="forms_fieldset">
                            <div class="forms_field">
                                <input type="email" placeholder="Email Address" class="forms_field-input" name="email" required autofocus />
                            </div>
                        </fieldset>
                        <div class="forms_buttons">
                            <button type="button" class="forms_buttons-forgot" onclick="window.location.href='main.php'">Back to Login</button>
                            <input type="submit" value="Send Reset Link" class="forms_buttons-action">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>

</html>