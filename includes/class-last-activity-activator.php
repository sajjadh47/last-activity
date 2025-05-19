<?php
/**
 * This file contains the definition of the Last_Activity_Activator class, which
 * is used during plugin activation.
 *
 * @package       Last_Activity
 * @subpackage    Last_Activity/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin activation.
 *
 * @since    2.0.0
 */
class Last_Activity_Activator {
	/**
	 * Activation hook.
	 *
	 * This function is called when the plugin is activated. It can be used to
	 * perform tasks such as creating database tables, setting up default options,
	 * or scheduling cron jobs.
	 *
	 * @since     2.0.0
	 * @static
	 * @access    public
	 */
	public static function on_activate() {
		$plugin_options = get_option( LAST_ACTIVITY_PLUGIN_OPTION_NAME, array() );
		$all_plugins    = get_plugins();

		foreach ( $all_plugins as $key => $plugin ) {
			if ( ! isset( $plugin_options[ $key ] ) ) {
				// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				$plugin_options[ $key ] = current_time( 'timestamp' );
			}
		}

		update_option( LAST_ACTIVITY_PLUGIN_OPTION_NAME, $plugin_options );
	}
}
