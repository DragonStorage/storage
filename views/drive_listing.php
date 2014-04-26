<a href="./?drive=<? echo $drive['uid']; ?>" class="ui dark segment drive">
	<h4><? echo Helpers::out($drive['name']); ?></h4>

	<b><? echo round(intval($drive['used'])/1000, 2) . ' / ' . round(intval($drive['capacity'])/1000, 2); ?> GB</b> -
	<b><? echo 100 - round(intval($drive['used'])/1000 / intval($drive['capacity']/1000) * 100,2);
	//(round(100 - intval($drive['used'], 2) / intval($drive['capacity']))); ?>%</b> remaining
	
	<div class="role"><? echo $drive['role']; ?></div>
	<div class="faculty"><? echo Helpers::getReadableFaculty($drive['faculty']); ?></div>
</a>