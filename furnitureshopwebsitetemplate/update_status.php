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

        // Update the status in the database
        $updateQuery = "UPDATE Purchase SET status='$newStatus' WHERE userid='$userId' AND prodid='$prodId'";
        $updateResult = mysqli_query($dlink, $updateQuery);

        if ($updateResult) {
            // Get the recipient (customer's user ID)
            $recipientQuery = "SELECT userid FROM user WHERE userid = '$userId'";
            $recipientResult = mysqli_query($dlink, $recipientQuery);

            if ($recipientResult && mysqli_num_rows($recipientResult) > 0) {
                $row = mysqli_fetch_assoc($recipientResult);
                $recipient = $row['userid'];

                // Check if the admin is not the recipient
                $adminId = $_COOKIE['userid'];
                if ($adminId !== $recipient) {
                    // Get the product information
                    $productQuery = "SELECT prodid, productname FROM products WHERE prodid = '$prodId'";
                    $productResult = mysqli_query($dlink, $productQuery);

                    if ($productResult && mysqli_num_rows($productResult) > 0) {
                        $productRow = mysqli_fetch_assoc($productResult);
                        $productId = $productRow['prodid'];
                        $productName = $productRow['productname'];

                        // Insert a message into the messages table
                        $message = "Admin has changed the order status for Customer $userId. The status of the product (ID: $productId, Name: $productName) has been changed to $newStatus";
                        $insertQuery = "INSERT INTO messages (sender, recipient, message, timestamp) VALUES ('Admin', '$recipient', '$message', NOW())";
                        $insertResult = mysqli_query($dlink, $insertQuery);

                        if ($insertResult) {
                            mysqli_close($dlink);
                            header("Location: customers.php");
                            exit();
                        } else {
                            echo "Error inserting message: " . mysqli_error($dlink);
                        }
                    } else {
                        echo "Error retrieving product information: " . mysqli_error($dlink);
                    }
                } else {
                    echo "You cannot reply to your own message.";
                }
            } else {
                echo "Error retrieving recipient: " . mysqli_error($dlink);
            }
        } else {
            echo "Error updating status: " . mysqli_error($dlink);
        }
    } else {
        echo "Invalid parameters. Please provide userid, prodid, quantity, date, and new_status.";
    }
}

mysqli_close($dlink);
?>