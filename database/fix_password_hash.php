<?php
include "../app/includes/db.php";

// First, let's check if the column is TEXT
$check_column = "SHOW COLUMNS FROM employees WHERE Field = 'password_hash'";
$result = mysqli_query($conn, $check_column);
$column_info = mysqli_fetch_assoc($result);

if ($column_info['Type'] === 'text') {
    // Create a temporary column
    mysqli_query($conn, "ALTER TABLE employees ADD COLUMN password_hash_new VARCHAR(255)");
    
    // Get all employees
    $query = "SELECT employee_id, password_hash FROM employees";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Rehash the password using the current hash as the password
        $new_hash = password_hash($row['password_hash'], PASSWORD_DEFAULT);
        
        // Update the new column
        $update = "UPDATE employees SET password_hash_new = ? WHERE employee_id = ?";
        $stmt = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt, "si", $new_hash, $row['employee_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Drop the old column and rename the new one
    mysqli_query($conn, "ALTER TABLE employees DROP COLUMN password_hash");
    mysqli_query($conn, "ALTER TABLE employees CHANGE password_hash_new password_hash VARCHAR(255) NOT NULL");
    
    echo "Password hash column has been fixed successfully.";
} else {
    echo "Password hash column is already using the correct type (VARCHAR(255)).";
}

mysqli_close($conn);
?> 