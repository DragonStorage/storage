<script type="text/javascript" src="js/jquery.address.min.js"></script>
<script type="text/javascript" src="js/semantic.min.js"></script>
<script type="text/javascript">
	["dropdown", "transition", "checkbox", "tab", "accordion"].forEach(function(which) {
		if($.fn[which].settings) {
			$.fn[which].settings.debug = false;
			$.fn[which].settings.performance = false;
			$.fn[which].settings.verbose = false;
		}
	});

	$('.ui.dropdown').dropdown();
	$('.ui.checkbox').checkbox();
	$('.ui.accordion').accordion();

	$('.tabs > .item').tab();
</script>