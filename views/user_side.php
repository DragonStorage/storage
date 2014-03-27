<div class="ui slim secondary vertical pointing menu tabs">
	<? $rq = Helpers::getRequests(); if(!empty($rq)) { ?>
		<a class="active item" data-tab="requests">
			<div class="ui small label"><? echo count($rq); ?></div>
			Requests
		</a>
	<? } ?>
	<? $dr = Helpers::getAll(); if(!empty($dr)) { ?>
		<a class="item" data-tab="all">
			<div class="ui small label"><? echo count($dr); ?></div>
			All drives
		</a>
	<? } ?>
	<? $p = Helpers::getP(); if(!empty($p)) { ?>
		<a class="item pad" data-tab="investigator">
			<div class="ui small label"><? echo count($p); ?></div>
			<div>Principal Investigator</div>
		</a>
	<? } ?>
	<? $d = Helpers::getD(); if(!empty($d)) { ?>
		<a class="item" data-tab="manager">
			<div class="ui small label"><? echo count($d); ?></div>
			Data Manager
		</a>
	<? } ?>
	<? $r = Helpers::getR(); if(!empty($r)) { ?>
		<a class="item" data-tab="researcher">
			<div class="ui small label"><? echo count($r); ?></div>
			Researcher
		</a>
	<? } ?>
		<a class="item wider" href="new">New</a>
</div>