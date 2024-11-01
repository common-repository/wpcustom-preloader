<?php
/*
Plugin Name: WPCustom Preloader
Plugin URI: https://www.wpcustom.in/lead-generation/
Description: Add preloader to your website easily, compatible with all major browsers.
Version: 1.0
Author: Rajan Gupta
Author URI: https://www.wpcustom.in/
License: GPLv2 or later
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Add plugin meta links
if ( !function_exists( 'wpc_wpcustom_preloader_plugin_row_meta' ) ){
	function wpc_wpcustom_preloader_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'preloader.php' ) !== false ) {
			$new_links = ['WPCustom'];
			$links = array_merge( $links, $new_links );
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'wpc_wpcustom_preloader_plugin_row_meta', 10, 2 );
}

if ( !function_exists( 'wpc_wpcustom_preloader_plugin_action_links' ) ){
	function wpc_wpcustom_preloader_plugin_action_links( $actions, $plugin_file ){
		static $plugin;
		if ( !isset($plugin) ){
			$plugin = plugin_basename(__FILE__);
		}
		if ($plugin == $plugin_file) {	
			if ( is_ssl() ) {
				$settings_link = '<a href="'.admin_url( 'plugins.php?page=wpc_wpcustom_preloader_settings', 'https' ).'">Settings</a>';
			}else{
				$settings_link = '<a href="'.admin_url( 'plugins.php?page=wpc_wpcustom_preloader_settings', 'http' ).'">Settings</a>';
			}
			$settings = array($settings_link);
			$actions = array_merge($settings, $actions);
		}
		return $actions;
	}
	add_filter( 'plugin_action_links', 'wpc_wpcustom_preloader_plugin_action_links', 10, 5 );
}

if ( !function_exists( 'wpc_wpcustom_plugin_preloader_init' ) ){
	function wpc_wpcustom_plugin_preloader_init(){
		if( !get_option('wpcustompreloader_screen') ){
			update_option('wpcustompreloader_screen', 'full');
		}

		if( !function_exists('is_woocommerce') and get_option('wpcustompreloader_screen') == 'woocommerce' ){
					update_option('wpcustompreloader_screen', 'full');
			}
	}
	add_action('init', 'wpc_wpcustom_plugin_preloader_init');
}

// Include Settings page
include( plugin_dir_path(__FILE__).'/settings.php' );


// Include JavaScript
if ( !function_exists( 'wcp_wpcustomplaceholder_wpc_wpcustom_plugin_preloader_script' ) ){
	function wcp_wpcustomplaceholder_wpc_wpcustom_plugin_preloader_script(){	
		if(
			get_option( 'wpcustompreloader_screen' ) == 'full'
			or get_option( 'wpcustompreloader_screen' ) == 'homepage' and is_home()
			or get_option( 'wpcustompreloader_screen' ) == 'frontpage' and is_front_page()
			or get_option( 'wpcustompreloader_screen' ) == 'posts' and is_single()
			or get_option( 'wpcustompreloader_screen' ) == 'pages' and is_page()
			or get_option( 'wpcustompreloader_screen' ) == '404error' and is_404()
		):
			wp_enqueue_script( 'wpcustom-plugin-preloader-script', plugins_url( '/js/preloader-script.js', __FILE__ ), array('jquery'), null, false);
		endif;

	}
	add_action('wp_enqueue_scripts', 'wcp_wpcustomplaceholder_wpc_wpcustom_plugin_preloader_script');
}

if ( !function_exists( 'wcp_wpcustomplaceholder_wpdocs_selectively_enqueue_admin_script' ) ){
	function wcp_wpcustomplaceholder_wpdocs_selectively_enqueue_admin_script( $hook ) {
		wp_enqueue_style( 'wpcustom-placeholder', plugins_url( '/css/wpcustom-placeholder.css', __FILE__ ), false, '1.0.0');
	}
	add_action( 'admin_enqueue_scripts', 'wcp_wpcustomplaceholder_wpdocs_selectively_enqueue_admin_script' );
}

// Add CSS
if ( !function_exists( 'wpc_wpcustomplaceholder_plugin_preloader_css' ) ){
	function wpc_wpcustomplaceholder_plugin_preloader_css(){	
		if( get_option('wpcustompreloader_bg_color') ):
			$background_color = get_option('wpcustompreloader_bg_color');
		else:
			$background_color = '#FFFFFF';
		endif;
			
		if( get_option('wpcustompreloader_image') ):
			$preloader_image = get_option('wpcustompreloader_image');
		else:
			$preloader_image = plugins_url( '/images/preloader.GIF', __FILE__ );
		endif;	
		if(
			get_option( 'wpcustompreloader_screen' ) == 'full'
			or get_option( 'wpcustompreloader_screen' ) == 'homepage' and is_home()
			or get_option( 'wpcustompreloader_screen' ) == 'frontpage' and is_front_page()
			or get_option( 'wpcustompreloader_screen' ) == 'posts' and is_single()
			or get_option( 'wpcustompreloader_screen' ) == 'pages' and is_page()
			or get_option( 'wpcustompreloader_screen' ) == '404error' and is_404()
		):
			$image_width = apply_filters('wpt_thepreloader_image_size_get_width', '64');
			$image_height = apply_filters('wpt_thepreloader_image_size_get_height', '64');
		?>
				<style type="text/css">
				#wpcustom-plugin-preloader{
					position: fixed;
					top: 0;
					left: 0;
					right: 0;
					bottom: 0;
					background:url(<?php echo esc_attr($preloader_image); ?>) no-repeat <?php echo esc_attr($background_color); ?> 50%;
					-moz-background-size:<?php echo esc_attr($image_width); ?>px; <?php echo esc_attr($image_height); ?>px;
					-o-background-size:<?php echo esc_attr($image_width); ?>px; <?php echo esc_attr($image_height); ?>px;
					-webkit-background-size:<?php echo esc_attr($image_width); ?>px <?php echo esc_attr($image_height); ?>px;
					background-size:<?php echo esc_attr($image_width); ?>px; <?php echo esc_attr($image_height); ?>px;
					z-index: 99998;
					width:100%;
					height:100%;
				}
			</style>
			<noscript>
					<style type="text/css">
							#wpcustom-plugin-preloader{
								display:none !important;
							}
					</style>
			</noscript>
			<?php
		endif;
		
	}
	add_action('wp_head', 'wpc_wpcustomplaceholder_plugin_preloader_css');
}