<div class="ui slim secondary vertical pointing menu tabs">
	<? $rq = Helpers::getFacultyRequests($faculty); if(!empty($rq)) { ?>
		<a class="active item" data-tab="requests">
			<div class="ui small label"><? echo count($rq); ?></div>
			Requests
		</a>
	<? } ?>

	<? $d = Helpers::getFacultyDrives($faculty); ?>
	<a class="item" data-tab="drives">
		<div class="ui small label"><? echo count($d); ?></div>
		Drives
	</a>
</div>