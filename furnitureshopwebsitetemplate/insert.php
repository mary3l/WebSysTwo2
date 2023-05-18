<?php
$hostname = "localhost";
$database = "resto";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

// Retrieve the prodid value from the URL
$prodid = $_GET['prodid'];

// Perform the insertion into the database
$query = "INSERT INTO Products (prodid, productname, productdesc, productlink, productimage, quantity, lastprice, curprice) VALUES ('$prodid', 'New Item', 'New Item', 'products.php', 'images/newProduct.png', 0, 0, 0)";
$result = mysqli_query($dlink, $query);

if ($result) {
    echo '<script>alert("Product inserted successfully.");</script>';
} else {
    echo '<script>alert("Failed to insert product. Please try again.");</script>';
}

mysqli_close($dlink);
?>