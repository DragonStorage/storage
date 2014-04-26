<!doctype html>

<?
require('php/helpers.php');

$driveID = $_GET['drive'];

// redirect back home if the user isn't logged in or a member
// of the drive they're trying to view
if(!Helpers::loggedIn() || !Helpers::memberOf($driveID))
	header('location: ./');

$drive = Helpers::getDrive($driveID);

if(Helpers::pOf($driveID))
	$drive['role'] = 'principals';
elseif(Helpers::dOf($driveID))
	$drive['role'] = 'managers';
elseif(Helpers::rOf($driveID))
	$drive['role'] = 'researchers';

$members = Helpers::getMembers($driveID);
?>

<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Dragon Storage</title>
	<? require('views/common.php'); ?>

	<script src="js/raphael.2.1.0.min.js"></script>
	<script src="js/justgage.1.0.1.min.js"></script>
</head>

<body class="bg grey">
	<div class="ui one column page grid">
		<? require('views/navbar.php'); ?>

		<div class="ui grid">
			<div class="three wide column">
				<div class="ui slim secondary vertical pointing menu">
					<? $rq = Helpers::getRequests(); if(!empty($rq)) { ?>
						<a class="item" href="./#/requests">
							<div class="ui small label"><? echo count($rq); ?></div>
							Requests
						</a>
					<? } ?>
					<? $dr = Helpers::getAll(); if(!empty($dr)) { ?>
						<a class="item" href="./#/all">
							<div class="ui small label"><? echo count($dr); ?></div>
							All drives
						</a>
					<? } ?>
					<? $p = Helpers::getP(); if(!empty($p)) { ?>
						<a class="item pad" href="./#/investigator">
							<div class="ui small label"><? echo count($p); ?></div>
							<div>Principal Investigator</div>
						</a>
					<? } ?>
					<? $d = Helpers::getD(); if(!empty($d)) { ?>
						<a class="item" href="./#/manager">
							<div class="ui small label"><? echo count($d); ?></div>
							Data Manager
						</a>
					<? } ?>
					<? $r = Helpers::getR(); if(!empty($r)) { ?>
						<a class="item" href="./#/researcher">
							<div class="ui small label"><? echo count($r); ?></div>
							Researcher
						</a>
					<? } ?>
					<? if(Helpers::canCreate()) { ?>
						<a class="item wider" href="new">New</a>
					<? } ?>
				</div>
			</div>
			<div class="twelve wide column">
				<div class="ui dark segment detailed">
					<h2><? echo Helpers::out($drive['name']); ?></h2>
					<div class="role"><? echo Helpers::getReadableRole($drive['role']); ?></div>

					<div class="info">
						<div class="users">
							<div><?
								$count = count($members);

								echo "There " . ($count==1?"is":"are") . " currently <b>"
								. $count . "</b> researcher" . ($count==1?"":"s")
								. " associated with this drive."; 
							?></div>

							<div>
								The Principal Investigator is <b>
								<?
									foreach($members as $member) {
										if($member['role'] == "principals") {
											echo Helpers::out(Helpers::getUserName($member['id']));
											break;
										}
									}
								?></b>.
							</div>
						</div>

						<!-- do promote/demote and add/remove stuff here -->
						<div></div>

						<div class="stats">
							<div id="gage"></div>
							<script>
								var g = new JustGage({
									id: "gage",
									value: <? echo round($drive['used']/1000,2); ?>,
									min: 0,
									max: <? echo $drive['capacity']/1000; ?>,
									title: "Used (GB)",
									showInnerShadow: false,
									gagueColor: "#EBEBEB"
								});
							</script>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<? require('views/js.php'); ?>
</body>
</html>