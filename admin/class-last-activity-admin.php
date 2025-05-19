<?php
/**
 * This file contains the definition of the Last_Activity_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Last_Activity
 * @subpackage    Last_Activity/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Last_Activity_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_styles() {
		global $pagenow;

		// check if current page is plugins list table page.
		if ( in_array( $pagenow, array( 'plugins.php' ), true ) ) {
			wp_enqueue_style( $this->plugin_name, LAST_ACTIVITY_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		global $pagenow;

		// check if current page is plugins list table page.
		if ( in_array( $pagenow, array( 'plugins.php' ), true ) ) {
			wp_enqueue_script( $this->plugin_name, LAST_ACTIVITY_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'LastActivity',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'plugins.php' ) ), __( 'Settings', 'last-activity' ) );

		return $links;
	}

	/**
	 * Adds a custom column to the plugins list table to display the last active time.
	 *
	 * This function adds a "Last Active" column to the WordPress plugins list table,
	 * allowing users to see when each plugin was last active.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $columns An associative array of columns to be displayed in the plugins list table.
	 * @return    array          An associative array of columns with the new "Last Active" column added.
	 */
	public function manage_plugins_columns( $columns ) {
		// Add the "Last Active" column to the columns array.
		$columns['pla_last_active'] = __( 'Last Active', 'last-activity' );

		// Return the updated columns array.
		return $columns;
	}

	/**
	 * Populates the custom "Last Active" column in the plugins list table.
	 *
	 * This function populates the "Last Active" column with the last active timestamp
	 * for each plugin. It displays the time in the WordPress configured date and time format,
	 * along with the time difference from the current time.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $column_name The name of the current column.
	 * @param     string $plugin_file The plugin's file path relative to the plugins directory.
	 */
	public function manage_plugins_custom_column( $column_name, $plugin_file ) {
		// Check if the current column is the "Last Active" column.
		if ( 'pla_last_active' === $column_name ) {
			// Get the WordPress date and time format.
			$date_time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

			// Retrieve the plugin activity times from the options table.
			$plugin_options = get_option( LAST_ACTIVITY_PLUGIN_OPTION_NAME, array() );

			// Check if the plugin is currently active.
			if ( is_plugin_active( $plugin_file ) ) {
				// Display the current time and "0 days, 0 hours, 0 minutes ago".
				echo esc_html( wp_date( $date_time_format, time() ) ) . '<br>0 days, 0 hours, 0 minutes ago';
			} elseif ( isset( $plugin_options[ $plugin_file ] ) ) {
				// Check if the plugin has a stored last active time.
				// Calculate the time difference.
				// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				$start_date = new DateTime( wp_date( $date_time_format, current_time( 'timestamp' ) ) );
				$end_date   = new DateTime( wp_date( $date_time_format, $plugin_options[ $plugin_file ] ) );
				$interval   = $start_date->diff( $end_date );
				$days       = $interval->days;
				$hours      = $interval->h;
				$minutes    = $interval->i;

				// Display the last active time and the time difference.
				echo wp_kses_post( wp_date( $date_time_format, $plugin_options[ $plugin_file ] ) . '<br>' . $days . ' days, ' . $hours . ' hours, ' . $minutes . ' minutes ago' );
			} else {
				// Display the current time and "0 days, 0 hours, 0 minutes ago" if no last active time is stored.
				// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				echo esc_html( wp_date( $date_time_format, current_time( 'timestamp' ) ) ) . '<br>0 days, 0 hours, 0 minutes ago';
			}
		}
	}

	/**
	 * Updates the last activity time for a specified plugin.
	 *
	 * This function updates the timestamp of the last activity for a given plugin
	 * in the WordPress options table.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @param    string $plugin The plugin's file path relative to the plugins directory.
	 */
	public function update_plugin_last_activity_time( $plugin ) {
		// Retrieve existing plugin activity times or initialize an empty array.
		$plugin_options = get_option( LAST_ACTIVITY_PLUGIN_OPTION_NAME, array() );

		// Retrieve all installed plugins.
		$all_plugins = get_plugins();

		// Iterate through all plugins to find the specified plugin.
		foreach ( $all_plugins as $key => $_plugin ) {
			// Check if the current plugin matches the specified plugin.
			if ( $plugin === $key ) {
				// Update the last activity time for the plugin.
				// phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				$plugin_options[ $key ] = current_time( 'timestamp' );

				// Exit the loop as the plugin has been found and updated.
				break;
			}
		}

		// Update the plugin activity times in the options table.
		update_option( LAST_ACTIVITY_PLUGIN_OPTION_NAME, $plugin_options );
	}
}
