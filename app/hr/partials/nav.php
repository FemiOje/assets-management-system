<nav class="dashboard-nav">
    <ul>
        <li>
            <a href="?section=personal_info" class="<?= $section === 'personal_info' ? 'active' : '' ?>">Personal Information</a>
        </li>

        <li>
            <a href="?section=request_asset" class="<?= $section === 'request_asset' ? 'active' : '' ?>">Request Asset</a>
        </li>

        <li>
            <a href="?section=view_requests" class="<?= $section === 'view_requests' ? 'active' : '' ?>">My Requests</a>
        </li>

        <li>
            <a href="?section=view_all_assets" class="<?= $section === 'view_all_assets' ? 'active' : '' ?>">View all Assets</a>
        </li>

        <li>
            <a href="?section=add_new_asset" class="<?= $section === 'add_new_asset' ? 'active' : '' ?>">Add New Asset</a>
        </li>

        <li>
            <a href="?section=view_assets" class="<?= $section === 'view_assets' ? 'active' : '' ?>">Assigned Assets</a>
        </li>

        <li>
            <a href="?section=forward_request" class="<?= $section === 'pending_requests' ? 'active' : '' ?>">View All Pending Requests</a>
        </li>

        <li>
            <a href="?section=manage_employees" class="<?= $section === 'manage_employees' ? 'active' : '' ?>">Manage Employees</a>
        </li>
        <li class="logout">
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
</nav>