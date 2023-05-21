<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
<style>
	.modal-container {
		display: none;
		position: fixed;
		z-index: 9990;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		/* Semi-transparent black background */
	}

	.modal-container.open {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.modal-content {
		background-color: #D35400;
		color: #fff;
		max-width: 1000px;
		margin: auto;
		padding: 80px;
		box-sizing: border-box;
		text-align: center;
		position: relative;
		top: 10%;
		border-radius: 8px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	}

	.modal-content input {
		height: 40px;
		border-radius: 4px;
		border: none;
		padding: 8px;
		margin-bottom: 10px;
	}

	.modal-content button {
		padding: 8px 16px;
		background-color: #EF6C00;
		border: none;
		color: white;
		border-radius: 4px;
		cursor: pointer;
		transition: background-color 0.3s ease;
	}

	.modal-content button:hover {
		background-color: #E65100;
	}

	.modal-content .close {
		margin-right: 10px;
		margin-top: 5px;
		color: #fff;
		opacity: 0.8;
		position: absolute;
		top: 20px;
		right: 20px;
		font-size: 24px;
		cursor: pointer;
	}

	/* Add the rest of your CSS styles for chatContainer, message, inputContainer, messageInput, and sendButton here */
	#chatContainer {
		height: 400px;
		overflow-y: scroll;
		padding: 10px;
		background-color: #F57C00;
		border-radius: 8px;
		margin-bottom: 20px;
	}

	.message {
		background-color: #D35400;
		padding: 10px;
		margin-bottom: 10px;
		border-radius: 8px;
		color: #fff;
	}

	#inputContainer {
		padding: 10px;
		background-color: #FF9800;
		border-radius: 8px;
		margin-bottom: 20px;
		display: flex;
	}

	#messageInput {
		flex: 1;
		padding: 8px;
		border: none;
		border-radius: 4px;
		margin-right: 5px;
	}

	#sendButton {
		padding: 8px 16px;
		background-color: #EF6C00;
		border: none;
		color: white;
		border-radius: 4px;
		cursor: pointer;
		transition: background-color 0.3s ease;
	}

	#sendButton:hover {
		background-color: #E65100;
	}
</style>


<head>
	<meta charset="UTF-8">
	<title>Furniture Shop Web Template</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<div id="header">
		<a href="index.php"><img src="images/logo.gif" alt="Image"></a>
		<div>
			<ul>
				<!-- Homepage Login Functionality ------------------------------------------------------------->
				<!-- will ouput welcome + usertype -->
				<li class="current"><a href="index.php">Home</a></li>
				<!-- Homepage Login Functionality ------------------------------------------------------------->
				<!-- to check whether admin or customer is signed in  ---------------------------->
				<?php
				if (isset($_COOKIE['type'])) {
					if ($_COOKIE['type'] == 'admin') {
						echo '<li><a href="calendar.php">Calendar</a></li>';
						echo '<li><a href="products.php">Products</a></li>';
						echo '<li><a href="#" onclick="openFormPopup()">Open Chat</a></li>';
					} elseif ($_COOKIE['type'] == 'customer') {
						echo '<li><a href="menu.php">Menu</a></li>';
						echo '<li><a href="cart.php">Cart</a></li>';
						echo '<li><a href="myorders.php">My Orders</a></li>';
						echo '<li><a href="#" onclick="openFormPopup()">Open Chat</a></li>';

					}
				}
				?>
				<?php if (!isset($_COOKIE['email'])): ?>
				<?php else: ?>
					<li><a href="logout.php">Logout</a></li>
				<?php endif ?>
				<!-- to check whether admin or customer is signed in  ---------------------------->
				<li><a href="about.php">About</a></li>
				<li><a href="contact.html">Contact</a></li>
			</ul>
		</div>
	</div>
	<div id="body">

		<div id="login_form">
			<!-- for login function wherein Welcome,[type == customer or admin ] ------------------------------------------------------->
			<?php if (!isset($_COOKIE['email'])): ?>
			<?php else: ?>
			<li>
				<h1>Welcome,
					<?php echo $_COOKIE['type'] . '  ' . $_COOKIE['email'] . '' ?>
				</h1>
			</li>
			<li><a href="logout.php">Logout</a></li>
			<?php endif ?>
			<!-- for login function wherein Welcome,[type == customer or admin ] ------------------------------------------------------->

			<!-- for database connection ------------------------------------------------------------------>
			<?php
			$hostname = "localhost";
			$database = "Shopee";
			$db_login = "root";
			$db_pass = "";

			$dlink = mysql_connect($hostname, $db_login, $db_pass) or die("Could not connect");
			mysql_select_db($database) or die("Could not select database");
			// for database connection-------------------------------------------------------------------
			
			// Registration Function-------------------------------------------------------------------------------------------- 
			if ($_REQUEST['name'] != "" && $_REQUEST['email'] != "" && $_REQUEST['password'] != "" && $_REQUEST['contact'] != "" && $_REQUEST['address'] != "") {
				$query = "SELECT * FROM user WHERE email='" . $_REQUEST['email'] . "'";
				$result = mysql_query($query) or die(mysql_error());
				$num_results = mysql_num_rows($result);

				if ($num_results == 0) {
					// Check if this is the first registered user
					$query = "SELECT * FROM user";
					$result = mysql_query($query) or die(mysql_error());
					$num_results = mysql_num_rows($result);

					$user_type = 'customer';

					if ($num_results == 0) {
						// First registered user is admin
						$user_type = 'admin';
					}

					$query = "INSERT INTO user(email, paswrd, contact, custname, address, usertype, user_date, user_ip) VALUES('" . $_REQUEST['email'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['contact'] . "', '" . $_REQUEST['name'] . "' ,'" . $_REQUEST['address'] . "', '" . $user_type . "', '" . date("Y-m-d h:i:s") . "', '" . $_SERVER['REMOTE_ADDR'] . "')";
					$result = mysql_query($query) or die(mysql_error());
					echo "<meta http-equiv='refresh' content='0;url=index.php?action=login&#login_form'>";
				} else {
					echo "<meta http-equiv='refresh' content='0;url=index.php?registered=user&register=true&#register'>";
					echo '<script>alert("Account Already Registered")</script>';
				}
			}
			// Registration Function-------------------------------------------------------------------------------------------- 
			
			// Login Function-------------------------------------------------------------------------------------------- 
			if ($_REQUEST['logging_in'] == true) {
				$query = "select * from user where email='" . $_REQUEST['email'] . "' and paswrd='" . $_REQUEST['password'] . "'";
				$result = mysql_query($query) or die(mysql_error());
				$total_results = mysql_num_rows($result);
				if ($total_results == 0) {
					echo '<meta http-equiv="refresh" content="0;url=index.php?action=register&#login_form">';
				} else {
					$row = mysql_fetch_array($result);
					setcookie("email", $row['email'], time() + 3600);
					setcookie("type", $row['usertype'], time() + 3600);
					echo '<meta http-equiv="refresh" content="0,url=index.php?user=logged_in">';
				}
			}
			// Login Function-------------------------------------------------------------------------------------------- 
			
			// Register Form-------------------------------------------------------------------------------------------- 
			if ($_REQUEST['action'] == 'register') {
				print('<form action=index.php method=post>');
				print('<h1>Registration Form</h1>');
				print('Enter Name<input type=text name=name><br>');
				print('Enter Email<input type=text name=email><br>');
				print('Enter Password<input type=text name=password><br>');
				print('Enter Contact<input type=text name=contact><br>');
				print('Enter Address<input type=text name=address><br>');
				print('<input type=submit value=submit>');
				print('</form>');
			}
			// Register Form-------------------------------------------------------------------------------------------- 
			
			// Login Form-------------------------------------------------------------------------------------------- 
			if ($_REQUEST['action'] == 'login') {
				print('<form action=index.php?logging_in=true method=post>');
				print('<h1>Login Form</h1>');
				print('Enter Email<input type=text name=email><br>');
				print("Enter Password<input type=text name=password><br>");
				print('<input type=submit value=submit name=submit>');
				print('</form>');
			}
			// Login Form-------------------------------------------------------------------------------------------- 
			
			?>
			<?php
			if ($_REQUEST['user'] != "logged_in") {
				echo '<li class="nav-item"><a class="nav-link" href="index.php?action=login&#login_form">Login</a></li>';
				echo '<li class="nav-item"> <a class="nav-link" href="index.php?action=register&#login_form">Register</a></li>';
			} else if ($_REQUEST['user'] == "logged_in") {
				// echo '<li><a href="#">Welcome! </a></li>';
			}
			?>
		</div>

		<div id="figure"><a href="menu.php"><img src="images/dining.jpg" alt="Image"></a><a href="products.html"><img
					src="images/bed.jpg" alt="Image"></a></div>
		<div class="section">
			<div>
				<h1>The place holder</h1>
				<p>
					This website template has been designed by <a href="http://www.freewebsitetemplates.com/">Free
						Website Templates</a> for you, for free. You can replace all this text with your own text.
				</p>
				<p>
					You can remove any link to our website from this website template, you're free to use this website
					template without linking back to us.
				</p>
				<p>
					If you're having problems editing this website template, then don't hesitate to ask for help on the
					<a href="http://www.freewebsitetemplates.com/forums/">Forums</a>.
				</p>
			</div>
			<ul>
				<li>
					<a href="menu.php"><img src="images/room.jpg" alt="Image"></a>
					<h2>This is just a place holder</h2>
					<p>
						Amet ipsum urna, eget luctus neque. Lorem ipsum dolor sit amet
					</p>
				</li>
				<li>
					<a href="menu.php"><img src="images/bathtub.jpg" alt="Image"></a>
					<h2>This is just a place holder</h2>
					<p>
						Sedeget cursusnisi. Condiment um mi eu augue volutpa
					</p>
				</li>
			</ul>
		</div>
	</div>
	<div id="popup-container" class="modal-container" style="display: none;">
		<div id="popup-window" class="modal-content">
			<button type="button" class="close" onclick="closeFormPopup()">&times;</button>
			<div id="chatContainer"></div>
			<div id="inputContainer">
				<input type="text" id="messageInput" placeholder="Type a message...">
				<button id="sendButton">Send</button>
			</div>
		</div>
	</div>
	<div id="footer">
		<div>
			<div>
				<a href="index.html"><img src="images/home.gif" alt="Image"></a>
				<h4>Place holder</h4>
				<p>
					This is just a place holder
				</p>
			</div>
			<div>
				<form action="index.html">
					<h5>Subscribe To Our Newsletter</h5>
					<label for="newsletter">
						<input type="text" id="newsletter" value="Enter your Email Address">
					</label>
					<input type="submit" id="subscribe" value="Subscribe">
				</form>
				<div>
					<h5>Follow</h5>
					<a href="http://freewebsitetemplates.com/go/facebook/" target="_blank" id="facebook">Facebook</a>
					<a href="http://freewebsitetemplates.com/go/twitter/" target="_blank" id="twitter">Twitter</a>
					<a href="http://freewebsitetemplates.com/go/googleplus/" target="_blank"
						id="googleplus">Google&#43;</a>
				</div>
			</div>
		</div>
		<p class="footnote">
			Copyright &copy; Website name all rights reserved.
		</p>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			var chatContainer = document.getElementById("chatContainer");
			var messageInput = document.getElementById("messageInput");
			var sendButton = document.getElementById("sendButton");

			// Function to generate chat message HTML
			function generateMessageHTML(sender, message, timestamp, userType) {
				var userLabel = sender === "Admin" ? "" : "Customer";
				var messageElement = document.createElement("div");
				messageElement.classList.add("message");
				messageElement.innerHTML = `
			<div><strong>${userLabel} ${sender}</strong></div>
			<div>${message}</div>
			<div>${timestamp}</div>
			<button class="reply-button" data-recipient="${sender}">Reply</button>
		`;
				return messageElement;
			}

			// Function to handle reply button click
			function handleReplyClick(event) {
				var recipient = event.target.dataset.recipient;
				var replyInput = document.getElementById("messageInput");
				replyInput.value = "";
				replyInput.placeholder = "Reply to " + recipient;
				replyInput.dataset.recipient = recipient; // Set the recipient value on the dataset
			}


			// Attach event listener to reply buttons
			chatContainer.addEventListener("click", function (event) {
				if (event.target.classList.contains("reply-button")) {
					handleReplyClick(event);
				}
			});

			// Event listener for sending a message
			sendButton.addEventListener("click", function () {
				var newMessage = messageInput.value;
				var recipient = messageInput.dataset.recipient; // Get the recipient from the messageInput dataset

				if (newMessage.trim() !== "" && recipient) {
					// Send the message to the server
					var formData = new FormData();
					formData.append("message", newMessage);
					formData.append("recipient", recipient);

					fetch("insert_chat_data.php", {
						method: "POST",
						body: formData,
					})
						.then(function (response) {
							return response.json();
						})
						.then(function (data) {
							// Successfully inserted the message, update chat messages
							getChatMessages();
						})
						.catch(function (error) {
							console.log("Error sending chat message:", error);
						});

					messageInput.value = "";
					messageInput.dataset.recipient = ""; // Clear the recipient after sending the message
				}
			});

			// Function to render chat messages
			function renderChatMessages(messages) {
				chatContainer.innerHTML = "";
				for (var i = 0; i < messages.length; i++) {
					var { sender, message, timestamp } = messages[i];
					var messageHTML = generateMessageHTML(sender, message, timestamp);
					chatContainer.appendChild(messageHTML);
				}
			}

			// Function to retrieve chat messages from the server
			function getChatMessages() {
				fetch("read_chat_data.php")
					.then(function (response) {
						return response.json();
					})
					.then(function (data) {
						renderChatMessages(data);
					})
					.catch(function (error) {
						console.log("Error fetching chat messages:", error);
					});
			}

			// Initial rendering of chat messages
			getChatMessages();
		});

		function openFormPopup() {
			document.getElementById('popup-container').style.display = 'flex';
		}

		function closeFormPopup() {
			document.getElementById('popup-container').style.display = 'none';
		}
	</script>
</body>

</html>