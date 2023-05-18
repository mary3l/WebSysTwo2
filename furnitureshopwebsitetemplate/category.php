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
				<li class="current">
					<a href="menu.php">Menu</a>
				</li>
				<li>
					<a href="cart.php">Cart</a>
				</li>
				<li>
					<a href="myorders.php">My Orders</a>
				</li>
				<li>
					<a href="about.php">About</a>
				</li>
				<li>
					<a href="contact.html">Contact</a>
				</li>
			</ul>
		</div>
	</div>
	<div id="body">
		<div id="products">
			<h2>Products</h2>
			<?php
			// Connect to the database
			$hostname = "localhost";
			$database = "Shopee";
			$db_login = "root";
			$db_pass = "";
			$conn = mysqli_connect($hostname, $db_login, $db_pass, $database);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}

			// Check if filter is set
			if (isset($_GET['prodcat']) && !empty($_GET['prodcat'])) {
				$prodcat = mysqli_real_escape_string($conn, $_GET['prodcat']);
				$sql = "SELECT * FROM Products WHERE prodcat = '$prodcat'";
			} else {
				// SQL query to select all rows from the 'Products' table
				$sql = "SELECT * FROM Products";
			}

			// Select all products from the database
			$result = mysqli_query($conn, $sql);

			// Initialize category array
			$categories = array();

			// Loop through rows and add categories to array
			while ($row = mysqli_fetch_assoc($result)) {
				$category = $row['prodcat'];
				if (!in_array($category, $categories)) {
					array_push($categories, $category);
				}
			}

			// Generate category links
			echo "<ul>";
			foreach ($categories as $category) {

				echo "<li>
                    <a href='category.php?prodcat=" . urlencode($category) . "'>$category</a></li>";
			}
			echo "</ul>";

			// Close the database connection
			mysqli_close($conn);
			?>
			<div>
				<?php
				// Connect to the database
				$hostname = "localhost";
				$database = "Shopee";
				$db_login = "root";
				$db_pass = "";
				$conn = mysqli_connect($hostname, $db_login, $db_pass, $database);
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}

				// Check if filter is set
				if (isset($_GET['prodcat']) && !empty($_GET['prodcat'])) {
					$prodcat = mysqli_real_escape_string($conn, $_GET['prodcat']);
					$sql = "SELECT * FROM Products WHERE prodcat = '$prodcat'";
				} else {
					// SQL query to select all rows from the 'Products' table
					$sql = "SELECT * FROM Products";
				}

				// Select all products from the database
				$result = mysqli_query($conn, $sql);

				// Generate HTML table
				echo "<table style='border-collapse: collapse;'>";
				echo "<tr style='border-bottom: 1px solid black;'><th style='padding: 10px; text-align: left;'>Product Image</th><th style='padding: 10px; text-align: left;'>Product Name</th><th style='padding: 10px; text-align: left;'>Price</th><th style='padding: 10px; text-align: left;'>Quantity</th><th style='padding: 10px; text-align: left;'></th></tr>";
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<tr style='border-bottom: 1px solid black;'>";
					echo "<td style='padding: 10px;'><img src=\"" . $row['productimage'] . "\" alt=\"Image\" style=\"max-width: 200px; max-height: 200px;\"></td>";
					echo "<td style='padding: 10px;'>" . $row['productname'] . "</td>";
					echo "<td style='padding: 10px;'>&#8369;" . $row['ourprice'] . "</td>";
					echo "<td style='padding: 10px;'>" . $row['quantity'] . "</td>";
					echo "<td style='padding: 10px;'>";

					if ($row['quantity'] > 0) {
						echo '<form method="get" action="cart.php">';
						echo '<input type="hidden" name="prodid" value="' . $row['prodid'] . '">';
						echo '<button type="submit">Add to Cart</button>';
						echo '</form>';
					} else {
						echo "Out of stock";
					}

					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				// Close the database connection
				mysqli_close($conn);
				?>

			</div>
			<div id="footer">
				<div>
					<div>
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
					</div>
					<p class="footnote">Copyright &copy; Website name all rights reserved.</p>
				</div>
</body>

</html>