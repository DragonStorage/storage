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

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$add = Helpers::in($_POST['id']);

	if(($drive['role'] == 'principals' ||
		$drive['role'] == 'managers') &&
		!in_array($add, $members)) {
		$sql = "select * from users where id='$add';";
		$result = mysqli_query($db, $sql);
		$num = mysqli_num_rows($result);

		if($num) {
			$sql = "insert into researchers(id, drive)
					values('$add', '$driveID');";
			$result = mysqli_query($db, $sql);

			if($result) {
				header("Location: ./?drive=".$driveID);
				exit();
			}
		}
	}
}
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

							<? // if there's researchers show the promote demote thingo
								$researchers = array_filter($members, function($item) {
									return $item['role'] == 'researchers' || $item['role'] == 'managers';
								});

							if(!empty($researchers)) { ?>
								<div class="ui fluid accordion">
									<div class="title">
										<i class="dropdown icon"></i>
										Researchers
									</div>

									<div class="content">
										<table class="ui table segment">
											<thead><tr>
												<th>ID</th>
												<th>Name</th>
												<th>Role</th>
												<? if($drive['role'] == 'principals' || $drive['role'] == 'managers') { ?>
													<th>Grant/Revoke</th>
												<? } ?>
											</tr></thead>

											<tbody>
											<? foreach($researchers as $user) { ?>
												<tr>
													<td><? echo $user['id']; ?></td>
													<td><? echo Helpers::getUserName($user['id']); ?></td>
													<td><? echo Helpers::getReadableRole($user['role']); ?></td>

													<? if($drive['role'] == 'principals' || $drive['role'] == 'managers') { ?>
														<td>
															<? if($drive['role'] == 'principals') { ?>
																<div class="change" name="<? echo $user['id']; ?>"><?
																	if($user['role'] == 'managers') {
																		?><span>Demote</span><?
																	} else {
																		?><span>Promote</span>&nbsp;&nbsp;&nbsp;<span>Remove</span><?
																	}
																?></div>
															<? } else { ?>
																<div class="change" name="<? echo $user['id']; ?>"><?
																	if($user['role'] == 'researchers') {
																		?><span>Remove</span><?
																	}
																?></div>
															<? } ?>
														</td>
													<? } ?>
											<? } ?>
											</tbody>
										</table>
									</div>
								</div>
							<? } ?>
						</div>

						<? if($drive['role'] == 'principals' || $drive['role'] == 'managers') { ?>
							<form class="add" method="post">
								<div class="ui action input">
								  <input type="text" name="id" placeholder="User ID">
								  <button type="submit" class="ui small black button">Add User</button>
								</div>
							</form>
						<? } ?>

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
							<div class="more">
								<a href="./additional?drive=<? echo $driveID; ?>" class="ui small black button">More Space</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<? require('views/js.php'); ?>

	<script type="text/javascript">
		$('.detailed .change span').on('click', function() {
			$.ajax({
				url: "php/promote.php",
				type: "POST",
				data: {
					action: $(this).html(),
					id: $(this).parent().attr('name'),
					drive: <? echo $driveID; ?>
				},

				success: function(data) {
					if(data === 'commit')
						location.reload(); // lazy hack for ui update
					else {
						console.log(data);
					}
				}
			});
		});

		// move tally back up to the top, needed to be at the bottom for correct numbers
		$('.drives > .tally').insertAfter('.drives > h3.header');
	</script>
</body>
</html>