<?php
require_once '../includes/db.php';
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM employees WHERE email='{$_SESSION['user_email']}'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . mysqli_error($conn)]);
        exit();
    }
    
    $requests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
    
    echo json_encode($requests);
}

mysqli_close($conn);
?>