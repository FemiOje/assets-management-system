<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? null;
    $total_quantity = $_POST['total_quantity'] ?? null;
    $available_quantity = $_POST['available_quantity'] ?? null;
    $category = $_POST['category'] ?? null;

    $errors = [];

    if (empty($name)) {
        $errors['asset_id'] = 'Please add an asset name.';
    }

    if (empty($description)) {
        $errors['description'] = 'Please add a description.';
    }

    if (empty($total_quantity) || $total_quantity < 1) {
        $errors['total_quantity'] = 'Total Quantity must be at least 1.';
    }

    if (empty($available_quantity) || $available_quantity < 1) {
        $errors['available_quantity'] = 'Available Quantity must be at least 1.';
    }

    if (empty($category)) {
        $errors['category'] = 'Please add a category.';
    }

    // If no errors, insert the request
    if (empty($errors)) {
        // $employee_id = $employee['employee_id']; // From session/auth

        $sql = "INSERT INTO assets (name, description, total_quantity, available_quantity, category) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssiis', $name, $description, $total_quantity, $available_quantity, $category);

        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Added asset successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to add asset. Please try again.</div>";
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
?>


<section class="add-new-asset">
    <h2>Add New Asset</h2>
    <form method="POST" action="?section=add_new_asset">
        <div class="form-group">
            <label for="name">Asset Name:</label>
            <input type="text" name="name">
        </div>

        <div class="form-group">
            <label for="name">Description:</label>
            <input type="text" name="description">
        </div>

        <div class="form-group">
            <label for="total_quantity">Quantity</label>
            <input type="number" name="total_quantity" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label for="available_quantity">Available Quantity:</label>
            <input type="number" name="available_quantity" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label for="name">Category:</label>
            <input type="text" name="category">
        </div>

        <button type="submit" class="btn btn-primary">Add Asset</button>
    </form>
</section>