<?php
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Check if the user is an admin
if (isset($_COOKIE['type']) && $_COOKIE['type'] == 'admin') {
    // Check if the form is submitted and the new status is not null
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['new_status'] !== null) {
        // Get the form data
        $userId = $_POST['userid'];
        $prodId = $_POST['prodid'];
        $newStatus = $_POST['new_status'];
        $status = $_POST['status'];
        $selectedDate = $_POST['date'];

        // Your database connection code here
        // ...

        // Update the status in the database
        $updateQuery = "UPDATE Purchase SET status='$newStatus' WHERE userid='$userId' AND prodid='$prodId'";
        $updateResult = mysqli_query($dlink, $updateQuery);

        // Redirect back to the same page
        header("Location: customers.php?status=$status&date=" . urlencode($selectedDate));
        exit;
    }
}


mysqli_close($dlink);
?>