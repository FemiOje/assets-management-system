<?php 
include "../includes/auth.php";
checkUserRole(['EMPLOYEE']);
include "../includes/db.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>    
    <div id="requests">
        <?php
        $query = "SELECT * FROM employees WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_email']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>Welcome to the Employee dashboard: " . htmlspecialchars($row['first_name']) . " " . 
                     htmlspecialchars($row['last_name']) . "</p>";
            }
        } else {
            echo "Error loading employee data";
        }
        
        mysqli_stmt_close($stmt);
        ?>
    </div>

    <nav>
        <a href="http://">Personal Information</a>
        <a href="http://">Request Asset</a>
        <a href="http://">View submitted requests</a>
        <a href="http://">View assets assigned to me</a>
    </nav>

    <a href="../logout.php" class="logout-button">Logout</a>
</body>
</html>

<?php
mysqli_close($conn);
?>