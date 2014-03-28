<a class="ui dark segment drive">
	<h4><? echo Helpers::out($drive['name']); ?></h4>

	<b><? echo round(intval($drive['used'])/1000, 2) . ' / ' . round(intval($drive['capacity'])/1000, 2); ?> GB</b> -
	<b><? echo round(100 - intval($drive['used']) / intval($drive['capacity'])); ?>%</b> remaining
	
	<div class="role"><? echo $drive['role']; ?></div>
</a>