jQuery(document).ready(function($) {
	// Switch tabs
	$('.nav-tab').on('click', function(event) {
		event.preventDefault();
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.tab-content').hide();
		$($(this).attr('href')).show();
	});

	// Show the first tab
	$('.nav-tab-wrapper a:first').click();

    $('.my-color-picker').wpColorPicker();
});