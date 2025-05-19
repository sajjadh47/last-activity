<?php
/**
 * This file contains the definition of the Last_Activity_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Last_Activity
 * @subpackage    Last_Activity/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Last_Activity_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'last-activity',
			false,
			dirname( LAST_ACTIVITY_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
