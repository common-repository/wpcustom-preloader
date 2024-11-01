jQuery(window).on('load', function(){ 

	jQuery('#wpcustom-plugin-preloader').delay(250).fadeOut("slow");
	
	setTimeout(wpcustom_plugin_remove_preloader, 2000);
	function wpcustom_plugin_remove_preloader() {	
		jQuery('#wpcustom-plugin-preloader').remove();
	}

});