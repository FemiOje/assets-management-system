<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - <?= ucfirst(str_replace('_', ' ', $section)) ?></title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <header class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($employee['first_name']) ?>!</h1>
    </header>