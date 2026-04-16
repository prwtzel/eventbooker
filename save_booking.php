<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = $_POST['customer_id'];
    $venue = $_POST['venue'];
    $guests = $_POST['guests'];
    $event_date = $_POST['event_date'];
    $event_type = $_POST['event_type'];

    $sql = "INSERT INTO bookings (customer_id, venue, guests, event_date, event_type)
            VALUES ('$customer_id', '$venue', '$guests', '$event_date', '$event_type')";

    if ($conn->query($sql)) {

        // ✅ REDIRECT BACK TO DASHBOARD
        header("Location: customer_dashboard.php?success=1");
        exit();

    } else {
        die("Error: " . $conn->error);
    }
}
?>