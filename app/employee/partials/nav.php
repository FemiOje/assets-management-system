<div class="mobile-menu-toggle">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
    </svg>
</div>
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