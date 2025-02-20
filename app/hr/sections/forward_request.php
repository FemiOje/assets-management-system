<?php
include_once "../includes/auth.php";
include_once "../includes/db.php";
checkUserRole(['HR']);

// Handle request forwarding
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forward_request'])) {
    $request_id = (int)$_POST['request_id'];
    
    $update_sql = "UPDATE requests 
                  SET status = 'pending_approval', hr_action = TRUE 
                  WHERE request_id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'i', $request_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Add notification to manager (implementation needed)
        $success = "Request #$request_id forwarded to manager successfully";
    } else {
        $error = "Error forwarding request: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Get all submitted requests
$query = "SELECT r.*, e.first_name, e.last_name, a.name AS asset_name 
          FROM requests r
          JOIN employees e ON r.employee_id = e.employee_id
          JOIN assets a ON r.asset_id = a.asset_id
          WHERE r.status = 'submitted'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forward Requests</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Pending Requests</h2>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Employee</th>
                    <th>Asset</th>
                    <th>Quantity</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['request_id'] ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . htmlspecialchars($row['last_name']))?></td>
                    <td><?= htmlspecialchars($row['asset_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['request_date'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                            <button type="submit" name="forward_request" class="btn btn-primary btn-sm">
                                Forward to Manager
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>