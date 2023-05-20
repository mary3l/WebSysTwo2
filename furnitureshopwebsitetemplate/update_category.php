<?php
// Check if the required parameters are set
if (isset($_POST['category']) && isset($_POST['newCategoryName'])) {
    // Retrieve the category and new category name from the request
    $category = $_POST['category'];
    $newCategoryName = $_POST['newCategoryName'];

    // Connect to the database
    $hostname = "localhost";
    $database = "Shopee";
    $db_login = "root";
    $db_pass = "";
    $conn = mysqli_connect($hostname, $db_login, $db_pass, $database);
    if (!$conn) {
        $response = array(
            'success' => false,
            'message' => 'Connection failed: ' . mysqli_connect_error()
        );
        echo json_encode($response);
        exit;
    }

    // Update the category name in the database
    $sql = "UPDATE Products SET prodcat = '$newCategoryName' WHERE prodcat = '$category'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Category name updated successfully
        echo 'success';
    } else {
        // Failed to update category name
        echo 'error';
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Required parameters not set
    echo 'error';
}
?>