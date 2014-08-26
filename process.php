<?php 
session_start();
include('include/connection.php');


if (isset($_POST['action']) && ($_POST['action'] == 'register')) {
	register_user($connection, $_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'login')) {
	login_user($connection, $_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'send')) {
	post_message($connection, $_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'comment')) {
	post_comment($connection, $_POST);
}

else {
	session_destroy();
	header('Location: index.php');
	die();
}

function register_user($connection, $post) {
	$_SESSION['error_messages'] = array();

	if (empty($post['first_name']) || empty($post['last_name'])) {
		$_SESSION['error_messages'][] = 'Name cannot be blank';
	}
	if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['error_messages'][] = 'Email is not valid';
	}
	if (strlen($post['password']) < 6) {
		$_SESSION['error_messages'][] = 'Password too short';
	}
	if ($post['password'] != $post['confirm_password']) {
		$_SESSION['error_messages'][] = 'Passwords do not match';
	}
	if (count($_SESSION['error_messages']) > 0) {
		$_SESSION['error_messages'][] = "Unable to validate information";
	}
	else {
		$esc_first_name= htmlentities(mysqli_real_escape_string($connection, $post['first_name']));
		$esc_last_name = htmlentities(mysqli_real_escape_string($connection, $post['last_name']));
		$esc_password = htmlentities(mysqli_real_escape_string($connection, $post['password']));
		$esc_email= htmlentities(mysqli_real_escape_string($connection, $post['email']));
		

		$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
							VALUES ('{$esc_first_name}','{$esc_last_name}','{$esc_password}','{$esc_email}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success_message'][] = "User successfully created!";
	}
	header("Location: index.php");
	die();
}

function login_user($connection, $post) {
	$esc_password = htmlentities(mysqli_real_escape_string($connection, $post['password']));
	$esc_email= htmlentities(mysqli_real_escape_string($connection, $post['email']));
	$query = "SELECT * FROM users
						WHERE users.password = '{$esc_password}' AND users.email = '{$esc_email}'";
	$user = fetch_all($query);

	if (count($user) > 0) {
		$_SESSION['user_id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['last_name'] = $user[0]['last_name'];
		$_SESSION['logged_in'] = true;
		header("Location: success.php?id=" . $_SESSION['user_id']);
		die();
	}

	else {
		$_SESSION['error_messages'][] = "Cannot find user";
		header('Location: index.php');
		die();
	}
}

function post_message($connection, $post) {
	$esc_message = htmlentities(mysqli_real_escape_string($connection, $post['message']));
	$query = "INSERT INTO messages (message, created_at, updated_at, users_id) 
						VALUES ('{$esc_message}', NOW(), NOW(), '{$_SESSION['user_id']}')";
	run_mysql_query($query);
	
	header("Location: success.php");
}

function post_comment($connection, $post) {
	$esc_message = htmlentities(mysqli_real_escape_string($connection, $post['comment-content']));
	$query = "INSERT INTO comments (comment, created_at, updated_at, messages_id, users_id)
						VALUES ('{$esc_message}', NOW(), NOW(), '{$post['message_id']}', '{$_SESSION['user_id']}')";
	run_mysql_query($query);
	header('location: success.php');
}
?>




