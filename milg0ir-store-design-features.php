<?php
/**
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Store-Design-Features
 * Version:		0.0.5
 * Author:		MILG0IR Group
 * Author URI:	https://milg0ir.co.uk
 * Text Domain: milg0ir-store
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
	exit;
}

// Autoloader for the classes
spl_autoload_register(function ($class_name) {
	if (strpos($class_name, 'MILG0IR_Store') !== false) {

		// Remove the namespace part (everything before the last backslash)
		$class_name_without_namespace = substr($class_name, strrpos($class_name, '\\') + 1);
		
		// Convert the class name to the file name format
		$file_name = str_replace('_', '-', strtolower($class_name_without_namespace)) . '.php';
		$file_path = plugin_dir_path(__FILE__) . 'includes/class-' . $file_name;
	
		if (file_exists($file_path)) {
			include_once $file_path;
			print('<script> console.log("MILG0IR Store Plugin: Loaded class file for ' . $class_name_without_namespace . '"); </script>');
		} else {
			error_log("MILG0IR Store Plugin: Failed to load class file for $class_name_without_namespace");
		}
	}
});

// Initialize the plugin
//MILG0IR_Store\Plugin_Setup::init();
