<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $asset_id = $_POST['asset_id'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    $errors = [];

    if (empty($asset_id)) {
        $errors['asset_id'] = 'Please select an asset.';
    }

    if (empty($quantity) || $quantity < 1) {
        $errors['quantity'] = 'Quantity must be at least 1.';
    }

    // If no errors, insert the request
    if (empty($errors)) {
        $employee_id = $employee['employee_id']; // From session/auth
        $status = 'SUBMITTED'; // Default status

        $sql = "INSERT INTO requests (employee_id, asset_id, quantity, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iiis', $employee_id, $asset_id, $quantity, $status);

        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Request submitted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to submit request. Please try again.</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Display errors
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }
}

// Fetch available assets for the dropdown
$assets_query = "SELECT * FROM assets WHERE available_quantity > 0";
$assets_result = mysqli_query($conn, $assets_query);
$assets = mysqli_fetch_all($assets_result, MYSQLI_ASSOC);
?>

<section class="request-asset">
    <h2>Request Asset</h2>
    <form method="POST" action="?section=request_asset">
        <div class="form-group">
            <label for="asset_id">Select Asset</label>
            <select name="asset_id" id="asset_id" class="form-control" required>
                <option value="">-- Select an asset --</option>
                <?php foreach ($assets as $asset): ?>
                    <option value="<?= htmlspecialchars($asset['asset_id']) ?>">
                        <?= htmlspecialchars($asset['name']) ?> (Available: <?= $asset['available_quantity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</section>