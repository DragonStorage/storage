<!doctype html>

<?
require('php/helpers.php');

// gotta be logged in to do stuff yo
if(!Helpers::loggedIn() || !Helpers::canAdd($_GET['drive'])) {
	header('location: ./');
	exit();
}

$error = false;
$error_message = "";
$error_header = 'Oops';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$projectID = Helpers::in($_GET['drive']);
	$storageRequest = Helpers::in($_POST['storageRequest']);
	$reason = Helpers::in($_POST['reason']);

	if(!isset($_POST['dragons'])) {
		$error = true;
		$error_header = 'Um';
		$error_message = "It appears you don't like dragons. Perhaps you should use a different site.";
	}

	if(!$error) {
		$id = $_SESSION['id'];
		$sql = "insert into requests(drive, reason, user, type)
		values('$projectID', '$reason', '$id', 1);";
		$result = mysqli_query($db, $sql);

		if($result) {
			header('location: ./#/requests');
			exit();
		} else {
			echo 'some error';
		}
	}
}
?>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>New | Dragon Storage</title>
	<? require('views/common.php'); ?>
</head>
<body class="bg grey">
	<div class="ui one column page grid">
		<? require('views/navbar.php'); ?>
		<div class="drive form container">
			<div class='panel'>
				<img class='ui image' src='img/alpine_dragon.png' width='200' height='205'>
			</div>
			<form id='form-additional' method='post' class='ui form segment'>
				<? if($error) { ?>
				<div class='ui error message <? if($error) { echo "show"; } ?>'>
					<div class='header'><? echo $error_header; ?></div>
					<p><? echo $error_message; ?></p>
				</div>
				<? } else { ?>
				<div class='ui message'>
					<div class='header'>Additional Storage Request</div>
					<p>Submit your request with a good reason.</p>
				</div>
				<? } ?>
				<div class='field'>
					<div class='ui right labeled icon input'>
						<input type='text' name='storageRequest' placeholder="Size">                               
						<i class='hdd icon'></i>
					</div>
				</div>
				<div class='field'>
					<div class=''>
						<textarea name="reason" form="form-additional" placeholder="Reason"></textarea>
					</div>
				</div>
				<div class="field">
					<div class="ui checkbox">
						<input type="checkbox" id='dragons' name='dragons'>
						<label class='dark grey' for='dragons'>I like dragons</label>
					</div>
				</div>
				<div class='pull-right'>
					<input type='submit' class='ui small dark grey button' value='Create'></input>
				</div>
			</form>
		</div>
	</div>
	<? require('views/js.php'); ?>
</body>
</html>


