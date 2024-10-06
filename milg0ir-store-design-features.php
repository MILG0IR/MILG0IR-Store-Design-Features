<?php

/**
 * @package     MIG0IR Group store optimizer
 * @author      MILG0IR Group
 * @copyright   
 * @license     GPL-2.0+
 * @link        http://milg0ir.co.uk
 * @version     0.0.1
 * 
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Group-store-optimizer
 * Version:     0.0.1
 * Author:      MILG0IR Group
 * Author URI:  http://milg0ir.co.uk
 * Text Domain: milg0ir-store
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin Defines
define( "WPS_FILE", __FILE__ );
define( "WPS_DIRECTORY", dirname(__FILE__) );
define( "WPS_TEXT_DOMAIN", 'milg0ir-store' );
define( "WPS_DIRECTORY_BASENAME", plugin_basename( WPS_FILE ) );
define( "WPS_DIRECTORY_PATH", plugin_dir_path( WPS_FILE ) );
define( "WPS_DIRECTORY_URL", plugins_url( null, WPS_FILE ) );

// Enqueue scripts and styles
function milg0ir_enqueue_assets() {
	wp_enqueue_style( 'milg0ir-store-style', plugin_dir_url( __FILE__ ) . 'assets/css/main.css', array(), '1.0.0' );
	wp_enqueue_script( 'milg0ir-store-script', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'milg0ir_enqueue_assets' );

/**
 * Add the admin menu.
 *
 * This function is hooked onto the 'admin_menu' action and adds the
 * plugin's menu item to the WordPress admin sidebar.
 *
 * @since 0.0.1
 */
function milg0ir_add_admin_menu() {
	/**
	 * Add the top-level menu item.
	 *
	 * This is the main entry point for the plugin's admin UI.
	 *
	 * @since 0.0.1
	 */
	add_menu_page(
		__( 'MILG0IR Store Summary', WPS_TEXT_DOMAIN ),		// Page title
		__( 'Summary', WPS_TEXT_DOMAIN ),						// Menu title (what appears in the sidebar)
		'manage_options',				// Capability required to access
		'mg-summary',					// Menu slug (used in the URL)
		'page_summary',					// Function to display the page content
		'https://avatars.githubusercontent.com/u/62485086?s=20',	// Icon for the menu item
		4								// Position in the admin sidebar (4 puts it near the top)
	);

	/**
	 * Add the configuration submenu item.
	 *
	 * This is the entry point for the plugin's configuration page.
	 *
	 * @since 0.0.1
	 */
	add_submenu_page(
		'mg-summary',		// Parent slug
		__( 'Configuration', WPS_TEXT_DOMAIN ),			// Page title
		__( 'Configuration', WPS_TEXT_DOMAIN ),			// Menu title (what appears in the sidebar)
		'manage_options',	// Capability required to access
		'mg-configuration',		// Menu slug (used in the URL)
		'page_configuration'			// Function to display the page content
	);

}
add_action('admin_menu', 'milg0ir_add_admin_menu');

// Request the HTML from the plugin
function page_summary() {
	//print(patse_language_translations('summary.html'));
	print(get_file_content( 'summary.html' ));
}
function page_configuration() {
	//print(parse_shortcodes(plugin_dir_path( __FILE__ ) . 'assets/html/configuration.html'));
	print(get_file_content( 'configuration.html' ));
}

// Register Custom Taxonomy 'Collections'
function custom_taxonomy_collections() {
    $labels = array(
        'name'              => 'Collections',
        'singular_name'     => 'Collection',
        'search_items'      => 'Search Collections',
        'all_items'         => 'All Collections',
        'parent_item'       => 'Parent Collection',
        'parent_item_colon' => 'Parent Collection:',
        'edit_item'         => 'Edit Collection',
        'update_item'       => 'Update Collection',
        'add_new_item'      => 'Add New Collection',
        'new_item_name'     => 'New Collection Name',
        'menu_name'         => 'Collections',
    );

    $args = array(
        'hierarchical'      => true, // Like categories (or false if like tags)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'collection' ),
    );

    register_taxonomy( 'collection', array( 'product', 'post' ), $args );
}
add_action( 'init', 'custom_taxonomy_collections', 0 );

function get_file_content($filename) {
	$dir = plugin_dir_path( __FILE__ ) . 'assets/' . pathinfo($filename)['extension'] . '/';

	if(file_exists($dir . $filename)) {
		return file_get_contents($dir . $filename);
	} else {
		if($dir . '/404.html') {
			return file_get_contents($dir . '/404.html');
		} else {
			return '<p>HTML file not found.</p>';
		}
	}
}

/**
 * Replace placeholders in an HTML file with translations.
 *
 * This function is used to generate the content of the admin page by
 * parsing placeholders in the HTML file and replacing them with the
 * translations.
 *
 * @param string $file_path The path to the HTML file that contains the
 *                           placeholders to be replaced.
 *
 * @return string The modified HTML content with placeholders replaced
 *                with translations.
 */
function patse_language_translations( $file_path ) {
	/**
	 * Get the content of the HTML file.
	 *
	 * @var string $html_content The content of the HTML file.
	 */
	$html_content = get_file_content( $file_path );

	/**
	 * Find all placeholders in the format {{ * }} using regex.
	 *
	 * @var array $matches Array containing the matches, where $matches[1]
	 *                     contains the keys of the placeholders.
	 */
	preg_match_all( '/{{\s*(.*?)\s*}}/', $html_content, $matches );

	/**
	 * Prepare an array to hold unique translations.
	 *
	 * @var array $translations Array containing the translations, where the
	 *                           key is the original placeholder and the
	 *                           value is the translated string.
	 */
	$translations = [];
	foreach ( $matches[1] as $key ) {
		$translations[ trim( $key ) ] = esc_html__( trim( $key ), 'milg0ir-store' );
	}

	/**
	 * Replace placeholders with translations in the HTML content.
	 *
	 * @var string $html_content The modified HTML content with placeholders
	 *                           replaced with translations.
	 */
	foreach ( $translations as $key => $translated ) {
		$html_content = str_replace( "{{ $key }}", $translated, $html_content );
	}

	/**
	 * Output the modified HTML.
	 *
	 * @return string The modified HTML content with placeholders replaced
	 *                with translations.
	 */
	return $html_content;
}