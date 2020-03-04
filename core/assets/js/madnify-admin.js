(function($) {

	$(document).ready(function() {
		// Fired after entire document is finished loading
		madnify_ExpandExtensionEffects();
	});

	$(window).resize(function() {
		// Fired whenever the window is resized
	});

	function madnify_ExpandExtensionEffects() {
		$('.madnify-extension-function.has-effects').click(function() {
			if ($(this).hasClass('effects-revealed')) {
				$(this).removeClass('effects-revealed').next().slideUp();
			} else {
				$(this).addClass('effects-revealed').next().slideDown();
			}
		});
	}

})(jQuery);