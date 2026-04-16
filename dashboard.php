<?php
include 'db.php';
include 'header.php';

/* =========================
   STATS (FIXED LOGIC)
========================= */

// Total bookings (exclude cancelled)
$total_bookings = $conn->query("
    SELECT COUNT(*) as total 
    FROM bookings 
    WHERE status != 'Cancelled'
")->fetch_assoc()['total'];

// Total customers
$total_customers = $conn->query("
    SELECT COUNT(*) as total 
    FROM customers
")->fetch_assoc()['total'];

// Revenue ONLY approved bookings
$total_revenue = $conn->query("
    SELECT SUM(guests * 500) as total 
    FROM bookings 
    WHERE status = 'Approved'
")->fetch_assoc()['total'] ?? 0;

?>

<style>
body {
    background: #0b1220;
    font-family: 'Segoe UI', sans-serif;
    color: #e5e7eb;
}

/* TITLE */
.dashboard-title {
    font-size: 22px;
    font-weight: 600;
}

/* CARDS */
.stat-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 18px;
    transition: .2s;
}

.stat-card:hover {
    transform: translateY(-4px);
}

/* GLASS */
.glass {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 20px;
}

/* TABLE */
.table {
    color: #e5e7eb;
}

.table-dark {
    background: #111827;
}

/* BUTTONS */
.btn-soft {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 10px;
}

/* ACTION BUTTONS */
.btn-action {
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 12px;
}
</style>

<div class="container-fluid">
<div class="row">

<?php include 'sidebar.php'; ?>

<div class="col-md-10 p-4">

    <div class="dashboard-title mb-4">
        🎉 Event Booker Dashboard
    </div>

    <!-- STATS -->
    <div class="row g-4">

        <div class="col-md-4">
            <div class="stat-card">
                <small>Total Bookings</small>
                <h3><?= $total_bookings ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <small>Total Customers</small>
                <h3><?= $total_customers ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <small>Revenue</small>
                <h3>₱<?= number_format($total_revenue, 2) ?></h3>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="row mt-5 g-4">

        <div class="col-md-6">
            <div class="glass">
                <h5>System Status</h5>

                <?php if ($total_bookings > 10): ?>
                    <div class="alert alert-success mb-0">
                        System is growing 🚀
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        Keep improving 📈
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-md-6">
            <div class="glass">
                <h5>Quick Actions</h5>

                <a href="book.php" class="btn btn-soft w-100 mb-2">➕ Add Booking</a>

                <button class="btn btn-soft w-100 mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#viewBookingsModal">
                    📋 View Bookings
                </button>

                <a href="reports.php" class="btn btn-soft w-100">📊 Reports</a>

            </div>
        </div>

    </div>

</div>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="viewBookingsModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light">

      <div class="modal-header">
        <h5 class="modal-title">📋 Booking Management</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <?php
        $bookings = $conn->query("
            SELECT b.*, c.name AS customer_name
            FROM bookings b
            LEFT JOIN customers c ON b.customer_id = c.customer_id
            ORDER BY b.event_date DESC
        ");
        ?>

        <table class="table table-dark table-hover">

            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Venue</th>
                    <th>Guests</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php while($row = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['venue']) ?></td>
                    <td><?= (int)$row['guests'] ?></td>
                    <td><?= $row['event_date'] ?></td>
                    <td><?= $row['event_type'] ?></td>

                    <!-- STATUS -->
                    <td>
                        <?php
                        if ($row['status'] == 'Approved') {
                            echo '<span class="badge bg-success">Approved</span>';
                        } elseif ($row['status'] == 'Rejected') {
                            echo '<span class="badge bg-danger">Rejected</span>';
                        } elseif ($row['status'] == 'Cancelled') {
                            echo '<span class="badge bg-secondary">Cancelled</span>';
                        } else {
                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                        }
                        ?>
                    </td>

                    <!-- ACTION -->
                    <td>

                        <a href="update_status.php?id=<?= $row['booking_id'] ?>&status=Approved"
                           class="btn btn-success btn-sm">
                            Approve
                        </a>

                        <a href="update_status.php?id=<?= $row['booking_id'] ?>&status=Rejected"
                           class="btn btn-danger btn-sm">
                            Reject
                        </a>

                        <a href="update_status.php?id=<?= $row['booking_id'] ?>&status=Cancelled"
                           class="btn btn-secondary btn-sm">
                            Cancel
                        </a>

                    </td>

                </tr>
            <?php endwhile; ?>

            </tbody>

        </table>

      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>