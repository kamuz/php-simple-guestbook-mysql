<?php

error_reporting(-1);

// Print array
function dd( $array ) {
	echo '<pre>';
	print_r( $array );
	echo '</pre>';
}

// Create connection
$conn = mysqli_connect('localhost', 'root', 'root', 'guestbook');

// Check connection
if (mysqli_connect_errno()) {
	// When failed, how message with error
	echo 'Failed to connect to MySQL ' . mysqli_connect_errno();
}

// Set charset
mysqli_set_charset( $conn, 'utf8' );

// Save message to file
function save_message() {
	global $conn;

	// Get data from form
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$message = mysqli_real_escape_string($conn, $_POST['message']);

	// Create query
	$query = "INSERT INTO messages (`name`, `message`) VALUES ('$name', '$message')";

	// Run query
	mysqli_query( $conn, $query );
}

// Get messages from file
function get_messages() {
	global $conn;

	// Create Query
	$query = 'SELECT * FROM messages ORDER BY id DESC';

	// Get Result
	$result = mysqli_query( $conn, $query );

	// Fetch Data
	$posts = mysqli_fetch_all( $result, MYSQLI_ASSOC );

	return $posts;
}

if ( $_POST ) {
	save_message();
	header("Location: {$_SERVER['PHP_SELF']}" );
	exit;
}

$messages = get_messages();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP Test</title>
	<style>
		form {
			padding: 10px;
			margin-bottom: 10px;
			background-color: #eee;
		}
		label {
			display: block;
		}
		input[type="text"],
		textarea {
			outline: none;
			display: block;
			width: auto;
			margin-bottom: 10px;
			font-family: sans-serif;
			border: 1px solid #ccc;
			padding: 5px;
			width: calc(100% - 10px);
		}
		button {
			border: none;
			background-color: green;
			color: #fff;
			padding: 5px 10px;
			cursor: pointer;
		}
		.container {
			max-width: 500px;
			margin: auto;
		}
		.message {
			border: 1px solid #eee;
			padding: 10px;
			margin-bottom: 10px;
		}
		.meta {
			font-size: 90%;
			opacity: 0.5;
		}
	</style>
</head>
<body>
	<div class="container">
		<form action="index.php" method="post">
			<label for="name">Name:</label>
			<input type="text" name="name" id="name" placeholder="Your name" required>
			<label for="message">Message:</label>
			<textarea name="message" id="message" rows="5" placeholder="Say something" required></textarea>
			<div><button type="submit">Send</button></div>
		</form>
		<?php if ( ! empty( $messages ) ) : ?>
			<div class="messages">
				<?php foreach( $messages as $message ) : ?>
					<div class="message">
						<?php // dd( $message ); ?>
						<div class="meta"><strong><?php echo $message['name'] ?></strong> | <i>Published</i>: <?php echo $message['published'] ?></div>
						<div class="divider">-----</div>
						<div class="text"><?php echo nl2br( htmlspecialchars( $message['message'] ) ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</body>
</html>