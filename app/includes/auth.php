<?php
session_start();

function checkUserRole($allowed_roles) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        header("Location: ../login.php");
        exit();
    }
    
    // Check if user's role is allowed
    if (!in_array($_SESSION['user_role'], $allowed_roles)) {
        header("Location: ../login.php?error=unauthorized");
        exit();
    }
    
    return true;
}
?>