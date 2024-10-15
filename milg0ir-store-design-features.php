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


$stampcard_defaults = [
	'mg_stamp_card_enabled' => false,
	'mg_stamp_card_mode' => 'order_based',
	'mg_price_based_value' => 10,
	'mg_min_order_value' => 10,
	'mg_hybrid_discount_percentage' => 10,
];

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style('milg0ir-store-style', plugin_dir_url( __FILE__ ) . 'assets/css/main.css', array(), '1.0.0' );
	wp_enqueue_script('milg0ir-store-script', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', array( 'jquery' ), '1.0.0', true );
	
	if (is_cart() || is_checkout() || is_front_page() || is_product()) {
		wp_enqueue_script('mg_cart_stamp_card_display', plugin_dir_url( __FILE__ ) . 'assets/js/cart_stamp_card_display.js', array('jquery'), '1.0.0', true);
		wp_localize_script('mg_cart_stamp_card_display', 'cart_params', array(
			'cart_total' => WC()->cart->get_total('edit'),
		));
	}
});
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style('milg0ir-store-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '1.0.0' );
	wp_enqueue_script('milg0ir-store-admin-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
});

/**
 * Add the admin menu.
 *
 * This function is hooked onto the 'admin_menu' action and adds the
 * plugin's menu item to the WordPress admin sidebar.
 *
 * @since 0.0.1
 */
add_action('admin_menu', function () {
	/**
	 * Add the top-level menu item.
	 *
	 * This is the main entry point for the plugin's admin UI.
	 *
	 * @since 0.0.1
	 */
	add_menu_page(
		__( 'MILG0IR ', MG_TEXT_DOMAIN ),	// Page title
		__( 'Milg0ir', MG_TEXT_DOMAIN ),	// Menu title (what appears in the sidebar)
		'manage_options',					// Capability required to access
		'mg',								// Menu slug (used in the URL)
		'page_index',						// Function to display the page content
		plugin_dir_path(__FILE__) . 'assets/images/logo/transparent/x24.webp',			// Icon for the menu item
		4									// Position in the admin sidebar (4 puts it near the top)
	);

});

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
add_action( 'init', function () {

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
}, 0 );

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
add_filter( 'pre_set_site_transient_update_plugins', function ( $transient ) {
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
} );
////////////////////////////////////////////////////////////////////////////////

/**
 * Add a new endpoint to WooCommerce My Account
 *
 * This function is hooked onto the 'init' action and adds a new
 * endpoint to the WooCommerce My Account page.
 *
 * @since 0.0.4
 */
add_action('init', function () {
	/**
	 * Add the new endpoint to the My Account menu
	 *
	 * This adds a new endpoint to the My Account page. The endpoint
	 * is called 'stamp-card' and is available at
	 * http://example.com/my-account/stamp-card
	 */
	add_rewrite_endpoint( 'stamp-card', EP_ROOT | EP_PAGES );
}, 0);

/**
 * Add the new endpoint to the My Account menu
 *
 * This function is hooked onto the 'woocommerce_account_menu_items' filter and
 * is responsible for adding the Stamp Card endpoint to the My Account menu.
 *
 * @since 0.0.4
 *
 * @param array $items The current menu items
 *
 * @return array The modified menu items with the new endpoint
 */
add_filter('woocommerce_account_menu_items', function ($items) {
	return $items['stamp_card'] = __('Stamp Card', MG_TEXT_DOMAIN);
});

/**
 * Handle the content of the My Account Stamp Card endpoint
 *
 * This function is hooked onto the 'woocommerce_account_stamp_card_endpoint' action and
 * is responsible for displaying the content of the Stamp Card endpoint in the My Account
 * page.
 *
 * @since 0.0.4
 */
add_action('woocommerce_account_stamp_card_endpoint', function () {
	require_once plugin_dir_path(__FILE__) . './assets/php/stampcard.php';
});


/**
 * Register a custom block category called 'milg0ir blocks'.
 *
 * @since 0.0.5
 */
add_filter( 'block_categories_all', function ( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'milg0ir-blocks',
				'title' => 'MILG0IR Blocks',
				'icon'  => plugin_dir_path(__FILE__) . 'assets/images/logo/transparent/x24.webp',
			),
		)
	);
}, 10, 2 );

/**
 * Registers the custom block script with WordPress.
 *
 * @since 0.0.5
 */

add_action('init', function () {
	wp_register_script(
		'stamp-card-preview-block',
		plugin_dir_url(__FILE__) . 'blocks/stamp-card.js',
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
		filemtime(plugin_dir_path(__FILE__) . 'blocks/stamp-card.js'),
		true
	);

	register_block_type('milg0ir/stamp-card-preview-block', array(
		'editor_script' => 'stamp-card-preview-block',
	));
});

add_action('rest_api_init', function () {
	register_rest_route('milg0ir/v1', '/stamp-card-data', array(
		'methods' => 'POST',
		'callback' => 'get_stamp_card_data',
		'permission_callback' => '__return_true', // Adjust this for security
	));
});

register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'stamp_cards';
    $charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		stamp_id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		user_id BIGINT(20) UNSIGNED NOT NULL,
		order_id BIGINT(20) UNSIGNED DEFAULT NULL,
		stamp_type VARCHAR(50) NOT NULL,
		stamp_value DECIMAL(10, 2) DEFAULT 0.00,
		stamp_redeemed DECIMAL(10, 2) DEFAULT 0.00,
		redeemed_at DATETIME DEFAULT NULL,
		redeemed_order_id BIGINT(20) UNSIGNED DEFAULT NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		INDEX user_id_index (user_id)
	) $charset_collate;";
	

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

function mg_save_stamp($user_id, $stamp_mode, $stamp_value = 0, $order_id = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'stamp_cards';

    $wpdb->insert(
        $table_name,
        [
            'user_id' => $user_id,
            'stamp_mode' => $stamp_mode,
            'stamp_value' => $stamp_value,
            'order_id' => $order_id,
        ],
        [
            '%d', // user_id
            '%s', // stamp_mode
            '%f', // stamp_value
            '%d', // order_id
        ]
    );
}

add_action('woocommerce_order_status_completed', function ($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $total_value = $order->get_total();

    // Assuming you have logic to determine the stamp_mode and value
    $stamp_mode = 'order_based'; // Replace with your actual logic
    $stamp_value = calculate_stamp_value($total_value); // Your custom calculation logic

    mg_save_stamp($user_id, $stamp_mode, $stamp_value, $order_id);
});

function mg_get_user_stamps($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'stamp_cards';

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC",
        $user_id
    ));

    return $results;
}


function get_stamp_card_data(WP_REST_Request $request) {
	// Retrieve the cart total
	$cart_total = $request->get_param('cart_total');
	$config = [
		'mg_stamp_card_enabled' => get_option('mg_stamp_card_enabled'),
		'mg_stamp_card_mode' => get_option('mg_stamp_card_mode'),
		'mg_min_order_value' => get_option('mg_min_order_value'),
		'mg_price_based_value' => get_option('mg_price_based_value'),
		'mg_hybrid_discount_percentage' => get_option('mg_hybrid_discount_percentage'),
	];

	// Calculate the number of stamps
	$number_of_stamps = 0;
	$message = '';

	if ($cart_total >= $config['mg_min_order_value']) {
		if ($config['mg_stamp_card_mode'] === 'order_based') {
			$message = 'You can earn a stamp with this order!';
		} elseif ($config['mg_stamp_card_mode'] === 'value_based') {
			$number_of_stamps = floor($cart_total / $config['mg_price_based_value']);
			$message = "You can earn {$number_of_stamps} stamp(s) with this order!";
		} elseif ($config['mg_stamp_card_mode'] === 'hybrid') {
			$value = round(($cart_total / 100) * $config['mg_hybrid_discount_percentage'], 2);
			$message = "Your stamp may be worth {$value} off a future order!";
		} else {
			$message = 'Invalid stamp card mode!';
		}
	} else {
		$difference = $mg_min_order_value - $cart_total;
		$message = "You are {$difference} away from earning a stamp!";
	}

	return new WP_REST_Response([
		'stampsEnabled' => $mg_stamp_card_enabled,
		'number_of_stamps' => $number_of_stamps,
		'message' => $message,
		'info' => [
			'cartTotal' => $cart_total,
			'config' => $config
		]
	], 200);
}







////////////////////////////////////////////////////////////////////////////////

/**
 * Register settings and add fields for the stamp card plugin
 *
 * @since 0.0.4
 */
add_action('admin_init', function() {
	// Register settings for the stamp card mode and enable/disable option
	register_setting( 'mg_stamp_card_settings_group', 'mg_stamp_card_enabled', [ 'default' => $stampcard_defaults['mg_stamp_card_enabled'] ]);
	register_setting( 'mg_stamp_card_settings_group', 'mg_stamp_card_mode', [ 'default' => $stampcard_defaults['mg_stamp_card_mode'] ]);
	// Register settings specific to each mode
	register_setting('mg_stamp_card_settings_group', 'mg_price_based_value', [ 'default' => $stampcard_defaults['mg_price_based_value'] ]);
	register_setting('mg_stamp_card_settings_group', 'mg_min_order_value', [ 'default' => $stampcard_defaults['mg_min_order_value'] ]);
	register_setting('mg_stamp_card_settings_group', 'mg_hybrid_discount_percentage', [ 'default' => $stampcard_defaults['mg_hybrid_discount_percentage'] ]);

	// Add a new section to the settings page
	// This section contains the stamp card mode setting
	add_settings_section( 'mg_stamp_card_settings_section', 'Stamp Card Configuration', null, 'mg_stamp_card-settings');

	// Add the checkbox field to enable or disable the stamp card system
	add_settings_field(
		'mg_stamp_card_enabled_field',		// ID
		'Enable Stamp Card System',		// Title
		'mg_stamp_card_enabled_checkbox',	// Callback
		'mg_stamp_card-settings',			// Page
		'mg_stamp_card_settings_section',	// Section
	);
	// Add the dropdown field to select the stamp card mode
	add_settings_field(
		'mg_stamp_card_mode_field',		// ID
		'Stamp Card Mode',				// Title
		'mg_stamp_card_mode_dropdown',		// Callback
		'mg_stamp_card-settings',			// Page
		'mg_stamp_card_settings_section',	// Section
	);
	// Add settings specific to the Price-Based mode
	add_settings_field(
		'mg_price_based_value_field',		// ID
		'Spend per stamp',				// Title
		'mg_price_based_value_input',		// Callback
		'mg_stamp_card-settings',			// Page
		'mg_stamp_card_settings_section',	// Section
	);
	// Add settings specific to the Price-Based mode
	add_settings_field(
		'mg_min_order_value_field',		// ID
		'Minimum order Value',			// Title
		'mg_min_order_value_input',		// Callback
		'mg_stamp_card-settings',			// Page
		'mg_stamp_card_settings_section',	// Section
	);
	// Add settings specific to the Hybrid mode
	add_settings_field(
		'mg_hybrid_discount_percentage_field',	// ID
		'Percentage value of order',		// Title
		'mg_hybrid_discount_percentage_input',	// Callback
		'mg_stamp_card-settings',				// Page
		'mg_stamp_card_settings_section',		// Section
	);
});

/**
 * Function to display the enable/disable checkbox for the stamp card system
 *
 * @since 0.0.4
 */
function mg_stamp_card_enabled_checkbox() {
	print('
		<input type="checkbox" name="mg_stamp_card_enabled" value="1" ' . (get_option('mg_stamp_card_enabled')? 'checked="checked': '') . ' />
		<label for="mg_stamp_card_enabled">Enable the stamp card system on the site.</label>
	');
}

/**
 * Function to display the dropdown field for selecting the stamp card mode
 *
 * @since 0.0.4
 */
function mg_stamp_card_mode_dropdown() {
	print('
		<select name="mg_stamp_card_mode" id="mg_stamp_card_mode">
			<option value="order_based" ' . (get_option('mg_stamp_card_mode') == 'order_based'? 'selected="selected"': '') . '>Order-Based</option>
			<option value="price_based" ' . (get_option('mg_stamp_card_mode') == 'price_based'? 'selected="selected"': '') . '>Price-Based</option>
			<option value="hybrid" ' . (get_option('mg_stamp_card_mode') == 'hybrid'? 'selected="selected"': '') . '>Hybrid</option>
		</select>
		<p class="description">
			Select the stamp card mode: Order-Based, Price-Based, or Hybrid.<br>
			Order-Based: this mode will add a stamp to the stamp card For each order placed.<br>
			Price-Based: this mode will add a stamp to the stamp card for each £x spent on the order.<br>
			Hybrid: this mode will add a stamp to the stamp card for each order. Each stamp has a value of x% of the order, at the end of the stamp card the customer will recieve a coupon code for the same value of the whole stamp card.
		</p>
	');
}

/**
 * Function to display the input field for Price-Based Stamp Value
 *
 * @since 0.0.4
 */
function mg_price_based_value_input() {
	print('
		<input type="number" class="price_based" name="mg_price_based_value" value="' . esc_attr(get_option('mg_price_based_value')) . '" />
		<p class="description">
			Set the price value for each stamp. Users can get multiple stamps per order (Default: '. esc_html($currency_symbol) .'10)
		</p>
	');
}

/**
 * Function to display the input field for Hybrid Discount Percentage
 *
 * Displays an input field for the admin to set the discount percentage for the Hybrid mode.
 *
 * @since 0.0.4
 */
function mg_hybrid_discount_percentage_input() {
	print('
		<input type="number" class="hybrid_based" name="mg_hybrid_discount_percentage" value="' . esc_attr(get_option('mg_hybrid_discount_percentage')) . '" min="1" max="100" />
		<p class="description">
			Set the percentage for the Hybrid mode to allocate to the stamp value (Default: 10%).
		</p>
	');
}

/**
 * Function to display the input field for the minimum order value for the Order-Based Stamp Card mode
 *
 * This function will display an input field for the admin to set the minimum order value required to earn a stamp.
 *
 * @since 0.0.4
 */
function mg_min_order_value_input() {
	$currency_symbol = get_woocommerce_currency_symbol();

	print('
		<input type="number" class="min_order" name="mg_min_order_value" value="' . esc_attr(get_option('mg_min_order_value')) . '" />
		<p class="description">
			Minimum order value required to earn a stamp. (Default: '. esc_html($currency_symbol) .'10)
		</p>
	');
}

?>