<?
if($_SERVER["REQUEST_METHOD"] === "POST") {
	if($_POST['req_uid']) {
		// re-setup things because we're just posting ajax to this file
		if(!isset($_SESSION)) {
			session_start();
			require('../php/db.php');
		}

		$id = $_SESSION['id'];
		$uid = $_POST['req_uid'];
		$value = in_array($_POST['value'], array(1, 2)) ? intval($_POST['value']) : 0;

		mysqli_query($db, "start transaction");
		$r1 = mysqli_query($db, "update requests r set r.status='$value'
								 where r.uid='$uid'
								 and r.faculty in (select faculty from approvers where id='$id')");
		$r2 = mysqli_query($db, "insert into drives(capacity, name, faculty)
								 select size, name, faculty from requests where uid='$uid'");
		$lastID = mysqli_insert_id($db);
		$r3 = mysqli_query($db, "insert into principals(id, drive) select user, '$lastID' from requests where uid='$uid'");

		if($r1 && $r2 && $r3) {
			mysqli_query($db, "commit");
			echo "commit";
		}
		else {
			mysqli_query($db, "rollback");
			var_dump($r1);
			var_dump($r2);
			var_dump($r3);
			echo "rollback";
		}
		exit();
	}
}

?>

<? $rq = Helpers::getFacultyRequests($faculty); if(!empty($rq)) { ?>
	<div class="ui tab active" data-tab="requests">
		<h3 class="ui header"><? echo Helpers::getReadableFaculty($faculty); ?> faculty requests</h3>

		<? foreach($rq as $request) { ?>
			<? $user = Helpers::getUser($request['user']); ?>
			<div class="ui dark segment request">
				<h4><? echo Helpers::out($request['name']); ?></h4>
				<b><? echo Helpers::out($user['id']) . ' - ' . Helpers::out($user['first']) . ' ' . Helpers::out($user['last']); ?></b> is requesting a
				<b><? echo intval($request['size']) / 1000; ?> GB</b> drive

				<? if($request['status'] == 0) { ?>
					<span class='status yellow'>This request is still pending</span>
					<div class="icons" value="<? echo $request['uid']; ?>">
						<i value="1" class="green large checkmark icon"></i>
						<i value="2" class="red large remove icon"></i>
					</div>
				<? } elseif($request['status'] == 1) { ?>
					<span class='status green'>This request has been approved</span>
				<? } elseif($request['status'] == 2) { ?>
					<span class='status red'>This request has been denied</span>
				<? } ?>
			</div>
		<? } ?>
	</div>
<? } ?>

<div class="ui tab drives" data-tab="drives">
	<? $d = Helpers::getFacultyDrives($faculty); if(!empty($d)) { ?>
		<h3 class="ui header"><? echo Helpers::getReadableFaculty($faculty); ?> faculty drives</h3>
		<? $used = 0; $reserved = 0; ?>

		<? foreach($d as $drive) { 
			$used += intval($drive['used']);
			$reserved += intval($drive['capacity']);
		?>
			<a class="ui dark segment drive">
				<h4><? echo Helpers::out($drive['name']); ?></h4>

				<b><? echo round(intval($drive['used'])/1000, 2) . ' / ' . round(intval($drive['capacity'])/1000, 2); ?> GB</b> -
				<b><? echo round(100 - intval($drive['used']) / intval($drive['capacity'])); ?>%</b> remaining
				
				<div class="role"><?
					$members = Helpers::countMembers($drive['uid']);
					echo $members . " researcher" . ($members>1?"s":""); 
				?></div>
			</a>
		<? } ?>

		<div class="tally">
			This faculty is using <b><? echo Helpers::niceNumber($used); ?></b> of its reserved <b><? echo Helpers::niceNumber($reserved); ?></b>
		</div>

	<? } else { ?>
		<div class="nodrives">
			<img class="flip ui image" src='img/zombie_dragon3.png' width='400' height='289'>
			<h3 class="ui header">there are no drives :(</h3>
		</div>
	<? } ?>
</div>

<script type="text/javascript">
	$('.request .icons > i').on('click', function() {
		$.ajax({
			url: "views/approver_content.php",
			type: "POST",
			data: {
				req_uid: $(this).parent().attr('value'),
				value: $(this).attr('value')
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