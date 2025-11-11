<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])) {
    header("Location: dashboard.php");
    exit();
}

$connection = new mysqli('localhost', 'root', '', 'revision', 3306);
if ($connection->connect_error) {
    echo "there is an error in connecting to database";
    die();
}

$error_message = "";
$success_message = "";
$valid_token = false;
$email = "";

// Check if token is provided
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $query = "SELECT * FROM auth WHERE reset_token='$token' AND reset_token_expiry > NOW()";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $valid_token = true;
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
    } else {
        $error_message = "Invalid or expired reset token. Please request a new password reset link.";
    }
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reset_token'])) {
    $token = $_POST['reset_token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match!";
        $valid_token = true; // Keep form visible
    } elseif (strlen($new_password) < 6) {
        $error_message = "Password must be at least 6 characters long!";
        $valid_token = true; // Keep form visible
    } else {
        // Verify token again before resetting
        $query = "SELECT * FROM auth WHERE reset_token='$token' AND reset_token_expiry > NOW()";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            $hashed_password = md5($new_password);

            // Update password and clear reset token
            $update_query = "UPDATE auth SET password='$hashed_password', reset_token=NULL, reset_token_expiry=NULL WHERE reset_token='$token'";

            if (mysqli_query($connection, $update_query)) {
                $success_message = "Password reset successful! Redirecting to login...";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'main.php';
                    }, 3000);
                </script>";
            } else {
                $error_message = "Error updating password. Please try again.";
                $valid_token = true;
            }
        } else {
            $error_message = "Invalid or expired reset token.";
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
    <title>Reset Password</title>
</head>

<body>
    <section class="user">
        <div class="user_options-container">
            <div class="user_options-text">
                <div class="user_options-unregistered">
                    <h2 class="user_unregistered-title">Need Help?</h2>
                    <p class="user_unregistered-text">If you're having trouble resetting your password, please contact support or try again.</p>
                    <button class="user_unregistered-signup" onclick="window.location.href='forgot-password.php'">Request New Link</button>
                </div>

                <div class="user_options-registered">
                    <h2 class="user_registered-title">Remember Password?</h2>
                    <p class="user_registered-text">If you remember your password, you can go back and login to your account.</p>
                    <button class="user_registered-login" onclick="window.location.href='main.php'">Back to Login</button>
                </div>
            </div>

            <div class="user_options-forms" id="user_options-forms" style="transform: translate3d(0, -50%, 0);">
                <div class="user_forms-login" style="opacity: 1; visibility: visible;">
                    <h2 class="forms_title">Reset Password</h2>

                    <?php if ($success_message): ?>
                        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 3px; margin-bottom: 20px; font-size: 0.9rem;">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 3px; margin-bottom: 20px; font-size: 0.9rem;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($valid_token && !$success_message): ?>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 30px; line-height: 1.5;">Enter your new password below.</p>
                        <form class="forms_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="reset_token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                            <fieldset class="forms_fieldset">
                                <div class="forms_field">
                                    <input type="password" placeholder="New Password" class="forms_field-input" name="password" required autofocus />
                                </div>
                                <div class="forms_field">
                                    <input type="password" placeholder="Confirm Password" class="forms_field-input" name="confirm_password" required />
                                </div>
                            </fieldset>
                            <div class="forms_buttons">
                                <button type="button" class="forms_buttons-forgot" onclick="window.location.href='main.php'">Back to Login</button>
                                <input type="submit" value="Reset Password" class="forms_buttons-action">
                            </div>
                        </form>
                    <?php elseif (!$valid_token && !$success_message): ?>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 30px; line-height: 1.5;">
                            Please click the "Request New Link" button to get a fresh password reset link.
                        </p>
                        <div class="forms_buttons" style="justify-content: center;">
                            <button class="forms_buttons-action" onclick="window.location.href='forgot-password.php'">Request New Link</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</body>

</html>