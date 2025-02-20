<?php
checkUserRole(['HR']);

include_once "../includes/auth.php";
include_once "../includes/db.php";

// Handle employee updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $employee_id = (int)$_POST['employee_id'];
    $status = $_POST['status'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $role = $_POST['role'];

    $update_sql = "UPDATE employees 
                  SET status = ?, department = ?, position = ?, role = ?
                  WHERE employee_id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'ssssi', $status, $department, $position, $role, $employee_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Employee #$employee_id updated successfully";
    } else {
        $error = "Error updating employee: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Get all employees
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Employee Management</h2>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="employee_id" value="<?= $row['employee_id'] ?>">
                        
                        <td><?= $row['employee_id'] ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . htmlspecialchars($row['last_name']))?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        
                        <td>
                            <select name="department" class="form-control">
                                <option value="">None</option>
                                <option value="IT" <?= $row['department'] === 'IT' ? 'selected' : '' ?>>IT</option>
                                <option value="HR" <?= $row['department'] === 'HR' ? 'selected' : '' ?>>HR</option>
                                <option value="Finance" <?= $row['department'] === 'Finance' ? 'selected' : '' ?>>Finance</option>
                            </select>
                        </td>
                        
                        <td>
                            <input type="text" name="position" class="form-control" 
                                   value="<?= htmlspecialchars($row['position']) ?>">
                        </td>
                        
                        <td>
                            <select name="role" class="form-control">
                                <option value="EMPLOYEE" <?= $row['role'] === 'EMPLOYEE' ? 'selected' : '' ?>>Employee</option>
                                <option value="HR" <?= $row['role'] === 'HR' ? 'selected' : '' ?>>HR</option>
                                <option value="MANAGER" <?= $row['role'] === 'MANAGER' ? 'selected' : '' ?>>Manager</option>
                            </select>
                        </td>
                        
                        <td>
                            <select name="status" class="form-control">
                                <option value="ACTIVE" <?= $row['status'] === 'ACTIVE' ? 'selected' : '' ?>>Active</option>
                                <option value="INACTIVE" <?= $row['status'] === 'INACTIVE' ? 'selected' : '' ?>>Inactive</option>
                                <option value="RESIGNED" <?= $row['status'] === 'RESIGNED' ? 'selected' : '' ?>>Resigned</option>
                            </select>
                        </td>
                        
                        <td>
                            <button type="submit" name="update_employee" class="btn btn-primary btn-sm">
                                Update
                            </button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>