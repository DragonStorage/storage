<!doctype html>

<?
require('php/helpers.php');

// gotta be logged in to do stuff yo
if(!Helpers::loggedIn()) {
	header('location: ./');
	exit();
}

$error = false;
$error_message = "";
$error_header = 'Oops';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = Helpers::in($_POST['name']);
	$faculty = Helpers::in($_POST['faculty']);

	if(!$faculty || !in_array($faculty, array('pointy', 'round'), true)) {
		$error = true;
		$error_message = "Looks like you didn't select a faculty";
	}

	if(!isset($_POST['dragons'])) {
		$error = true;
		$error_header = 'Um';
		$error_message = "It appears you don't like dragons. Perhaps you should use a different site.";
	}

	if(!$error) {
		$id = $_SESSION['id'];

		$sql = "insert into requests(name, faculty, user)
				values('$name', '$faculty', '$id');";
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

			<form id='form-new' method='post' class='ui form segment'>
				<? if($error) { ?>
					<div class='ui error message <? if($error) { echo "show"; } ?>'>
						<div class='header'><? echo $error_header; ?></div>
						<p><? echo $error_message; ?></p>
					</div>
				<? } else { ?>
					<div class='ui message'>
						<div class='header'>Provision New Drive</div>
						<p>You even get a cool auto generated name! Deal with it.</p>
					</div>
				<? } ?>
				
				<div class='disabled field'>
					<div class='ui right labeled icon input'>
						<input type='text' name='name' value='<? echo Helpers::name(); ?>' readonly>
						<i class='hdd icon'></i>
					</div>
				</div>

				<div class='field'>
					<div class="ui dropdown selection">
						<input type="hidden" name="faculty">
						<div class="default text">Faculty</div>
						<i class="dropdown icon"></i>
						<div class="menu">
							<div class="item" data-value="pointy">Pointy</div>
							<div class="item" data-value="round">Round</div>
						</div>
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