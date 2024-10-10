<?php

/**
 * @package	 MIG0IR Group store optimizer
 * @author	  MILG0IR Group
 * @copyright   
 * @license	 GPL-2.0+
 * @link		http://milg0ir.co.uk
 * @version	 0.0.3
 * 
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Group-store-optimizer
 * Version:	 0.0.3
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
		__( 'MILG0IR ', WPS_TEXT_DOMAIN ),	// Page title
		__( 'Milg0ir', WPS_TEXT_DOMAIN ),				// Menu title (what appears in the sidebar)
		'manage_options',								// Capability required to access
		'mg',											// Menu slug (used in the URL)
		'page_index',									// Function to display the page content
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
function page_index() {
	// Load the HTML content from the plugin
	//print(parse_language_translations('summary.html'));
	print(get_file_content( 'index.html' ));
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
 * Plugin updater handler function.
 * Pings the Github repo that hosts the plugin to check for updates.
 */
function prismpress_check_for_plugin_update( $transient ) {
    // If no update transient or transient is empty, return.
    if ( empty( $transient->checked ) ) {
        return $transient;
    }

    // Plugin slug, path to the main plugin file, and the URL of the update server
    $plugin_slug = 'prismpress/prismpress.php';
    $update_url = 'https://raw.githubusercontent.com/MILG0IR/MILG0IR-Store-Design-Features/refs/heads/main/update-info.json';

    // Fetch update information from your server
    $response = wp_remote_get( $update_url );
    if ( is_wp_error( $response ) ) {
        return $transient;
    }

    // Parse the JSON response (update_info.json must return the latest version details)
    $update_info = json_decode( wp_remote_retrieve_body( $response ) );

    // If a new version is available, modify the transient to reflect the update
    if ( version_compare( $transient->checked[ $plugin_slug ], $update_info->new_version, '<' ) ) {
        $plugin_data = array(
            'slug'        => 'prismpress',
            'plugin'      => $plugin_slug,
            'new_version' => $update_info->new_version,
            'url'         => $update_info->url,
            'package'     => $update_info->package, // URL of the plugin zip file
        );
        $transient->response[ $plugin_slug ] = (object) $plugin_data;
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'prismpress_check_for_plugin_update' );
