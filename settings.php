<?php
defined( 'ABSPATH' ) or die( 'Keep Silence!!' );
if ( !function_exists( 'wcp_WPCustom_preloader_settings' ) ){
    function wcp_WPCustom_preloader_settings() {
        add_plugins_page( 'Preloader Settings', 'WP Preloader', 'manage_options', 'wcp_WPCustom_preloader_settings', 'wcp_WPCustom_preloader_settings_page');
    }
    add_action( 'admin_menu', 'wcp_WPCustom_preloader_settings' );
}
    
if ( !function_exists( 'wcp_WPCustom_preloader_register_settings' ) ){
    function wcp_WPCustom_preloader_register_settings() {
        register_setting( 'WPCustom_preloader_register_setting', 'wpcustompreloader_bg_color' );
        register_setting( 'WPCustom_preloader_register_setting', 'wpcustompreloader_image' );
        register_setting( 'WPCustom_preloader_register_setting', 'wpcustompreloader_screen' );
    }
    add_action( 'admin_init', 'wcp_WPCustom_preloader_register_settings' );
}

if ( !function_exists( 'wcp_WPCustom_preloader_settings_page' ) ){  
    function wcp_WPCustom_preloader_settings_page(){
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

        $get_theme  = wp_get_theme();
        $theme_name = strtolower( $get_theme->get('Name') );
        $remove_d   = str_replace(" ", "-", $theme_name);
        $get_theme_name = rtrim($remove_d, "-");

        if( is_ssl() ):
            $header_file_url = admin_url("theme-editor.php?file=header.php&theme=$get_theme_name", "https");
        else:
            $header_file_url = admin_url("theme-editor.php?file=header.php&theme=$get_theme_name", "http");
        endif;

        $preloader_element = esc_html('now after <body> insert Preloader HTML element:<code><div id="wpcustom-plugin-preloader"></div></code>');
        ?>
            <div class="wrap" style="display: grid;">
                <h2>Preloader Settings</h2>
                
                <?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ): ?>
                    <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                        <p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                    </div>
                <?php endif; ?>
                <div id="wpcustom-wrap" class="wppostbox">
                    <div class="wp-setting-wrapper">
                        <div class="inside">
                            <form method="post" action="options.php">
                                <?php settings_fields( 'WPCustom_preloader_register_setting' ); ?>
                                
                                <table class="form-table">
                                    <tbody>
                                    
                                        <tr>
                                            <th scope="row"><label for="wpcustompreloader_bg_color">Background Color</label></th>
                                            <td>
                                                <input class="regular-text" name="wpcustompreloader_bg_color" type="text" id="wpcustompreloader_bg_color" value="<?php echo esc_attr( $background_color ); ?>">
                                                <p class="description">Enter background color code, default color is white #FFFFFF.</p>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th scope="row"><label for="wpcustompreloader_image">Preloader Image</label></th>
                                            <td>
                                                <input class="regular-text" name="wpcustompreloader_image" type="text" id="wpcustompreloader_image" value="<?php echo esc_attr( $preloader_image ); ?>">
                                                <p class="description"><?php echo apply_filters('wpt_thepreloader_image_size_remove_128px', 'Enter preloader image link, image size must be 128x128 (will be retina ready).'); ?></p>
                                            </td>
                                        </tr>                                                
                                        
                                        <tr>
                                            <th scope="row">Display Preloader</th>
                                            <td>
                                                <?php
                                                    $display_preloader = get_option( 'wpcustompreloader_screen' );
                                                    
                                                ?>
                                                <fieldset>
                                                    <legend class="screen-reader-text"><span>Display Preloader</span></legend>
                                                    <label title="Display Preloader in full website like home page, posts, pages, categories, tags, attachment, etc..">
                                                        <input type="radio" name="wpcustompreloader_screen" value="full" <?php checked( $display_preloader, 'full' ); ?>>In The Entire Website.
                                                    </label>
                                                    <br>
                                                    <label title="Display Preloader in home page">
                                                        <input type="radio" name="wpcustompreloader_screen" value="homepage" <?php checked( $display_preloader, 'homepage' ); ?>>In Home Page only.
                                                    </label>
                                                    <br>
                                                    <label title="Display Preloader in front page">
                                                        <input type="radio" name="wpcustompreloader_screen" value="frontpage" <?php checked( $display_preloader, 'frontpage' ); ?>>In Front Page only.
                                                    </label>
                                                    <br>
                                                    <label title="Display Preloader in posts only">
                                                        <input type="radio" name="wpcustompreloader_screen" value="posts" <?php checked( $display_preloader, 'posts' ); ?>>In Posts only.
                                                    </label>
                                                    <br>
                                                    <label title="Display Preloader in pages only">
                                                        <input type="radio" name="wpcustompreloader_screen" value="pages" <?php checked( $display_preloader, 'pages' ); ?>>In Pages only.
                                                    </label>
                                                    <br />
                                                    
                                                    <label title="Display Preloader in 404 error page">
                                                        <input type="radio" name="wpcustompreloader_screen" value="404error" <?php checked( $display_preloader, '404error' ); ?>>In 404 Error Page only.
                                                    </label>
                                                    <br>
                                                </fieldset>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><label>Preloader Element (<span style="color:red">*</span>)</label></th>
                                            <td>
                                                <p class="description">Open <a target="_blank" href="<?php echo esc_url($header_file_url); ?>">header.php</a> file for your theme, <?php echo esc_attr($preloader_element); ?></p>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                
                                <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Save Changes"></p>
                            </form>
                        </div>
                        <div class="upgrade-sidebar">
                            <div class="tool-box">
                                <div class="wp-auth">
                                <a href="https://www.linkedin.com/in/rajan-gupta-webdeveloper/" target="_blank"><img src="<?php echo plugins_url( '/banner/auth-pic.jpg', __FILE__ ); ?>" alt="Rajan Gupta"></a>
                                <div class="auth-name">
                                    <h4>Rajan Gupta</h4>
                                    <a href="https://www.linkedin.com/in/rajan-gupta-webdeveloper/" target="_blank">LinkedIn</a>
                                </div>
                                </div>
                                <h3 class="title">Recommended Links</h3>
                                <ul>
                                    <li>Custom WordPress themes with a lot of features and premium support for <a href="https://www.wpcustom.in/lets-work-together/" target="_blank">Get it now</a>.
                                    </li>
                                </ul>
                                <p><a href="https://www.wpcustom.in/request-a-project-quote/" target="_blank"><img style="max-width: 60% !important;" src="<?php echo plugins_url( '/banner/wpcustom-dark.png', __FILE__ ); ?>" alt="WPcustom"></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    } // settings page function
}