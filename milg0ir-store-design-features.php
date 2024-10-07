<?php

/**
 * @package	 MIG0IR Group store optimizer
 * @author	  MILG0IR Group
 * @copyright   
 * @license	 GPL-2.0+
 * @link		http://milg0ir.co.uk
 * @version	 0.0.2
 * 
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Group-store-optimizer
 * Version:	 0.0.2
 * Author:	  MILG0IR Group
 * Author URI:  http://milg0ir.co.uk
 * Text Domain: milg0ir-store
 * License:	 GPL-2.0+
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
function milg0ir_enqueue_admin_assets() {
	wp_enqueue_style( 'milg0ir-store-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '1.0.0' );
	wp_enqueue_script( 'milg0ir-store-admin-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'milg0ir_enqueue_admin_assets' );

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
		__( 'MILG0IR Store Summary', WPS_TEXT_DOMAIN ),	// Page title
		__( 'Milg0ir', WPS_TEXT_DOMAIN ),				// Menu title (what appears in the sidebar)
		'manage_options',								// Capability required to access
		'mg',											// Menu slug (used in the URL)
		'page_summary',									// Function to display the page content
		plugin_dir_url(__FILE__) . 'assets/images/logo_transparent.svg',	// Icon for the menu item
		4												// Position in the admin sidebar (4 puts it near the top)
	);

}
add_action('admin_menu', 'milg0ir_add_admin_menu');

/**
 * Page handler for the summary page.
 *
 * This function is hooked onto the WordPress admin menu system and
 * displays the content of the summary page.
 *
 * @since 0.0.1
 */
function page_summary() {
	// Load the HTML content from the plugin
	//print(parse_language_translations('summary.html'));
	print(get_file_content( 'summary.html' ));
}
/**
 * Page handler for the configuration page.
 *
 * This function is hooked onto the WordPress admin menu system and
 * displays the content of the configuration page.
 *
 * @since 0.0.1
 */
function page_configuration() {
	// Load the HTML content from the plugin
	print(get_file_content( 'configuration.html' ));
}

/**
 * Register a custom taxonomy for the Collections feature.
 *
 * The Collections feature is for organizing products into logical groups
 * that can be used for filtering, sorting, etc.
 *
 * @since 0.0.1
 */
function custom_taxonomy_collections() {

	/**
	 * Define the labels for the taxonomy.
	 *
	 * This array contains the text strings that are displayed in the
	 * WordPress admin UI for the taxonomy.
	 *
	 * @since 0.0.1
	 */
	$labels = array(
		'name'				=> 'Collections',
		'singular_name'		=> 'Collection',
		'search_items'		=> 'Search Collections',
		'all_items'			=> 'All Collections',
		'parent_item'		=> 'Parent Collection',
		'parent_item_colon'	=> 'Parent Collection:',
		'edit_item'			=> 'Edit Collection',
		'update_item'		=> 'Update Collection',
		'add_new_item'		=> 'Add New Collection',
		'new_item_name'		=> 'New Collection Name',
		'menu_name'			=> 'Collections',
	);

	/**
	 * Define the arguments for the taxonomy.
	 *
	 * This array contains the arguments that are passed to the
	 * register_taxonomy() function.
	 *
	 * @since 0.0.1
	 */
	$args = array(
		'hierarchical'		=> true, // Like categories (or false if like tags)
		'labels'			=> $labels,
		'show_ui'			=> true,
		'show_admin_column'	=> true,
		'query_var'			=> true,
		'rewrite'			=> array( 'slug' => 'collection' ),
	);

	/**
	 * Register the taxonomy.
	 *
	 * This registers the taxonomy with WordPress and makes it available
	 * for use.
	 *
	 * @since 0.0.1
	 */
	register_taxonomy( 'collection', array( 'product', 'post' ), $args );
}
add_action( 'init', 'custom_taxonomy_collections', 0 );

/**
 * Get the content of a file.
 *
 * This function is used to get the content of an HTML file from the
 * plugin's assets directory. It first checks if the file exists in the
 * correct directory, and if so, returns the content of the file. If the
 * file does not exist, it returns the content of the 404.html file if it
 * exists, or a default message if not.
 *
 * @param string $filename The name of the file to get the content of.
 *
 * @return string The content of the file, or a default message if the
 *				file does not exist.
 *
 * @since 0.0.1
 */
function get_file_content($filename) {
	$dir = plugin_dir_path( __FILE__ ) . 'assets/' . pathinfo($filename)['extension'] . '/';

	if (file_exists($dir . $filename)) {
		// Return the content of the file if it exists
		return file_get_contents($dir . $filename);
	} else {
		// Return the content of the 404.html file if it exists
		if (file_exists($dir . '/404.html')) {
			return file_get_contents($dir . '/404.html');
		} else {
			// Return a default message if the file does not exist
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
 *						   placeholders to be replaced.
 *
 * @return string The modified HTML content with placeholders replaced
 *				with translations.
 *
 * @since 0.0.1
 */
function parse_language_translations( $file_path ) {
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
	 *					 contains the keys of the placeholders.
	 *
	 * The regex pattern matches any string that starts with '{{', followed
	 * by any characters (including whitespace), and ends with '}}'. The
	 * parentheses around the inner pattern are used to create a capture
	 * group, which is stored in $matches[1].
	 */
	preg_match_all( '/{{\s*(.*?)\s*}}/', $html_content, $matches );

	/**
	 * Prepare an array to hold unique translations.
	 *
	 * @var array $translations Array containing the translations, where the
	 *						   key is the original placeholder and the
	 *						   value is the translated string.
	 *
	 * The array is used to store the translations in a way that allows
	 * us to easily look up the translation for a given placeholder.
	 */
	$translations = [];
	foreach ( $matches[1] as $key ) {
		$translations[ trim( $key ) ] = esc_html__( trim( $key ), 'milg0ir-store' );
	}

	/**
	 * Replace placeholders with translations in the HTML content.
	 *
	 * @var string $html_content The modified HTML content with placeholders
	 *						   replaced with translations.
	 *
	 * The foreach loop iterates over the $translations array and
	 * replaces each placeholder in the HTML content with the
	 * corresponding translation.
	 */
	foreach ( $translations as $key => $translated ) {
		$html_content = str_replace( "{{ $key }}", $translated, $html_content );
	}

	/**
	 * Output the modified HTML.
	 *
	 * @return string The modified HTML content with placeholders replaced
	 *				with translations.
	 *
	 * The function returns the modified HTML content, which is then
	 * used to generate the content of the admin page.
	 */
	return $html_content;
}
