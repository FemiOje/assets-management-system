<?php
$employee_id = $employee['employee_id'];

$query = "SELECT requests.*, assets.name AS asset_name
          FROM requests
          INNER JOIN assets ON requests.asset_id = assets.asset_id
          WHERE employee_id = ?";
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

<section class="employee-requests">
    <h2>My Requests</h2>
    <div class="requests-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Asset Name</th>
                    <th>Quantity</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>HR Action</th>
                    <th>Manager Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['request_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['asset_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hr_action']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['manager_action']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>You have not submitted any requests.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</section>

<?php
mysqli_stmt_close($stmt);
?>