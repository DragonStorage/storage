<!doctype html>

<?
if(isset($_GET['drive'])) {
	require('./drive.php');
	exit();
}

require('php/helpers.php');
?>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Dragon Storage</title>
	<? require('views/common.php'); ?>
</head>

<body class="bg grey">
	<div class="ui one column page grid">
		<? require('views/navbar.php'); ?>

		<? if(Helpers::loggedIn()) { ?>
			<? if($faculty = Helpers::getApprover()) { ?>
				<div class="ui grid">
					<div class="three wide column">
						<? require('views/approver_side.php'); ?>
					</div>
					<div class="twelve wide column">
						<? require('views/approver_content.php'); ?>
					</div>
				</div>
			<? } elseif(Helpers::hasDrives() || Helpers::hasRequests()) { ?>
				<div class="ui grid">
					<div class="three wide column">
						<? require('views/user_side.php'); ?>
					</div>
					<div class="twelve wide column">
						<? require('views/user_content.php'); ?>
					</div>
				</div>
			<? } else { ?>
				<div class="ui centered dark compact icon message">
					<i class="hdd icon"></i>
					<div class="content">
						<div class="header">Well</div>
						<p>It appears you are not currently a member of any storage drives, to fix that try some of the following things!</p>
						<ul class="list">
							<li><a href="new">Provision</a> your own drive and become a Principal Investigator</li>
							<li>Ask someone in your research team to add you to their disk</li>
						</ul>
					</div>				
				</div>
			<? } ?>
		<? } else { ?>
			<div>not logged in</div>
		<? } ?>
	</div>

	<? require('views/js.php'); ?>
</body>
</html>