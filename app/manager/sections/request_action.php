<?php
include_once "../includes/auth.php";
include_once "../includes/db.php";
checkUserRole(['MANAGER']);

// Handle request approval/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action']; // 'approve' or 'decline'

    if ($action === 'approve') {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Get request details first
            $get_request = "SELECT r.*, a.available_quantity 
                          FROM requests r 
                          JOIN assets a ON r.asset_id = a.asset_id 
                          WHERE r.request_id = ?";
            $stmt = mysqli_prepare($conn, $get_request);
            mysqli_stmt_bind_param($stmt, 'i', $request_id);
            mysqli_stmt_execute($stmt);
            $request = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);

            // Check if there's enough quantity available
            if ($request['available_quantity'] < $request['quantity']) {
                throw new Exception("Not enough assets available. Only {$request['available_quantity']} available.");
            }

            // Approve the request
            $update_sql = "UPDATE requests 
                          SET status = 'APPROVED', manager_action = TRUE 
                          WHERE request_id = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, 'i', $request_id);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error approving request: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);

            // Insert into assignments table
            $assignment_sql = "INSERT INTO assignments (employee_id, asset_id, assigned_date, request_id) 
                              SELECT employee_id, asset_id, CURDATE(), request_id 
                              FROM requests 
                              WHERE request_id = ?";
            $stmt = mysqli_prepare($conn, $assignment_sql);
            mysqli_stmt_bind_param($stmt, 'i', $request_id);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error assigning asset: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);

            // Update the available quantity of the asset
            $update_quantity_sql = "UPDATE assets a 
                                  JOIN requests r ON a.asset_id = r.asset_id 
                                  SET a.available_quantity = a.available_quantity - r.quantity 
                                  WHERE r.request_id = ?";
            $stmt = mysqli_prepare($conn, $update_quantity_sql);
            mysqli_stmt_bind_param($stmt, 'i', $request_id);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error updating asset quantity: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);

            // Commit transaction
            mysqli_commit($conn);
            $success = "Request #$request_id approved and assigned successfully.";

        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }

    } elseif ($action === 'decline') {
        // Decline the request
        $update_sql = "UPDATE requests 
                      SET status = 'DECLINED', manager_action = TRUE 
                      WHERE request_id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, 'i', $request_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Request #$request_id declined successfully.";
        } else {
            $error = "Error declining request: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Invalid action.";
    }
}

// Get all pending approval requests
$query = "SELECT r.*, e.first_name, e.last_name, a.name AS asset_name, a.available_quantity 
          FROM requests r
          JOIN employees e ON r.employee_id = e.employee_id
          JOIN assets a ON r.asset_id = a.asset_id
          WHERE r.status = 'PENDING_APPROVAL'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requests Pending Review</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Requests Pending Review</h2>
        
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
                    <th>Available</th>
                    <th>Request Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['request_id'] ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['asset_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['available_quantity'] ?></td>
                    <td><?= $row['request_date'] ?></td>
                    <td>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm" <?= $row['available_quantity'] < $row['quantity'] ? 'disabled' : '' ?>>
                                Approve
                            </button>
                        </form>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                            <input type="hidden" name="action" value="decline">
                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
