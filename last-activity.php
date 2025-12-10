<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Last_Activity
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Last Activity
 * Plugin URI:        https://wordpress.org/plugins/last-activity/
 * Description:       Keep Tracks of each plugin's last active datetime, helpful to find obsolete plugins for deletion.
 * Version:           2.0.3
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       last-activity
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'LAST_ACTIVITY_PLUGIN_VERSION', '2.0.3' );

/**
 * Define Plugin Folders Path
 */
define( 'LAST_ACTIVITY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'LAST_ACTIVITY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'LAST_ACTIVITY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'LAST_ACTIVITY_PLUGIN_OPTION_NAME', 'pl_activity_data' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-last-activity-activator.php
 *
 * @since    2.0.0
 */
function on_activate_last_activity() {
	require_once LAST_ACTIVITY_PLUGIN_PATH . 'includes/class-last-activity-activator.php';

	Last_Activity_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_last_activity' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-last-activity-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_last_activity() {
	require_once LAST_ACTIVITY_PLUGIN_PATH . 'includes/class-last-activity-deactivator.php';

	Last_Activity_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_last_activity' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    2.0.0
 */
require LAST_ACTIVITY_PLUGIN_PATH . 'includes/class-last-activity.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_last_activity() {
	$plugin = new Last_Activity();

	$plugin->run();
}

run_last_activity();
