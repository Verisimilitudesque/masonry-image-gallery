<?php
/**
 * Plugin Name:       Masonry Image Gallery
 * Plugin URI:        https://example.com/
 * Description:       Responsive masonry image gallery with a color overlay and overlaid text, sourced from an ACF Options Page. Use the [masonry_gallery] shortcode to place it anywhere.
 * Version:           1.0.3
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Your Venue
 * Text Domain:       masonry-image-gallery
 * Domain Path:       /languages
 *
 * Requires the Advanced Custom Fields plugin and an ACF Options Page with
 * the following field:
 *
 *   gallery_images   (Repeater)
 *     image            (Image)
 *     overlay_color    (Color Picker)
 *     text             (Group)
 *       heading           (Text)
 *       sub_heading       (Text)
 *     link             (Link)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'MIG_VERSION', '1.0.3' );
define( 'MIG_PLUGIN_FILE', __FILE__ );
define( 'MIG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MIG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Core includes.
 */
require_once MIG_PLUGIN_DIR . 'includes/class-mig-render.php';
require_once MIG_PLUGIN_DIR . 'includes/class-mig-shortcode.php';

/**
 * Bootstrap the plugin once all plugins are loaded so ACF is available.
 */
function mig_init_plugin() {
	if ( ! class_exists( 'ACF' ) ) {
		add_action( 'admin_notices', 'mig_missing_acf_notice' );
	}

	MIG_Shortcode::init();
}
add_action( 'plugins_loaded', 'mig_init_plugin' );

/**
 * Admin notice if ACF isn't active.
 */
function mig_missing_acf_notice() {
	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'Masonry Image Gallery requires Advanced Custom Fields, with the gallery_images repeater configured on an ACF Options Page.', 'masonry-image-gallery' );
	echo '</p></div>';
}

/**
 * Register front-end assets. Only actually enqueued (via mig_load_assets)
 * on pages that use the [masonry_gallery] shortcode.
 */
function mig_enqueue_assets() {
	wp_register_style( 'mig-style', MIG_PLUGIN_URL . 'assets/css/mig-style.css', array(), MIG_VERSION );
}
add_action( 'wp_enqueue_scripts', 'mig_enqueue_assets' );

/**
 * Actually enqueue (assets were only registered above) — called from the
 * shortcode so the CSS only ships on pages that use it.
 */
function mig_load_assets() {
	wp_enqueue_style( 'mig-style' );
}
