<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
    <meta charset="UTF-8" />
    <title>Products - Furniture Shop Web Template</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
    <div id="header">
        <a href="index.html"><img src="images/logo.gif" alt="Image" /></a>
        <div>
            <ul>
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="calendar.php">Calendar</a>
                </li>
                <li class="current">
                    <a href="customers.php">Customers Orders</a>
                </li>
                <li>
                    <a href="blog.html">Blog</a>
                </li>
                <li>
                    <a href="contact.html">Contact</a>
                </li>
            </ul>
            <form action="index.html">
                <input type="text" id="search" value="Enter Keyword or item #" />
                <input type="submit" value="Search" id="searchbtn" />
            </form>
        </div>
    </div>
    <div id="body">
        <div id="">
            <br>

            <!-- PHP HERE FOR CUSTOMER ORDERS -->
            <?php
            // Check if the user is logged in and has the usertype of "admin"
            if (!isset($_COOKIE['type']) || $_COOKIE['type'] !== 'admin') {
                header("Location: index.php?action=login&#login_form");
                exit();
            }

            $hostname = "localhost";
            $database = "Shopee";
            $db_login = "root";
            $db_pass = "";
            $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

            // Retrieve the user ID based on the email from the user table
            $userQuery = "SELECT userid, usertype FROM user WHERE email='$customerEmail'";
            $userResult = mysqli_query($dlink, $userQuery);
            $user = mysqli_fetch_assoc($userResult);
            $userId = $user['userid'];
            $userType = $user['usertype'];

            // Check if the user is an admin
            if (isset($_COOKIE['type']) && $_COOKIE['type'] == 'admin') {

                // Get the status parameter from the URL, defaulting to empty
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                // Get the selected date from the calendar input
                $selectedDate = isset($_GET['date']) ? $_GET['date'] : null;

                // Retrieve the counts for each status and date combination
                $pendingCountQuery = "SELECT COUNT(*) AS pendingCount FROM Purchase WHERE status='Pending' " . ($selectedDate ? "AND DATE(date)='$selectedDate'" : "");
                $acceptedCountQuery = "SELECT COUNT(*) AS acceptedCount FROM Purchase WHERE status='Accepted' " . ($selectedDate ? "AND DATE(date)='$selectedDate'" : "");
                $completedCountQuery = "SELECT COUNT(*) AS completedCount FROM Purchase WHERE status='Completed' " . ($selectedDate ? "AND DATE(date)='$selectedDate'" : "");
                $returnRefundCountQuery = "SELECT COUNT(*) AS returnRefundCount FROM Purchase WHERE status='Return/Refund' " . ($selectedDate ? "AND DATE(date)='$selectedDate'" : "");

                // Execute count queries
                $pendingCountResult = mysqli_query($dlink, $pendingCountQuery);
                $pendingCountRow = mysqli_fetch_assoc($pendingCountResult);
                $pendingCount = $pendingCountRow['pendingCount'];

                $acceptedCountResult = mysqli_query($dlink, $acceptedCountQuery);
                $acceptedCountRow = mysqli_fetch_assoc($acceptedCountResult);
                $acceptedCount = $acceptedCountRow['acceptedCount'];

                $completedCountResult = mysqli_query($dlink, $completedCountQuery);
                $completedCountRow = mysqli_fetch_assoc($completedCountResult);
                $completedCount = $completedCountRow['completedCount'];

                $returnRefundCountResult = mysqli_query($dlink, $returnRefundCountQuery);
                $returnRefundCountRow = mysqli_fetch_assoc($returnRefundCountResult);
                $returnRefundCount = $returnRefundCountRow['returnRefundCount'];


                // Retrieve orders from the Purchase table based on the status and date
                $query = "SELECT * FROM Purchase";

                // Add the date filter if a date parameter is provided
                if ($selectedDate) {
                    $query .= " WHERE DATE(date) = '$selectedDate'";
                }

                // Add the status filter if a status parameter is provided
                if (!empty($status)) {
                    if (strpos($query, 'WHERE') !== false) {
                        $query .= " AND status='$status'";
                    } else {
                        $query .= " WHERE status='$status'";
                    }
                }

                $result = mysqli_query($dlink, $query);

                // Display the table with the orders and ability to change the status
                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table id="customers-table">';
                    echo '<tr><th><a href="customers.php?status=pending' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Pending (' . $pendingCount . ')</a></th><th><a href="customers.php?status=accepted' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Accepted (' . $acceptedCount . ')</a></th><th><a href="customers.php?status=completed' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Completed (' . $completedCount . ')</a></th><th><a href="customers.php?status=return/refund' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Return/Refund (' . $returnRefundCount . ')</a></th></tr>';
                    echo '<tr><th>Product</th><th>Quantity</th><th>Description</th><th>Total</th><th>Date Ordered</th><th>Status</th></tr>';
                    $totalCost = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        // Retrieve product details from the Products table based on the prodid
                        $productId = $row['prodid'];
                        $productQuery = "SELECT * FROM Products WHERE prodid='$productId'";
                        $productResult = mysqli_query($dlink, $productQuery);
                        $product = mysqli_fetch_assoc($productResult);

                        // Calculate the cost for the current order
                        $orderCost = $product['ourprice'] * $row['quantity'];

                        // Add the order cost to the total cost
                        $totalCost += $orderCost;

                        echo '<tr>';
                        echo '<td><img src="' . $product['productimage'] . '" alt="' . $product['productname'] . '" style="max-width: 200px; max-height: 200px;"></td>';
                        echo '<td>' . $row['quantity'] . '</td>';
                        echo '<td>' . $product['productname'] . '<br>' . $product['productdesc'] . '</td>';
                        echo '<td>$' . number_format($product['ourprice'] * $row['quantity'], 2) . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td>';
                        echo '<form method="POST" action="update_status.php">';
                        echo '<input type="hidden" name="userid" value="' . $row['userid'] . '">';
                        echo '<input type="hidden" name="prodid" value="' . $row['prodid'] . '">';
                        echo '<input type="hidden" name="quantity" value="' . $row['quantity'] . '">';
                        echo '<input type="hidden" name="date" value="' . $row['date'] . '">';
                        echo '<select name="new_status" onchange="this.form.submit()">';
                        echo '<option value="Pending"' . ($row['status'] == 'Pending' ? ' selected' : '') . '>Pending</option>';
                        echo '<option value="Accepted"' . ($row['status'] == 'Accepted' ? ' selected' : '') . '>Accepted</option>';
                        echo '<option value="Completed"' . ($row['status'] == 'Completed' ? ' selected' : '') . '>Completed</option>';
                        echo '<option value="Return/Refund"' . ($row['status'] == 'Return/Refund' ? ' selected' : '') . '>Return/Refund</option>';
                        echo '</select>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';

                        mysqli_free_result($productResult);
                    }

                    echo '<tr><td></td><td colspan="2">Total Cost:</td><td id="total_cost">$' . number_format($totalCost, 2) . '</td>';
                    echo '</table>';
                } else {
                    echo '<table id="customers-table">';
                    echo '<tr><th><a href="customers.php?status=pending' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Pending (' . $pendingCount . ')</a></th><th><a href="customers.php?status=accepted' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Accepted (' . $acceptedCount . ')</a></th><th><a href="customers.php?status=completed' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Completed (' . $completedCount . ')</a></th><th><a href="customers.php?status=return/refund' . ($selectedDate ? '&date=' . $selectedDate : '') . '">Return/Refund (' . $returnRefundCount . ')</a></th></tr>';
                    echo '<tr><th>Product</th><th>Quantity</th><th>Description</th><th>Total</th><th>Date Ordered</th><th>Status</th></tr>';
                    echo '<tr>';
                    echo '<td><img src="' . $product['productimage'] . '" alt="' . $product['productname'] . '" style="max-width: 200px; max-height: 200px;"></td>';
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>' . $product['productname'] . '<br>' . $product['productdesc'] . '</td>';
                    echo '<td>$' . number_format($product['ourprice'] * $row['quantity'], 2) . '</td>';
                    echo '<td>' . $row['date'] . '</td>';
                    echo '<td>' . $row['status'] . '</td>';
                    echo '</tr>';
                    echo '</table>';
                }

                // Close the result set and the database connection
                mysqli_free_result($result);
            } elseif ($_COOKIE['type'] == 'customer') {
                // If the user is not an admin, display an error message or redirect them to the appropriate page
                echo 'You do not have permission to access this page.';
            }

            // Close the result set and the database connection for the user query
            mysqli_free_result($userResult);
            mysqli_close($dlink);
            ?>

            <div id="footer">
                <!-- <div>
                <div> -->
                <!-- <a href="index.html"
                        ><img
                            src="images/home.gif"
                            alt="Image"
                    />
                    <img
                            src="images/home.gif"
                            alt="Image"
                    />
                    </a> -->
            </div>
            <div>
                <!-- <form action="index.html">
                        <h5>Subscribe To Our Newsletter</h5>
                        <label for="newsletter">
                            <input
                                type="text"
                                id="newsletter"
                                value="Enter your Email Address" />
                        </label>
                        <input
                            type="submit"
                            id="subscribe"
                            value="Subscribe" />
                    </form>
                    <div>
                        <h5>Follow</h5>
                        <a
                            href="http://freewebsitetemplates.com/go/facebook/"
                            target="_blank"
                            id="facebook"
                            >Facebook</a
                        >
                        <a
                            href="http://freewebsitetemplates.com/go/twitter/"
                            target="_blank"
                            id="twitter"
                            >Twitter</a
                        >
                        <a
                            href="http://freewebsitetemplates.com/go/googleplus/"
                            target="_blank"
                            id="googleplus"
                            >Google&#43;</a
                        >
                    </div>
                </div> -->
                <!-- </div>
            <p class="footnote">Copyright &copy; Website name all rights reserved.</p>
        </div> -->
</body>

</html>