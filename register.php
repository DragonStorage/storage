<!doctype html>

<?
require('php/helpers.php');
require('php/lib/PasswordHash.php');

$hasher = new PasswordHash(8, false);
$error_message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	session_destroy(); // remove current login since they're making a new one
	session_start();

	$error = false;
	$error_message = 'You forgot your ';

	$first = Helpers::in($_POST['first']);
	$last = Helpers::in($_POST['last']);
	$id = Helpers::in($_POST['id']);
	$pass = Helpers::in($_POST['password']);

	if(!$first) {
		$error_message .= 'first name, ';
		$error = true;
	}

	if(!$last) {
		$error_message .= 'last name, ';
		$error = true;
	}

	if(!$id) {
		$error_message .= 'Curtin ID, ';
		$error = true;
	}

	if(!$pass) {
		$error_message .= 'password, ';
		$error = true;
	}

	$error_message = substr($error_message, 0, -2);

	if(!$error) {
		$pass = $hasher->HashPassword($pass);

		$sql = "select id from users where id='$id'";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);

		// user doesn't already exist
		if($row === NULL) {
			$sql = "insert into users(first, last, id, password)
					values('$first', '$last', '$id', '$pass')";
			$result = mysqli_query($db, $sql);

			if($result) {
				$_SESSION['loggedIn'] = true;
				$_SESSION['id'] = $id;
				$_SESSION['first'] = $first;
				$_SESSION['last'] = $last;

				header('location: ./');
				exit();
			} else {
				$error = true;
				$error_message = 'something broked :(';
			}
		} else {
			$error = true;
			$error_message = 'that ID already belongs to someone else';
		}
	}
}
?>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Register | Dragon Storage</title>
	<? require('views/common.php'); ?>
</head>
<body class="bg blue">
	<a href='./' class="home button">
		<i class="large left arrow icon"></i>
		<i class="bigger home icon"></i>
	</a>

	<div class="user form">
		<div class='panel'>
			<img class='ui flip image' src='img/cloud_dragon.png' width='228' height='247'>
			<h2>Welcome to <br>Dragon Storage</h2>
		</div>

		<form id='form-register' method='post' class='ui form segment'>
			<div class='ui error message <? if($error) { echo "show"; } ?>'>
				<div class='header'>Oops</div>
				<p><? echo $error_message; ?></p>
			</div>
			
			<div class='field'>
				<div class='ui right labeled icon input'>
					<input type='text' name='first' placeholder='first name'>
					<i class='user icon'></i>
				</div>
			</div>

			<div class='field'>
				<div class='ui right labeled icon input'>
					<input type='text' name='last' placeholder='last name'>
					<i class='user icon'></i>
				</div>
			</div>

			<div class='field'>
				<div class='ui right labeled icon input'>
					<input type='text' name='id' placeholder='curtin id'>
					<i class='user icon'></i>
				</div>
			</div>

			<div class='field'>
				<div class='ui right labeled icon input'>
					<input type='password' name='password' placeholder='password'>
					<i class='lock icon'></i>
				</div>
			</div>

			<div class='pull-right'>
				<a href='login' class='ui small button'>Login</a>
				<input type='submit' class='ui small dark blue button' value='Register'></input>
			</div>
		</form>
	</div>
</body>
</html>