<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>

<head>
	<meta charset="UTF-8" />
	<title>Products - Furniture Shop Web Template</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" href="styles.css">
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
					<a href="products.php">Products</a>
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
			<!-- this if for category pages -->
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
			//--------------------------------------------------------------------------------------------------------------
			// Generate category links
			echo "<ul>";
			foreach ($categories as $category) {
				echo "<li>";
				echo "<a href='admin_category.php?prodcat=" . urlencode($category) . "'>$category</a>";

				// Add delete button for the category
				echo "<button class='delete-category-btn' onclick='confirmDeleteCategory(\"" . urlencode($category) . "\")'>Delete</button>";

				// Add edit button for the category
				echo "<button class='edit-category-btn' onclick='toggleEditCategory(this)'>Edit</button>";

				echo "</li>";
			}
			echo '<br>';
			echo '<br>';
			//--------------------------------------------------------------------------------------------------------------
			// Add "New Category" button
			echo '<div class="category">';
			echo '<button id="new-category-btn" onclick="createNewCategory()">New Category</button>';
			echo '</div>';

			echo "</ul>";

			// Close the database connection
			mysqli_close($conn);
			?>
			<div>
				<!-- this is for the menu home page -->
				<?php
				$hostname = "localhost";
				$database = "Shopee";
				$db_login = "root";
				$db_pass = "";
				$dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

				// check if a category filter is set
				if (isset($_GET['category'])) {
					$category_filter = $_GET['category'];
					$query = "SELECT * FROM Products WHERE prodcat='$category_filter' ORDER BY prodid";
				} else {
					$query = "SELECT * FROM Products ORDER BY prodcat, prodid";
				}

				$result = mysqli_query($dlink, $query);
				$current_cat = '';

				while ($row = mysqli_fetch_assoc($result)) {

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
						echo '<select class="product-options" onchange="handleProductOptionChange(' . $row['prodid'] . ', this)">
								<option value="" selected>--------</option> <!-- Make the empty value option selected -->
								<option value="edit">Edit</option>
								<option value="insert">Insert</option>
								<option value="delete">Delete</option>
							</select>';

						if ($row['quantity'] > 0) {

						} else {
							echo "Out of stock";
						}

						echo "</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
				mysqli_close($dlink);
				?>
				<script>
					function openFormPopup() {
						document.getElementById('popup-container').style.display = 'flex';
					}

					function closeFormPopup() {
						document.getElementById('popup-container').style.display = 'none';
					}

					function handleProductOptionChange(prodid, selectElement) {
						var value = selectElement.value;

						if (value === "insert") {
							handleInsertProduct(prodid, selectElement);
						} else if (value === "delete") {
							handleDeleteProduct(prodid);
						} else if (value === "edit") {
							handleProductEdit(prodid);
						}
					}
					//--------------------------------------------------------------------------------------------------------------
					//this serves as the functionality of the insert option from the dropdown
					function handleInsertProduct(prodid, selectElement) { // Add selectElement as a parameter
						// Retrieve the category of the selected product
						var prodcat = selectElement.getAttribute("data-category");

						// Make an AJAX request to insert a new product
						var xhr = new XMLHttpRequest();
						xhr.open("POST", "insert_product.php", true);
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xhr.onreadystatechange = function () {
							if (xhr.readyState === 4 && xhr.status === 200) {
								// Insertion completed successfully
								// Reload the page
								location.reload();
							}
						};
						xhr.send("prodid=" + prodid + "&category=" + prodcat + "&option=insert");
					}
					//--------------------------------------------------------------------------------------------------------------
					// this deletes the products from the products.php
					function handleDeleteProduct(prodid) {
						var confirmationMessage = "Are you sure you want to delete 'Product " + prodid + "'?";

						if (confirm(confirmationMessage)) {
							// Make an AJAX request to delete the product
							var xhr = new XMLHttpRequest();
							xhr.open("POST", "delete_product.php", true);
							xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							xhr.onreadystatechange = function () {
								if (xhr.readyState === 4 && xhr.status === 200) {
									// Product deleted successfully, reload the page
									location.reload();
								}
							};
							xhr.send("prodid=" + prodid);
						} else {
							// Reset the select value to the default option
							selectElement.value = "";
						}
					}
					//--------------------------------------------------------------------------------------------------------------
					// this edits the products from the products.php
					// this will have the pop-up modal that when edit is being clicked from the dropdown 
					//the funtion edit will be activated
					function handleProductEdit(prodid) {
						// Show the edit form popup
						document.getElementById('popup-container').style.display = 'block';

						// Set the prodid value in the edit form
						document.getElementById('prodid').value = prodid;

						// Update the heading with the prodid
						var heading = document.getElementById('popup-heading');
						heading.textContent = 'Edit Product ' + prodid;

						// Retrieve the product details using AJAX
						var xhr = new XMLHttpRequest();
						xhr.open("GET", "get_product_details.php?prodid=" + prodid, true);
						xhr.onreadystatechange = function () {
							if (xhr.readyState === 4 && xhr.status === 200) {
								var productDetails = JSON.parse(xhr.responseText);

								// Set the values of the form fields
								document.getElementById('current-image').src = productDetails.productimage;
								document.getElementById('productname').value = productDetails.productname;
								document.getElementById('description').value = productDetails.description;
								document.getElementById('quantity').value = productDetails.quantity;
								document.getElementById('ourprice').value = productDetails.ourprice;
								document.getElementById('prodcat').value = productDetails.prodcat;

							}
						};
						xhr.send();
					}
					//--------------------------------------------------------------------------------------------------------------
					// function that handles the create "New Category" button
					function createNewCategory() {
						// Send an AJAX request to create a new category
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'create_category.php', true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						xhr.onload = function () {
							if (xhr.status === 200) {
								// Reload the page after creating a new category
								location.reload();
							}
						};
						xhr.send();
					}
					//--------------------------------------------------------------------------------------------------------------
					//this function will serve as the activation for the delete button 
					function confirmDeleteCategory(categoryId) {
						var confirmationMessage = "Are you sure you want to delete the category '" + categoryId + "' and its products?";

						if (confirm(confirmationMessage)) {
							// Make an AJAX request to delete the category
							var xhr = new XMLHttpRequest();
							xhr.open("POST", "delete_category.php", true);
							xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							xhr.onreadystatechange = function () {
								if (xhr.readyState === 4 && xhr.status === 200) {
									// Category deleted successfully, reload the page
									location.reload();
								}
							};
							xhr.send("categoryId=" + categoryId);
						}
					}
					//--------------------------------------------------------------------------------------------------------------
					function toggleEditCategory(editButton) {
						var listItem = editButton.parentNode;

						var saveButton = document.createElement('button');
						saveButton.className = 'save-category-btn';
						saveButton.textContent = 'Save';
						saveButton.addEventListener('click', function () {
							var category = listItem.querySelector('a').textContent;
							var inputField = listItem.querySelector('input');
							var newCategoryName = inputField.value;
							saveCategory(category, newCategoryName);
						});

						var inputField = document.createElement('input');
						inputField.type = 'text';
						inputField.value = listItem.querySelector('a').textContent;

						listItem.replaceChild(inputField, editButton);
						listItem.appendChild(saveButton);
					}
					//--------------------------------------------------------------------------------------------------------------
					//save function category from the edit button
					//save function category from the edit button
					function saveCategory(category, newCategoryName) {
						var xhr = new XMLHttpRequest();
						var url = 'update_category.php';
						var params = 'category=' + encodeURIComponent(category) + '&newCategoryName=' + encodeURIComponent(newCategoryName);

						xhr.open('POST', url, true);
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

						xhr.onreadystatechange = function () {
							if (xhr.readyState === XMLHttpRequest.DONE) {
								if (xhr.status === 200) {
									var response = xhr.responseText;
									console.log('Response from server:', response); // Log the response received from the server
									if (response === 'success') {
										console.log('Category name updated successfully');
										location.reload(); // Reload the page
									} else {
										// Handle error case
										console.log('Failed to update category name');
									}
								} else {
									// Handle error case
									console.log('Failed to send request');
								}
							}
						};

						xhr.send(params);
					}

				</script>
			</div>
		</div>
	</div>
	<div id="popup-container" style="display: none;">
		<div id="popup-window">
			<div class="modal-content">
				<button type="button" class="close" onclick="closeFormPopup()">&times;</button>
				<div>
					<div class="row text-center">
						<h1 id="popup-heading">Edit Product</h1>
						<hr>
					</div>
					<form action="update_product.php" method="post" id="edit-form" enctype="multipart/form-data"
						onsubmit="handleProductUpdate(event)">
						<input type="hidden" id="prodid" name="prodid">
						<div class="row">

							<div class="col-md-6">
								<label for="prodcat"></label>
								<input class="form-control" name="prodcat" id="prodcat" placeholder="Product Category">
							</div>
							<div class="col-md-6">
								<label for="productname"></label>
								<input class="form-control" name="productname" id="productname"
									placeholder="Product Name">
							</div>
							<div class="col-md-6">
								<label for="description"></label>
								<input class="form-control" name="description" id="description"
									placeholder="Description">
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<label for="quantity"></label>
								<input class="form-control" name="quantity" id="quantity" placeholder="Quantity"
									type="number" min="0">
							</div>
							<div class="col-md-6">
								<label for="ourprice"></label>
								<input class="form-control" name="ourprice" id="ourprice" placeholder="Current Price">
							</div>
						</div>
						<br>
						<div class="row">
							<label for="image">Current Product Image:</label>
							<img id="current-image" src="' . $row['productimage'] . '" width="100">
						</div>
						<div class="col-md-6">
							<label for="image">Edit Product Image:</label>
							<input type="file" name="image" id="image" accept="image/*">
						</div>

				</div>
				<br>
				<center>
					<input type="submit" class="btn btn-primary" name="submit" value="Update">
				</center>
				</form>
				<br>
			</div>
		</div>
	</div>
	<!-- <div id="footer">
				<div>
					<div>
						<a href="index.html"><img src="images/home.gif" alt="Image" />
							<img src="images/home.gif" alt="Image" />
						</a>
					</div>
					<div>
						<form action="index.html">
							<h5>Subscribe To Our Newsletter</h5>
							<label for="newsletter">
								<input type="text" id="newsletter" value="Enter your Email Address" />
							</label>
							<input type="submit" id="subscribe" value="Subscribe" />
						</form>
						<div>
							<h5>Follow</h5>
							<a href="http://freewebsitetemplates.com/go/facebook/" target="_blank"
								id="facebook">Facebook</a>
							<a href="http://freewebsitetemplates.com/go/twitter/" target="_blank"
								id="twitter">Twitter</a>
							<a href="http://freewebsitetemplates.com/go/googleplus/" target="_blank"
								id="googleplus">Google&#43;</a>
						</div>
					</div>
				</div>
			</div>
			<p class="footnote">Copyright &copy; Website name all rights reserved.</p> -->
	<!-- </div>
	</div> -->
	<!-- <div id="footer">
			 <div>
				<div>
					<a href="index.html"><img src="images/home.gif" alt="Image" /></a>
					<h4>Place holder</h4>
					<p>This is just a place holder</p>
				</div>
				<div>
					<form action="index.html">
						<h5>Subscribe To Our Newsletter</h5>
						<label for="newsletter">
							<input type="text" id="newsletter" value="Enter your Email Address" />
						</label>
						<input type="submit" id="subscribe" value="Subscribe" />
					</form>
					<div>
						<h5>Follow</h5>
						<a href="http://freewebsitetemplates.com/go/facebook/" target="_blank"
							id="facebook">Facebook</a>
						<a href="http://freewebsitetemplates.com/go/twitter/" target="_blank" id="twitter">Twitter</a>
						<a href="http://freewebsitetemplates.com/go/googleplus/" target="_blank"
							id="googleplus">Google&#43;</a>
					</div>
				</div>
			</div>
			<p class="footnote">Copyright &copy; Website name all rights reserved.</p> -->
	<!-- </div> -->

</body>

</html>