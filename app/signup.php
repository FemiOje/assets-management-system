<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'asset_mgt_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = $last_name = $email = $password = $department = $position = $role = '';
$errors = [];

// Process form data after submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate first name
    if (empty(trim($_POST['first_name']))) {
        $errors['first_name'] = 'Please enter your first name.';
    } else {
        $first_name = trim($_POST['first_name']);
    }

    // Validate last name
    if (empty(trim($_POST['last_name']))) {
        $errors['last_name'] = 'Please enter your last name.';
    } else {
        $last_name = trim($_POST['last_name']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $errors['email'] = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        } else {
            // Check if email exists
            $sql = "SELECT employee_id FROM employees WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['email'] = 'This email is already registered.';
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $errors['password'] = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $errors['password'] = 'Password must have at least 8 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate department and position
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);

    // Validate role
    if (empty(trim($_POST['role']))) {
        $errors['role'] = 'Please select a role.';
    } else {
        $role = strtoupper(trim($_POST['role'])); // Convert to uppercase
        if (!in_array($role, ['EMPLOYEE', 'HR', 'MANAGER'])) {
            $errors['role'] = 'Invalid role selected.';
        }
    }

    // Check for errors before inserting
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employees (first_name, last_name, email, password_hash, department, position, role, date_joined, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'ACTIVE')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssss', $first_name, $last_name, $email, $password_hash, $department, $position, $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Employee registered successfully!';
            header('Location: login.php');
            exit();
        } else {
            $errors['database'] = 'Something went wrong. Please try again later.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Asset Management</title>
    <link rel="stylesheet" href="../styles/signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <!-- Side Image -->
            <div class="side-image">
                <img src="../styles/sign-up.png" alt="Welcome Image">
            </div>

            <!-- Signup Form -->
            <div class="form-section">
                <h2>Create Your Account</h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="<?php echo $first_name; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?php echo $last_name; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $email; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" required>
                            <option value="">Select Department</option>
                            <option value="IT">IT</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" value="<?php echo $position; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="EMPLOYEE">Employee</option>
                            <option value="HR">HR</option>
                            <option value="MANAGER">Manager</option>
                        </select>
                    </div>

                    <button type="submit" class="auth-btn">Register</button>
                </form>

                <p class="auth-link">Already have an account? <a href="./login.php">Log In</a></p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>