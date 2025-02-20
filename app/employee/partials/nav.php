<!-- <nav class="dashboard-nav">
    <ul>
        <li class="<?= $section === 'personal_info' ? 'active' : '' ?>">
            <a href="?section=personal_info">Personal Information</a>
        </li>
        <li class="<?= $section === 'request_asset' ? 'active' : '' ?>">
            <a href="?section=request_asset">Request Asset</a>
        </li>
        <li class="<?= $section === 'view_requests' ? 'active' : '' ?>">
            <a href="?section=view_requests">My Requests</a>
        </li>
        <li class="<?= $section === 'view_assets' ? 'active' : '' ?>">
            <a href="?section=view_assets">Assigned Assets</a>
        </li>
        <li class="logout">
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
</nav> -->

<nav class="dashboard-nav">
    <ul>
        <li><a href="?section=personal_info" class="<?= $section === 'personal_info' ? 'active' : '' ?>">Personal Info</a></li>
        <li><a href="?section=request_asset" class="<?= $section === 'request_asset' ? 'active' : '' ?>">Request Asset</a></li>
        <li><a href="?section=view_requests" class="<?= $section === 'view_requests' ? 'active' : '' ?>">View My Requests</a></li>
        <li><a href="?section=view_assets" class="<?= $section === 'view_assets' ? 'active' : '' ?>">View Assets</a></li>
        <li class="logout">
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
</nav>