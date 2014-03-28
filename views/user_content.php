<? $rq = Helpers::getRequests(); if(!empty($rq)) { ?>
	<div class="ui tab active" data-tab="requests">
		<h3 class="ui header">List of your requests</h3>

		<? foreach($rq as $request) { ?>
			<div class="ui dark segment request">
				<h4><? echo Helpers::out($request['name']); ?></h4>
				<? if($request['type'] == 0) { ?>
					<b><? echo intval($request['size']) / 1000; ?> GB</b> drive for the
					<b><? echo ucfirst($request['faculty']); ?></b> faculty.
				<? } else { ?>
					<b><? echo intval($request['size']) / 1000; ?> GB</b> of additional space for a
					<b><? echo ucfirst($request['faculty']); ?></b> faculty drive.
				<? } ?>

				<? if($request['status'] == 0) { ?>
					<span class='status yellow'>This request is still pending</span>
					<div class="icons">
						<i class="large trash icon"></i>
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

<? $dr = Helpers::getAll(); if(!empty($dr)) { ?>
	<div class="ui tab" data-tab="all">
		<h3 class="ui header">All drives you're a member of</h3>

		<? foreach($dr as $driveID) {
			$drive = Helpers::getDrive($driveID['drive']);
			$drive['role'] = $driveID['role'];

			require('views/drive_listing.php');
		} ?>	
	</div>
<? } ?>

<? $p = Helpers::getP(); if(!empty($p)) { ?>
	<div class="ui tab" data-tab="investigator">
		<h3 class="ui header">All drives you're a principal investigator of</h3>

		<? foreach($p as $driveID) {
			$drive = Helpers::getDrive($driveID['drive']);
			$drive['role'] = $driveID['role'];

			require('views/drive_listing.php');
		} ?>	
	</div>
<? } ?>

<? $d = Helpers::getD(); if(!empty($d)) { ?>
	<div class="ui tab" data-tab="manager">
		<h3 class="ui header">All drives you're a data manager of</h3>

		<? foreach($d as $driveID) {
			$drive = Helpers::getDrive($driveID['drive']);
			$drive['role'] = $driveID['role'];

			require('views/drive_listing.php');
		} ?>
	</div>
<? } ?>

<? $r = Helpers::getR(); if(!empty($r)) { ?>
	<div class="ui tab" data-tab="researcher">
		<h3 class="ui header">All drives you're a researcher of</h3>

		<? foreach($r as $driveID) {
			$drive = Helpers::getDrive($driveID['drive']);
			$drive['role'] = $driveID['role'];

			require('views/drive_listing.php');
		} ?>
	</div>
<? } ?>
