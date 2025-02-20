<?php
session_start();
include("./includes/db.php");

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $query = "SELECT * FROM employees WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['employee_id'];
                $_SESSION['user_role'] = $row['role'];
                $_SESSION['user_name'] = $row['first_name'] . ' ' . $row['last_name'];
                $_SESSION['user_email'] = $row['email'];

                switch ($row['role']) {
                    case 'HR':
                        header("Location: hr/dashboard.php");
                        exit();
                    case 'MANAGER':
                        header("Location: manager/dashboard.php");
                        exit();
                    case 'EMPLOYEE':
                        header("Location: employee/dashboard.php");
                        exit();
                    default:
                        $error_message = "Invalid role assignment";
                }
            } else {
                $error_message = "Invalid email or password";
            }
        } else {
            $error_message = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/login.css">
    <title>Log In - Asset Management</title>
</head>

<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="wave-bg"></div>

            <h2>Welcome Back</h2>
            <form id="login-form" action="login.php" method="post">
                <div class="form-group email">
                    <br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                    <br>
                </div>

                <div class="form-group password">
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" name="password" required>
                    <br>
                </div>

                <?php if ($error_message): ?>
                    <div style="color: red;"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div style="color: green;"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <div class="submit">
                    <br>
                    <input type="submit" name="submit" value="Log in" class="auth-btn">
                </div>
            </form>
            <p class="auth-link">Don't have an account? <a href="./signup.php">Sign Up</a></p>
        </div>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>