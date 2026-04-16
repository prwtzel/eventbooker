<?php
include 'db.php';
include 'header.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include 'sidebar.php'; ?>

        <div class="col-md-10 p-4">

            <h2 class="mb-4">📊 Reports Dashboard</h2>

            <?php
            // TOTAL BOOKINGS
            $totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];

            // TOTAL CUSTOMERS
            $totalCustomers = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'];

            // TOTAL REVENUE
            $totalRevenue = $conn->query("SELECT SUM(guests * 500) as total FROM bookings")->fetch_assoc()['total'] ?? 0;

            // MONTHLY REPORT
            $monthly = $conn->query("
                SELECT 
                    DATE_FORMAT(event_date, '%Y-%m') as month,
                    COUNT(*) as bookings,
                    SUM(guests * 500) as revenue
                FROM bookings
                GROUP BY DATE_FORMAT(event_date, '%Y-%m')
                ORDER BY month DESC
            ");
            ?>

            <!-- SUMMARY CARDS -->
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card shadow p-3 text-white bg-primary">
                        <h5>Total Bookings</h5>
                        <h2><?= $totalBookings ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow p-3 text-white bg-success">
                        <h5>Total Customers</h5>
                        <h2><?= $totalCustomers ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow p-3 text-white bg-warning">
                        <h5>Total Revenue</h5>
                        <h2>₱<?= number_format($totalRevenue, 2) ?></h2>
                    </div>
                </div>

            </div>

            <!-- MONTHLY REPORT TABLE -->
            <div class="card shadow mt-5 p-3">
                <h5 class="mb-3">📅 Monthly Report</h5>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php while ($row = $monthly->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['month'] ?></td>
                                <td><?= $row['bookings'] ?></td>
                                <td>₱<?= number_format($row['revenue'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>