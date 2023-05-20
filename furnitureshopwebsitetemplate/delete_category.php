<?php
// delete_category.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the categoryId parameter is provided
    if (isset($_POST['categoryId'])) {
        $categoryId = $_POST['categoryId'];

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

        // Delete the products associated with the category
        $deleteProductsQuery = "DELETE FROM Products WHERE prodcat = '$categoryId'";
        if (!mysqli_query($conn, $deleteProductsQuery)) {
            $response = array(
                'success' => false,
                'message' => 'Error deleting products: ' . mysqli_error($conn)
            );
            echo json_encode($response);
            mysqli_close($conn);
            exit;
        }

        // Delete the category
        $deleteCategoryQuery = "DELETE FROM Categories WHERE category_id = '$categoryId'";
        if (!mysqli_query($conn, $deleteCategoryQuery)) {
            $response = array(
                'success' => false,
                'message' => 'Error deleting category: ' . mysqli_error($conn)
            );
            echo json_encode($response);
            mysqli_close($conn);
            exit;
        }

        // Close the database connection
        mysqli_close($conn);

        // Return a response indicating success
        $response = array(
            'success' => true,
            'message' => 'Category deleted successfully.'
        );
        echo json_encode($response);
    } else {
        // categoryId parameter is not provided
        $response = array(
            'success' => false,
            'message' => 'Category ID not specified.'
        );
        echo json_encode($response);
    }
}
?>