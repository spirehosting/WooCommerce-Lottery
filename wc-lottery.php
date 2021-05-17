<?php

/**
 *
 * @link              https://spirehosting.co.uk
 * @since             1.0.0
 * @package           wc_lottery
 *
 * @wordpress-plugin
 * Plugin Name:       Competitions
 * Plugin URI:        https://spirehosting.co.uk
 * Description:       Competitions Plugin
 * Version:           1.1.23
 * Author:            Spire Hosting
 * Author URI:        https://spirehosting.co.uk
 * Requires at least: 4.0
 * Tested up to:      5.4
 * 
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc_lottery
 * Domain Path:       /languages
 *
 * WC requires at least: 2.6.0
 * WC tested up to: 4.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( is_multisite() && in_array( 'woocommerce/woocommerce.php', array_flip( get_site_option( 'active_sitewide_plugins' ) ) ) ) ) {

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-wc-lottery-activator.php
     */
		 function activate_wc_lottery() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-lottery-activator.php';
        wc_lottery_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-wc-lottery-deactivator.php
     */
    function deactivate_wc_lottery() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-lottery-deactivator.php';
        wc_lottery_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_wc_lottery' );
    register_deactivation_hook( __FILE__, 'deactivate_wc_lottery' );

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-wc-lottery.php';

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(  __FILE__  ) . 'includes/class-wc-lottery-i18n.php';


    function run_wc_lottery() {

        global $wc_lottery;

        $wc_lottery = new wc_lottery();
        $wc_lottery->run();
    }

    add_action( 'woocommerce_init' , 'run_wc_lottery');

} else {

    add_action('admin_notices', 'wc_lottery_error_notice');

    function wc_lottery_error_notice(){
        global $current_screen;
        if($current_screen->parent_base == 'plugins'){
                echo '<div class="error"><p>Competitions '.__('requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="'.admin_url('plugin-install.php?tab=search&type=term&s=WooCommerce').'" target="_blank">WooCommerce</a> first.', 'wc_lottery').'</p></div>';
        }
    }

    $plugin = plugin_basename(__FILE__);

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if(is_plugin_active($plugin)){
            deactivate_plugins( $plugin);
    }

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
}
