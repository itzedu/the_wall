<?php 
session_start();
include('include/connection.php');


if (isset($_POST['action']) && ($_POST['action'] == 'register')) {
	register_user($_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'login')) {
	login_user($_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'send')) {
	post_message($_POST);
}

elseif (isset($_POST['action']) && ($_POST['action'] == 'comment')) {

	post_comment($_POST, $_GET);
}

else {
	session_destroy();
	header('Location: index.php');
	die();
}

function register_user($post) {
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
		$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
							VALUES ('{$post['first_name']}','{$post['last_name']}','{$post['password']}','{$post['email']}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success_message'][] = "User successfully created!";
	}
	header("Location: index.php");
	die();
}

function login_user($post) {
	
	$query = "SELECT * FROM users
						WHERE users.password = '{$post['password']}' AND users.email = '{$post['email']}'";
	$user = fetch_all($query);
	// var_dump($user);
	// die();

	// if (crypt($post['password'], $user['password']) == $user['password']) {
	// 	header("Location: success.php");
	// }
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

function post_message($post) {
	$query = "INSERT INTO messages (message, created_at, updated_at, users_id) 
						VALUES ('{$post['message']}', NOW(), NOW(), '{$_SESSION['user_id']}')";
	run_mysql_query($query);
	
	header("Location: success.php");
}

function post_comment($post,$get) {
	$query = "INSERT INTO c
	boomments (comment, created_at, updated_at, messages_id, users_id)
						VALUES ('{$post['comment']}', NOW(), NOW(), '{$get['id']}', '{$_SESSION['user_id']}')";
	run_mysql_query($query);
	header('location: success.php');
}
?>