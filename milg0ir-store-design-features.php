<?php

/**
 * @package	 MIG0IR Group store optimizer
 * @author	  MILG0IR Group
 * @copyright   
 * @license	 GPL-2.0+
 * @link		http://milg0ir.co.uk
 * @version	 0.0.5
 * 
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Group-store-optimizer
 * Version:	 0.0.5
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
define( "MG_FILE", __FILE__ );
define( "MG_DIRECTORY", dirname(__FILE__) );
define( "MG_TEXT_DOMAIN", 'milg0ir-store' );
define( "MG_DIRECTORY_BASENAME", plugin_basename( MG_FILE ) );
define( "MG_DIRECTORY_PATH", plugin_dir_path( MG_FILE ) );
define( "MG_DIRECTORY_URL", plugins_url( null, MG_FILE ) );

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
		__( 'MILG0IR ', MG_TEXT_DOMAIN ),	// Page title
		__( 'Milg0ir', MG_TEXT_DOMAIN ),				// Menu title (what appears in the sidebar)
		'manage_options',								// Capability required to access
		'mg',											// Menu slug (used in the URL)
		'page_index',									// Function to display the page content
		plugin_dir_url(__FILE__) . 'assets/images/logo_transparent.svg',	// Icon for the menu item
		4												// Position in the admin sidebar (4 puts it near the top)
	);

}
add_action('admin_menu', 'milg0ir_add_admin_menu');

/**
 * Page handler for the index page.
 *
 * This function is hooked onto the WordPress admin menu system and
 * displays the content of the index page. It requires the PHP file
 * located at `./assets/php/index.php` which contains the HTML for
 * the page.
 *
 * @since 0.0.1
 */
function page_index() {

	// Require the PHP file that contains the page content
	require_once plugin_dir_path(__FILE__) . './assets/php/index.php';

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
 *
 * This function pings the Github repo that hosts the plugin to check
 * for updates.
 *
 * @param object $transient The update transient object.
 *
 * @return object The update transient object.
 */
function milg0ir_check_for_plugin_update( $transient ) {
	// If no update transient or transient is empty, return.
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	// Plugin slug, path to the main plugin file, and the URL of the update server
	$plugin_slug = 'MILG0IR-Store-Design-Features/milg0ir-store-design-features.php';
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
			'slug'        => 'MILG0IR-Store-Design-Features', // The slug of the plugin.
			'plugin'      => $plugin_slug, // The path to the main plugin file.
			'new_version' => $update_info->new_version, // The new version of the plugin.
			'url'         => $update_info->url, // The URL of the plugin page.
			'package'     => $update_info->package, // The URL of the plugin zip file.
		);
		$transient->response[ $plugin_slug ] = (object) $plugin_data;
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'milg0ir_check_for_plugin_update' );
////////////////////////////////////////////////////////////////////////////////

// Add a new endpoint to WooCommerce My Account
function add_stampcard_endpoint() {
    add_rewrite_endpoint('stampcard', EP_ROOT | EP_PAGES);
}
add_action('init', 'add_stampcard_endpoint');

// Add the new endpoint to the My Account menu
function add_stampcard_link_my_account($items) {
    $items['stampcard'] = 'Stamp Card';
    return $items;
}
add_filter('woocommerce_account_menu_items', 'add_stampcard_link_my_account');

// Handle the content of the Stamp Card endpoint
function stampcard_content() {
    echo '<h2>STAMP CARDS ARE CURRENTLY IN DEVELOPMENT</h1>';
    echo '<h2>Your Stamp Card</h2>';
    echo '<p>Here you can see your current stamp card progress!</p>';
    // Add your stamp card display code here
}
add_action('woocommerce_account_stampcard_endpoint', 'stampcard_content');
////////////////////////////////////////////////////////////////////////////////

/**
 * Register settings and add fields for the stamp card plugin
 *
 * @since 0.0.4
 */
function register_stampcard_settings() {
    // Register settings for the stamp card mode and enable/disable option
	register_setting( 'stampcard_settings_group', 'stampcard_mode');
	register_setting( 'stampcard_settings_group', 'stampcard_enabled');
    // Register settings specific to each mode
    register_setting('stampcard_settings_group', 'price_based_value');
    register_setting('stampcard_settings_group', 'order_based_value');
    register_setting('stampcard_settings_group', 'hybrid_discount_percentage');

	// Add a new section to the settings page
	// This section contains the stamp card mode setting
	add_settings_section( 'stampcard_settings_section', 'Stamp Card Configuration', null, 'stampcard-settings');

	// Add the checkbox field to enable or disable the stamp card system
	add_settings_field(
		'stampcard_enabled_field',
		'Enable Stamp Card System',
		'stampcard_enabled_checkbox',
		'stampcard-settings',
		'stampcard_settings_section'
	);
	// Add the dropdown field to select the stamp card mode
	add_settings_field(
		'stampcard_mode_field',
		'Stamp Card Mode',
		'stampcard_mode_dropdown',
		'stampcard-settings',
		'stampcard_settings_section'
	);
	// Add settings specific to the Price-Based mode
    add_settings_field(
        'price_based_value_field',
        'Price-Based Stamp Value',
        'price_based_value_input',
        'stampcard-settings',
        'stampcard_settings_section'
    );
	// Add settings specific to the Price-Based mode
    add_settings_field(
        'order_based_value_field',
        'Order-Based Stamp Value',
        'order_based_value_input',
        'stampcard-settings',
        'stampcard_settings_section'
    );
    // Add settings specific to the Hybrid mode
    add_settings_field(
        'hybrid_discount_percentage_field',
        'Hybrid Discount Percentage',
        'hybrid_discount_percentage_input',
        'stampcard-settings',
        'stampcard_settings_section'
    );
}
add_action('admin_init', 'register_stampcard_settings');

/**
 * Function to display the enable/disable checkbox for the stamp card system
 *
 * @since 0.0.4
 */
function stampcard_enabled_checkbox() {
    // Get the current value of the stamp card enabled option
    $enabled = get_option('stampcard_enabled');

	// Display the dropdown field
	?>
		<input type="checkbox" name="stampcard_enabled" value="1" <?php checked(1, $enabled, true); ?> />
		<label for="stampcard_enabled">Enable the stamp card system on the site.</label>
	<?php
}

/**
 * Function to display the dropdown field for selecting the stamp card mode
 *
 * @since 0.0.4
 */
function stampcard_mode_dropdown() {
	// Get the current value of the stamp card mode option
	$options = get_option('stampcard_mode');

	// Display the dropdown field
	?>
		<select name="stampcard_mode" id="stampcard_mode">
			<option value="order_based" <?php selected($options, 'order_based'); ?>>Order-Based</option>
			<option value="price_based" <?php selected($options, 'price_based'); ?>>Price-Based</option>
			<option value="hybrid" <?php selected($options, 'hybrid'); ?>>Hybrid</option>
		</select>
		<p class="description">
			Select the stamp card mode: Order-Based, Price-Based, or Hybrid.<br>
			Order-Based: this mode will add a stamp to the stamp card For each order placed.<br>
			Price-Based: this mode will add a stamp to the stamp card for each £x spent on the order.<br>
			Hybrid: this mode will add a stamp to the stamp card for each order. Each stamp has a value of x% of the order, at the end of the stamp card the customer will recieve a coupon code for the same value of the whole stamp card.
		</p>
	<?php
}
/**
 * Function to display the input field for Price-Based Stamp Value
 *
 * @since 0.0.4
 */
function order_based_value_input() {
    // Get the current value of the price based stamp value option
    $value = get_option('order_based_value');

    // Display the input field
    ?>
    <input type="number" class="order_based" name="order_based_value" value="<?php echo esc_attr($value); ?>" />
    <p class="description"></p>
    <?php
}
/**
 * Function to display the input field for Price-Based Stamp Value
 *
 * @since 0.0.4
 */
function price_based_value_input() {
    // Get the current value of the price based stamp value option
    $value = get_option('price_based_value');

    // Display the input field
    ?>
    <input type="number" class="price_based" name="price_based_value" value="<?php echo esc_attr($value); ?>" />
    <p class="description">
        Set the minimum order value to earn a stamp for the Price-Based mode.<br>
        For example, if you set it to 10, customers will earn a stamp for every order with a value of 10 or more.
    </p>
    <?php
}

/**
 * Function to display the input field for Hybrid Discount Percentage
 *
 * Displays an input field for the admin to set the discount percentage for the Hybrid mode.
 *
 * @since 0.0.4
 */
function hybrid_discount_percentage_input() {
    // Get the current value of the hybrid discount percentage option
    $value = get_option('hybrid_discount_percentage');

    // Display the input field
    ?>
    <input type="number" class="hybrid_based" name="hybrid_discount_percentage" value="<?php echo esc_attr($value); ?>" min="1" max="100" />
    <p class="description">Set the discount percentage for the Hybrid mode (e.g., 10%).</p>
    <?php
}
?>