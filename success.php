<?php 

	session_start();

	include('include/connection.php');
	$query = "SELECT users.first_name, users.last_name, messages.message, messages.users_id, messages.created_at, messages.id
						 FROM messages 
						 LEFT JOIN users ON users.id = messages.users_id
						 ORDER BY created_at DESC";						 
	$msg_created = fetch_all($query);


	?>
<!doctype html>
<html>
<head>
	<meta charset='utf-8'>
	<title>CodingDojo Wall</title>
	<link rel="stylesheet" type="text/css" href="style/success.css">
</head>
<body>
	<div id='header'>
		<h1>CodingDojo Wall</h1>
		<h2> Welcome, <span id='underline'><?= $_SESSION['first_name'] ?></span></h2>
		<a href='process.php'>Log Off</a>
	</div>			

	<div id='container'>
		<div id='main'>
			<h2>Your Message Here:</h2>
			<form action='process.php' method='post'>
				<input type='hidden' name='action' value='send'>
				<textarea name="message"></textarea>
				<input type='submit' value="Post a message">
			</form>
		</div>

<?php foreach ($msg_created as $message) 
			{ 
			$sqldate = strtotime($message['created_at']);
			$date = date('F j, Y', $sqldate);
			$query = "SELECT comments.comment, comments.created_at, users.first_name, users.last_name
								FROM comments
								LEFT JOIN users ON comments.users_id = users.id
								WHERE {$message['id']} = comments.messages_id";
			$comments = fetch_all($query);
?>
		<div id='messages'>
			<h2><?= "{$message['first_name']} {$message['last_name']} - {$date}"?></h2>
			<p><?= $message['message'] ?></p>
			<div id='comment'>
<?php 	foreach ($comments as $comment)
				{ 
					$sqldate1 = strtotime($comment['created_at']);
					$date1 = date('F j, Y', $sqldate1); ?>
					<h3><?= "{$comment['first_name']} {$comment['last_name']} - {$date1}" ?></h3>
					<p><?= $comment['comment'] ?></p>
<?php		} ?>
				<form action="process.php?id=<?=$message['id']?>" method='post'>
					<h3 id='comment-title'>Post a comment</h3>
					<textarea name='comment'></textarea>
					<input type='hidden' name='action' value='comment'>
					<input type='submit' value='Post a comment'>
				</form>
			</div>
		</div>
<?php } ?>
	</div> <!-- end of container  -->


</body>
</html>