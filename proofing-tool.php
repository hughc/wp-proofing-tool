<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://highbrow.com.au/
 * @since             1.0.0
 * @package           Proofing_Tool
 *
 * @wordpress-plugin
 * Plugin Name:       Proofing Tool
 * Plugin URI:        http://highbrow.com.au/plugins/proofing-tool
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hugh Campbell
 * Author URI:        http://highbrow.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       proofing-tool
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-proofing-tool-activator.php
 */
function activate_proofing_tool() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-proofing-tool-activator.php';
	Proofing_Tool_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-proofing-tool-deactivator.php
 */
function deactivate_proofing_tool() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-proofing-tool-deactivator.php';
	Proofing_Tool_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_proofing_tool' );
register_deactivation_hook( __FILE__, 'deactivate_proofing_tool' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-proofing-tool.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_proofing_tool() {

	$plugin = new Proofing_Tool();
	$plugin->run();

}
run_proofing_tool();
