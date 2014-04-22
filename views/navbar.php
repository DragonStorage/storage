<div class='navbar'>
	<h1 class='ui header brand'><a href='./'>Dragon Storage</a></h1>

	<ul class='right nav'>
		<? if(Helpers::loggedIn()) { ?>
			<li><a><? echo Helpers::hi(); ?> <? echo Helpers::out($_SESSION['first']); ?></a></li>
			<li><a href="logout">logout</a></li>
		<? } else { ?>
			<li><a href="login">login</a></li>
			<li><a href="register">register</a></li>
		<? } ?>
	</ul>
</div>

<div class="ui horizontal icon divider">
	<i class="massive heart icon"></i>
</div>