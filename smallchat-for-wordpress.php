<?php
/**
 * Bootstrap file for the Smallchat For WordPress plugin.
 *
 * @link      https://www.linkedin.com/in/zakknudsen/
 * @since     1.0.0
 * @package   Smallchat_For_WordPress
 * @copyright Copyright (C) 2018-2022, Zachary Knudsen
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Smallchat For WordPress
 * Plugin URI:  https://rushhourmedia.hk/
 * Description: Load Smallchat's client on WordPress sites.
 * Version:     1.0.1
 * Author:      Zak Knudsen
 * Author URI:  https://www.linkedin.com/in/zakknudsen/
 * License:     GPL v3
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: smallchat-for-wp
 * Domain Path: /languages
 */

// Abort if accessed abnormally.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Prepare translations.
if ( ! function_exists( 'smchat4wp_load_textdomain' ) ) :
	add_action( 'init', 'smchat4wp_load_textdomain' );

	/**
	 * Load the plugin text domain for translations.
	 */
	function smchat4wp_load_textdomain() {
		load_plugin_textdomain( 'smallchat-for-wp', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}
endif;

// Enqueue JS.
if ( ! function_exists( 'smchat4wp_enqueue_scripts' ) ) :
	add_action( 'wp_enqueue_scripts', 'smchat4wp_enqueue_scripts' );

	/**
	 * Add Smallchat loader scrip to the front end.
	 */
	function smchat4wp_enqueue_scripts() {
		$user             = wp_get_current_user();
		$disallowed_roles = array( 'editor', 'administrator', 'author' );
		$options = get_option( 'smchat4wp_settings' );

		// Register a script if a unique client ID has been saved.
		if ( '' !== $options['smchat4wp_unique_client_id'] ) {
			wp_register_script( 'embedded-smallchat-js', esc_url( sprintf( 'https://embed.small.chat/%s.js', $options['smchat4wp_unique_client_id'] ) ), array(), false, true );

			// Enqueue only when useful.
			if ( ! array_intersect( $disallowed_roles, $user->roles ) || is_customize_preview() ) {
				wp_enqueue_script( 'embedded-smallchat-js' );
			}
		}
	}
endif;

// Filter the script tag.
if ( ! function_exists( 'smchat4wp_loader_tag_filter' ) ) :
	add_filter( 'script_loader_tag', 'smchat4wp_loader_tag_filter', 10, 3 );

	/**
	 * Filter the Smallchat loader to include the 'async' html attribute.
	 *
	 * @param string $tag The <script> tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src The script's source URL.
	 */
	function smchat4wp_loader_tag_filter( $tag, $handle, $src ) {
		if ( 'embedded-smallchat-js' !== $handle ) {
			return $tag;
		}

		// 'script' is moved to an arg of sprintf() to suppress WordPress Coding Standards errors generated from phpcs.
		return sprintf( '<%1$s src="%2$s" async></%1$s>', 'script', $src );
	}
endif;

// Add a settings page.
if ( ! function_exists( 'smchat4wp_add_admin_menu' ) ) :
	add_action( 'admin_menu', 'smchat4wp_add_admin_menu' );

	/**
	 * Add an option page for the Smallchat plugin.
	 */
	function smchat4wp_add_admin_menu() {
		add_options_page(
			esc_html_x( 'Smallchat', 'Settings Page Title', 'smallchat-for-wp' ),
			esc_html_x( 'Smallchat', 'Admin Menu Title', 'smallchat-for-wp' ),
			'manage_options',
			'smallchat',
			'smchat4wp_options_page'
		);
	}
endif;

// Register plugin settings.
if ( ! function_exists( 'smchat4wp_settings_init' ) ) :
	/**
	 * Register settings for the Smallchat plugin.
	 */
	function smchat4wp_settings_init() {
		register_setting( 'smchat4wpClient', 'smchat4wp_settings' );

		add_settings_section(
			'smchat4wp_smchat4wpClient_section',
			esc_html_x( 'Your unique Smallchat credentials', 'Credentials settings section description', 'smallchat-for-wp' ),
			'smchat4wp_settings_section_callback',
			'smchat4wpClient'
		);

		add_settings_field(
			'smchat4wp_unique_client_id',
			esc_html_x( 'Client ID', 'Unique client ID setting field', 'smallchat-for-wp' ),
			'smchat4wp_unique_client_id_render',
			'smchat4wpClient',
			'smchat4wp_smchat4wpClient_section'
		);
	}
	add_action( 'admin_init', 'smchat4wp_settings_init' );
endif;

// Render Unique Client ID field.
if ( ! function_exists( 'smchat4wp_unique_client_id_render' ) ) :
	/**
	 * Callback to render the Unique Client ID field.
	 */
	function smchat4wp_unique_client_id_render() {
		$options = get_option( 'smchat4wp_settings' );
		?>
		<input type="text" placeholder="XXXXXXXXXXXXXXXXXX" name="smchat4wp_settings[smchat4wp_unique_client_id]" value="<?php echo esc_attr( $options['smchat4wp_unique_client_id'] ); ?>">
		<?php
	}
endif;

// Unique client identifier section callback.
if ( ! function_exists( 'smchat4wp_settings_section_callback' ) ) :

	/**
	 * Callback to generate instructions/description for the client identity settings section.
	 */
	function smchat4wp_settings_section_callback() {
		echo '<p>' . esc_html__( 'Enter your unique identifier from Smallchat.', 'smallchat-for-wp' ) . '</p>';
		echo '<img src="' . plugin_dir_url( __FILE__ ) . 'assets/embed-unique-id-location.png" alt="Image showing where to retrieve unique identifier">';
	}
endif;

// Generate an options page.
if ( ! function_exists( 'smchat4wp_options_page' ) ) :

	/**
	 * Generates the markup for the options page with form.
	 */
	function smchat4wp_options_page() {
		?>
		<form action='options.php' method='post'>

			<h2><?php echo esc_html_x( 'Smallchat', 'Settings Form Title', 'smallchat-for-wp' ); ?></h2>

			<?php
			settings_fields( 'smchat4wpClient' );
			do_settings_sections( 'smchat4wpClient' );
			submit_button();
			?>

		</form>
		<?php

	}
endif;
