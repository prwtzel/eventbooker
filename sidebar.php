<style>
    .sidebar {
        background: #0f172a;
        min-height: 100vh;
        padding: 20px;
        color: white;
        position: sticky;
        top: 0;
    }

    .sidebar h4 {
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        margin-bottom: 8px;
        border-radius: 10px;
        text-decoration: none;
        color: #cbd5e1;
        transition: 0.2s ease;
        font-size: 15px;
    }

    .sidebar a:hover {
        background: #1e293b;
        color: #fff;
        transform: translateX(4px);
    }

    .sidebar a.active {
        background: #2563eb;
        color: #fff;
    }

    .sidebar hr {
        border: 0;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 15px 0;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 20px;
        width: calc(100% - 40px);
    }
</style>

<div class="col-md-2 sidebar">

    <h4>🍽️ Event Booker</h4>
    <hr>

    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    

    <a href="reports.php" class="<?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>">
        <i class="bi bi-bar-chart-line"></i> Reports
    </a>

    <hr>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

</div>