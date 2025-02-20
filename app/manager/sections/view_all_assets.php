<?php
// $employee_id = $employee['employee_id'];

$query = "SELECT 
asset_id, 
name, 
description, 
total_quantity, 
available_quantity, 
category
FROM assets"
;

$stmt = mysqli_prepare($conn, $query);
if ($stmt === false) {
    echo "<div class='alert alert-danger'>Error preparing statement: " . mysqli_error($conn) . "</div>";
    return;
}

// mysqli_stmt_bind_param($stmt, 'i', $employee_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result === false) {
    echo "<div class='alert alert-danger'>Error getting result: " . mysqli_error($conn) . "</div>";
    mysqli_stmt_close($stmt);
    return;
}
?>

<section class="employee-assignments">
    <h2>All Assets</h2>
    <div class="assignments-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Assignment ID</th>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Total Quantity</th>
                    <th>Avaiilable Quantity</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['asset_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['available_quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>There are no assets in the asset table.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php mysqli_stmt_close($stmt); ?>