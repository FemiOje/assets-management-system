<?php
$employee_id = $employee['employee_id'];

$query = "SELECT 
    assignments.assignment_id,
    assignments.assigned_date,
    assignments.returned_date,
    assets.name AS asset_name,
    assets.description,
    requests.request_id
FROM assignments
INNER JOIN assets ON assignments.asset_id = assets.asset_id
INNER JOIN requests ON assignments.request_id = requests.request_id
WHERE assignments.employee_id = ?
ORDER BY assignments.assigned_date DESC";

$stmt = mysqli_prepare($conn, $query);
if ($stmt === false) {
    echo "<div class='alert alert-danger'>Error preparing statement: " . mysqli_error($conn) . "</div>";
    return;
}

mysqli_stmt_bind_param($stmt, 'i', $employee_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result === false) {
    echo "<div class='alert alert-danger'>Error getting result: " . mysqli_error($conn) . "</div>";
    mysqli_stmt_close($stmt);
    return;
}
?>

<section class="employee-assignments">
    <h2>My Assigned Assets</h2>
    <div class="assignments-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Assignment ID</th>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Request ID</th>
                    <th>Assigned Date</th>
                    <th>Return Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['assignment_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['asset_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['request_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['assigned_date']) . "</td>";
                        echo "<td>" . (is_null($row['returned_date']) ? 'Currently Assigned' : 'Returned on ' . htmlspecialchars($row['returned_date'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>You don't have any assigned assets.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php mysqli_stmt_close($stmt); ?>