<?php
// Check for POST data
if (count($_POST) < 1) {
    header('HTTP/1.0 404 Not Found'); // Generate 404
	exit();
} else {
	header('Cache-Control: no-cache, private'); // Disable caching
	header('Content-Type: application/json'); // Serving JSON
}

// Form values
$name = $_POST['name'];
$email = $_POST['email'];
$timeframe = $_POST['timeframe'];
$budget = $_POST['budget'];
$message = $_POST['message'];
$errors = array();

function clean($data) {
	return trim(stripslashes(strip_tags($data))); // Escape data
}

// Validation
if (strlen($name) < 2) {
	$errors[] = 'name';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors[] = 'email';
}

if (strlen($message) < 10) {
	$errors[] = 'message';
}

// Check for errors
if (count($errors) < 1) {
	// Build email message
	$mail = 'Name: ' . clean($name) . "\r\n";
	$mail .= 'Email: ' . $email . "\r\n";
	$mail .= 'Timeframe: ' . clean($timeframe) . "\r\n";
	$mail .= 'Budget: ' . clean($budget) . "\r\n";
	$mail .= 'Message: ' . clean($message) . "\r\n\r\n";
	$mail .= 'IP Address: ' . $_SERVER['REMOTE_ADDR'] . "\r\n";
	$mail .= 'Browser: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";

	// Send
	if (mail('me@jordanowen.co.uk', 'Design Request from ' . clean($name), $mail, "From: postmaster@jordanowen.co.uk\r\nReply-To: " . $email)) {
		echo(json_encode(array('status' => 'ok')));
		exit();
	} else {
		$errors[] = 'sending';
	}
}

// Show errors
echo(json_encode(array('status' => 'error', 'errors' => $errors)));
