<!doctype html>

<?
require('php/helpers.php');
require('php/lib/PasswordHash.php');

$error_message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = false;
	$error_message = 'You forgot your ';

	$hasher = new PasswordHash(8, false);

	$id = Helpers::in($_POST['id']);
	$pass = Helpers::in($_POST['password']);

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
		$sql = "select * from users where id='$id'";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);

		if($row === NULL) {
			$error = true;
			$error_message = 'Invalid login.'; // no user
		} else {
			$hash = $row['password'];

			if($hasher->CheckPassword($pass, $hash)) {
				$_SESSION['loggedIn'] = true;
				$_SESSION['id'] = $row['id'];
				$_SESSION['first'] = $row['first'];
				$_SESSION['last'] = $row['last'];
				$_SESSION['admin'] = $row['admin'];

				header('location: ./');
				exit();
			} else {
				$error = true;
				$error_message = 'Invalid login.'; // bad password
			}
		}
	}
}
?>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Login</title>
	<? require('php/common.php'); ?>

	<style>
		body { background-color: #9CBCDE; }
	</style>
</head>
<body>
	<a href='./' class="home">
		<i class="large left arrow icon"></i>
		<i class="bigger home icon"></i>
	</a>

	<div class="form container">
		<div class='form panel'>
			<img class='ui image' src='img/cloud_dragon.png' width='227' height='227'>
			<h2>Welcome to <br>Some Cool Name</h2>
		</div>

		<form id='form-login' method='post' class='ui form segment'>
			<div class='ui error message <? if($error) { echo "show"; } ?>'>
				<div class='header'>Oops</div>
				<p><? echo $error_message; ?></p>
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
				<a href='register' class='ui small button'>Register</a>
				<input type='submit' class='ui small dark blue button' value='Login'></input>
			</div>
		</form>
	</div>
</body>
</html>
