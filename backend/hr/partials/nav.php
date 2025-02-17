<nav class="dashboard-nav">
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

        <li class="<?= $section === 'view_all_assets' ? 'active' : '' ?>">
            <a href="?section=view_all_assets">View all Assets</a>
        </li>

        <li class="<?= $section === 'add_new_asset' ? 'active' : '' ?>">
            <a href="?section=add_new_asset">Add New Asset</a>
        </li>

        <li class="<?= $section === 'view_assets' ? 'active' : '' ?>">
            <a href="?section=view_assets">Assigned Assets</a>
        </li>

        <li class="<?= $section === 'pending_requests' ? 'active' : '' ?>">
            <a href="?section=forward_request">View All Pending Requests</a>
        </li>

        <li class="<?= $section === 'manage_employees' ? 'active' : '' ?>">
            <a href="?section=manage_employees">Manage Employees</a>
        </li>
        <li class="logout">
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
</nav>