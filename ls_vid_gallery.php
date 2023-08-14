<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lumberstack.org
 * @since             1.0.0
 * @package           Ls_vid_gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Lumberstack Video Gallery
 * Plugin URI:        https://https://github.com/mattmaddux/ls-vid-gallery
 * Description:       A simple video gallery plugin for WordPress.
 * Version:           1.0.0
 * Author:            Lumberstack
 * Author URI:        https://lumberstack.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ls_vid_gallery
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
define( 'LS_VID_GALLERY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ls_vid_gallery-activator.php
 */
function activate_ls_vid_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ls_vid_gallery-activator.php';
	Ls_vid_gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ls_vid_gallery-deactivator.php
 */
function deactivate_ls_vid_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ls_vid_gallery-deactivator.php';
	Ls_vid_gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ls_vid_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_ls_vid_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ls_vid_gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ls_vid_gallery() {

	$plugin = new Ls_vid_gallery();
	$plugin->run();

}
run_ls_vid_gallery();
