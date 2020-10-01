<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.bitwise.academy
 * @since             1.0.0
 * @package           Bitwise_Ai_Service
 *
 * @wordpress-plugin
 * Plugin Name:       bitWise AI latest
 * Plugin URI:        www.bitwise.academy
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.15
 * Author:            MadhanKarthik Ramasamy
 * Author URI:        www.bitwise.academy
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bitwise-ai-service
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BITWISE_AI_SERVICE_VERSION', '1.0.15' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bitwise-ai-service-activator.php
 */
function activate_bitwise_ai_service() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bitwise-ai-service-activator.php';
	Bitwise_Ai_Service_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bitwise-ai-service-deactivator.php
 */
function deactivate_bitwise_ai_service() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bitwise-ai-service-deactivator.php';
	Bitwise_Ai_Service_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bitwise_ai_service' );
register_deactivation_hook( __FILE__, 'deactivate_bitwise_ai_service' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bitwise-ai-service.php';

//Include the autoloader functionality to load the all dependies.
require_once( plugin_dir_path( __FILE__ ).'vendor/autoload.php');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bitwise_ai_service() {

	$plugin = new Bitwise_Ai_Service();
	$plugin->run();

}
run_bitwise_ai_service();

 /*(function myplugin_cookie_expiration( $expiration, $user_id, $remember ) {
    return $remember ? $expiration : 6;
      }
     add_filter( 'auth_cookie_expiration', 'myplugin_cookie_expiration', 99, 3 );
*/