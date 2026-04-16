<?php 
session_start();
include 'db.php';

// CHECK LOGIN
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// BOOKINGS QUERY
$bookings = $conn->query("
    SELECT * FROM bookings 
    WHERE customer_id='$customer_id'
    ORDER BY event_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard - Event Booker</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 🌈 LIVE BACKGROUND */
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial;
            background: linear-gradient(-45deg, #667eea, #764ba2, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .header {
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(10px);
            color: white;
            padding: 25px;
            border-radius: 0 0 25px 25px;
        }

        .card-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 18px;
            color: white;
        }

        table {
            color: white !important;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success text-center m-0">
    🎉 Booking Successfully Saved!
</div>
<?php endif; ?>

<?php if (isset($_GET['cancel'])): ?>
<div class="alert alert-danger text-center m-0">
    ❌ Booking Cancelled Successfully
</div>
<?php endif; ?>

<!-- HEADER -->
<div class="header text-center">
    <h3>👋 Welcome, <?php echo $_SESSION['customer_name'] ?? 'Guest'; ?></h3>
    <p>Book your events easily</p>

    <button class="btn btn-light mt-2" data-bs-toggle="modal" data-bs-target="#bookModal">
        ➕ Book Event
    </button>
</div>

<div class="container mt-4">

    <!-- BOOKINGS TABLE -->
    <div class="card-box p-4">

        <h5 class="mb-3">📋 My Bookings</h5>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Venue</th>
                    <th>Guests</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($bookings && $bookings->num_rows > 0): ?>
                    <?php while ($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['venue'] ?></td>
                            <td><?= $row['guests'] ?></td>
                            <td><?= $row['event_date'] ?></td>
                            <td><?= $row['event_type'] ?></td>

                            <!-- STATUS -->
                            <td>
                                <?php
                                $status = $row['status'] ?? 'Pending';

                                if ($status == 'Approved') {
                                    echo '<span class="badge bg-success">Approved</span>';
                                } elseif ($status == 'Rejected') {
                                    echo '<span class="badge bg-danger">Rejected</span>';
                                } elseif ($status == 'Cancelled') {
                                    echo '<span class="badge bg-dark">Cancelled</span>';
                                } else {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                }
                                ?>
                            </td>

                            <!-- ACTION (CANCEL) -->
                            <td>
                                <?php if ($status == 'Pending'): ?>
                                    <a href="cancel_booking.php?id=<?= $row['booking_id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Cancel this booking?')">
                                        Cancel
                                    </a>
                                <?php else: ?>
                                    <span class="text-light">—</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No bookings yet</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<!-- BOOKING MODAL -->
<div class="modal fade" id="bookModal" tabindex="-1">
  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">📅 Book Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form action="save_booking.php" method="post">

            <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

            <div class="mb-2">
                <label>Venue</label>
                <input name="venue" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Guests</label>
                <input id="guests" name="guests" type="number" class="form-control" required>
                <small id="totalDisplay" class="text-success fw-bold"></small>
            </div>

            <div class="mb-2">
                <label>Event Date</label>
                <input name="event_date" type="date" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Event Type</label>
                <select name="event_type" class="form-control">
                    <option>Birthday</option>
                    <option>Wedding</option>
                    <option>Corporate</option>
                    <option>Others</option>
                </select>
            </div>

            <button class="btn btn-primary w-100 mt-2">
                🎉 Submit Booking
            </button>

        </form>

      </div>

    </div>

  </div>
</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>