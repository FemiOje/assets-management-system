<header class="dashboard-header">
    <h1>Welcome, <?= htmlspecialchars($employee['first_name']) ?>!</h1>
</header>
<section class="personal-info">
    <h2>Personal Information</h2>
    <div class="info-card">
        <div class="info-row">

            <p><span>Name: </span> <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></p>
        </div>
        <div class="info-row">

            <p><span>Email: </span><?= htmlspecialchars($employee['email']) ?></p>
        </div>
        <div class="info-row">

            <p><span>Department: </span><?= htmlspecialchars($employee['department'] ?? 'N/A') ?></p>
        </div>
    </div>
</section>