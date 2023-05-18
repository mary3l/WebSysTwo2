<?php
session_start();
$hostname = "localhost";
$database = "Shopee";
$db_login = "root";
$db_pass = "";
$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

if (isset($_POST['update_quantity'])) {
    $prodid = $_GET['prodid'];
    $new_quantity = $_POST['quantity'];
    $query = "UPDATE Products SET quantity=quantity-'$new_quantity' WHERE prodid='$prodid'";
    mysqli_query($dlink, $query);
    $_SESSION['cart'][$prodid] = $new_quantity;
    header('Location: cart.php');
    exit();
}
?>