<!doctype html>

<?
require('php/helpers.php');

?>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Storage</title>
	<? require('php/common.php'); ?>
</head>

<body>
	<div class="ui one column page grid">
		<h1 class='ui center aligned header'>Some Name</h1>

		<div class="ui horizontal icon divider">
		  <i class="massive cloud icon"></i>
		</div>

		<? if(Helpers::loggedIn()) { ?>
			Sup <? echo Helpers::out($_SESSION['first']); ?>
			<a href="logout">logout</a>
		<? } else { ?>
			Sup stranger
			<a href="login">login</a>
			<a href="register">register</a>
		<? } ?>
	</div>
</body>
</html>