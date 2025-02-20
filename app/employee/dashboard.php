

<!-- ============================================================ -->
<?php
include "../includes/auth.php";
checkUserRole(['EMPLOYEE']);
include "../includes/db.php";

$query = "SELECT * FROM employees WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_email']);
mysqli_stmt_execute($stmt);
$employee = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$valid_sections = [
    'personal_info',
    'request_asset',
    'view_requests',
    'view_assets'
];

$section = $_GET['section'] ?? 'personal_info';
if (!in_array($section, $valid_sections)) {
    $section = 'personal_info';
}

include "partials/header.php";
?>
<div class="dashboard-container">
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <?php include "partials/nav.php"; ?>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <?php 
            $section_file = "sections/{$section}.php";
            if(file_exists($section_file)) {
                include $section_file;
            } else {
                echo "<div class='alert alert-danger'>Invalid section</div>";
            }
            ?>
        </main>
    </div>

    <!-- Footer -->
    <?php include "partials/footer.php"; ?>
</div>

<?php
mysqli_close($conn);
?>