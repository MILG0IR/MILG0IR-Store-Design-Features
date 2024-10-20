<?php
/**
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Store-Design-Features
 * Version:		0.0.4
 * Author:		MILG0IR Group
 * Author URI:	https://milg0ir.co.uk
 * Text Domain: milg0ir-store
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 */
//////////!              DEFAULTS                !//////////
	if(!defined('ABSPATH')) exit;

	define("MG_FILE", __FILE__);
	define("MG_DIRECTORY", dirname(__FILE__));
	define("MG_TEXT_DOMAIN", 'milg0ir-store');
	define("MG_DIRECTORY_BASENAME", plugin_basename(MG_FILE));
	define("MG_DIRECTORY_PATH", plugin_dir_path(MG_FILE));
	define("MG_DIRECTORY_URL", plugins_url(null, MG_FILE));
	define("MG_STAMPCARD_DEFAULTS", [
		'mg_stamp_card_enabled' => false,
		'mg_stamp_card_stamp_count' => 6,
		'mg_stamp_card_mode' => 'order_based',
		'mg_price_based_value' => 10,
		'mg_min_order_value' => 10,
		'mg_hybrid_discount_percentage' => 10,
	]);
//////////!         STYLES AND SCRIPTS           !//////////
	add_action('wp_enqueue_scripts', function () {
		wp_enqueue_style('milg0ir-store-style', plugin_dir_url(__FILE__) . 'assets/css/main.css', array(), '1.0.0');
		wp_enqueue_script('milg0ir-store-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), '1.0.0', true);
		
		if (is_cart() || is_checkout() || is_front_page() || is_product()) {
			wp_enqueue_script('mg_cart_stamp_card_display', plugin_dir_url(__FILE__) . 'assets/js/cart_stamp_card_display.js', array('jquery'), '1.0.0', true);
			wp_localize_script('mg_cart_stamp_card_display', 'cart_params', array(
				'cart_total' => WC()->cart->get_total('edit'),
			));
		}
	});
	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_style('milg0ir-store-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), '1.0.0');
		wp_enqueue_script('milg0ir-store-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0.0', true);
	});

//////////!          ADMIN TAB / PAGE            !//////////
	add_action('admin_menu', function () {
		add_menu_page(
			__('MILG0IR ', MG_TEXT_DOMAIN),	// Page title
			__('Milg0ir', MG_TEXT_DOMAIN),	// Menu title (what appears in the sidebar)
			'manage_options',					// Capability required to access
			'mg',								// Menu slug (used in the URL)
			'page_index',						// Function to display the page content
			plugin_dir_path(__FILE__) . 'assets/images/logo/transparent/x32.webp',			// Icon for the menu item
			4									// Position in the admin sidebar (4 puts it near the top)
		);

	});

	function page_index() {
		require_once plugin_dir_path(__FILE__) . './assets/php/index.php';

	}

//////////!              TAXONOMY                !//////////
	add_action('init', function () {
		register_taxonomy('collection', array('product', 'post'), array(
			'hierarchical'		=> true, // Like categories (or false if like tags)
			'labels'			=> array(
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
			),
			'show_ui'			=> true,
			'show_admin_column'	=> true,
			'query_var'			=> true,
			'rewrite'			=> array('slug' => 'collection'),
		));
	}, 0);


//////////!           PLUGIN UPDATER             !//////////
	add_filter('plugins_api', 'get_plugin_info', 20, 3);
	add_filter('pre_set_site_transient_update_plugins', 'push_update');

	function get_plugin_info($res, $action, $args) {
		// do nothing if this is not about getting plugin information
		if ('plugin_information' !== $action) {
			return $res;
		}
	
		// do nothing if it is not our plugin
		if (plugin_basename(__FILE__) !== $args->slug) {
			return $res;
		}
	
		$remote = wp_remote_get(
			'https://raw.githubusercontent.com/MILG0IR/MILG0IR-Store-Design-Features/main/update-info.json',
			array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				)
			)
		);
	
		if (
			is_wp_error($remote) ||
			200 !== wp_remote_retrieve_response_code($remote) ||
			empty(wp_remote_retrieve_body($remote))
		) {
			return $res;
		}
	
		$remote = json_decode(wp_remote_retrieve_body($remote));
	
		$res = new stdClass();
		$res->name = $remote->name;
		$res->slug = $remote->slug;
		$res->author = $remote->author;
		$res->author_profile = $remote->author_profile;
		$res->version = $remote->version;
		$res->tested = $remote->tested;
		$res->requires = $remote->requires;
		$res->requires_php = $remote->requires_php;
		$res->download_link = $remote->download_url;
		$res->trunk = $remote->download_url;
		$res->last_updated = $remote->last_updated;
		$res->sections = array(
			'description' => $remote->sections->description,
			'installation' => $remote->sections->installation,
			'changelog' => $remote->sections->changelog
		);
	
		if (!empty($remote->sections->screenshots)) {
			$res->sections['screenshots'] = $remote->sections->screenshots;
		}
	
		$res->banners = array(
			'low' => $remote->banners->low,
			'high' => $remote->banners->high
		);
	
		error_log(print_r($res, true)); // Log the data for debugging
		return $res;
	}
	
	function push_update($transient) {
		if (empty($transient->checked)) {
			return $transient;
		}
	
		$remote = wp_remote_get(
			'https://raw.githubusercontent.com/MILG0IR/MILG0IR-Store-Design-Features/main/update-info.json',
			array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				)
			)
		);
	
		if (
			is_wp_error($remote) ||
			200 !== wp_remote_retrieve_response_code($remote) ||
			empty(wp_remote_retrieve_body($remote))
		) {
			return $transient;
		}
	
		$remote = json_decode(wp_remote_retrieve_body($remote));
	
		$current_version = get_plugin_data( __FILE__ )['Version'];

		if (
			$remote &&
			version_compare($current_version, $remote->version, '<') &&
			version_compare($remote->requires, get_bloginfo('version'), '<=') &&
			version_compare($remote->requires_php, PHP_VERSION, '<=')
		) {
			$res = new stdClass();
			$res->slug = $remote->slug;
			$res->plugin = plugin_basename(__FILE__);
			$res->new_version = $remote->version;
			$res->tested = $remote->tested;
			$res->package = $remote->download_url;
			$transient->response[$res->plugin] = $res;
		}
	
		//	print_r($res, true); // Log the data for debugging
		return $transient;
	}
	

//////////!             PLUGIN LINKS             !//////////
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');
	function add_action_links($links) {
		array_unshift($links, '<a href="/wp-admin/update-core.php" >' . __('Check for Update', 'milg0ir-store') . '</a>');
		array_unshift($links, '<a href="/wp-admin/admin.php?page=mg">' . __('Settings', 'milg0ir-store') . '</a>');
		return $links;
	}

//////////!      CUSTOM 'MY ACCOUNT' PAGES       !//////////
	add_action('init', function () {
		add_rewrite_endpoint('wishlist',	EP_ROOT | EP_PAGES);
		add_rewrite_endpoint('stampcard',	EP_ROOT | EP_PAGES);
	}, 0);

	add_filter('woocommerce_account_menu_items', function ($items) {
		$items['wishlist']		= __('Wishlist', MG_TEXT_DOMAIN);
		$items['stampcard']	= __('Stamp Card', MG_TEXT_DOMAIN);
		return $items;
	});

	add_action('woocommerce_account_wishlist_endpoint', function () {
		require_once plugin_dir_path(__FILE__) . './assets/php/wishlist.php';
	});
	add_action('woocommerce_account_stampcard_endpoint', function () {
		require_once plugin_dir_path(__FILE__) . './assets/php/stamp-card.php';
	});

//////////!            CUSTOM BLOCKS             !//////////
	add_filter('block_categories_all', function ($categories) {
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
	}, 10, 2);

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
		
		wp_register_script(
			'my-account-nav',
			plugin_dir_url(__FILE__) . 'blocks/account-nav.js',
			array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data'),
			filemtime(plugin_dir_path(__FILE__) . 'blocks/account-nav.js'),
			true
		);
		register_block_type('milg0ir/my-account-nav', array(
			'editor_script' => 'my-account-nav',
		));
	});

//////////!           CUSTOM REST API            !//////////
	add_action('rest_api_init', function () {
		register_rest_route('milg0ir/v1', '/stamp-card-data', array(
			'methods' => 'POST',
			'callback' => 'get_stamp_card_data',
			'permission_callback' => '__return_true', // Adjust this for security
		));
	});

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

//////////!          STAMP CARD OPTIONS          !//////////
	add_action('admin_init', function() {
		// Register settings for the stamp card mode and enable/disable option
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_enabled', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_stamp_card_enabled'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_stamp_count', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_stamp_card_stamp_count'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_mode', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_stamp_card_mode'] ]);
		// Register settings specific to each mode
		register_setting('mg_stamp_card_settings_group', 'mg_price_based_value', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_price_based_value'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_min_order_value', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_min_order_value'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_hybrid_discount_percentage', [ 'default' => MG_STAMPCARD_DEFAULTS['mg_hybrid_discount_percentage'] ]);

		// Add a new section to the settings page
		// This section contains the stamp card mode setting
		add_settings_section('mg_stamp_card_settings_section', 'Stamp Card Configuration', null, 'mg_stamp_card-settings');

		// Add the checkbox field to enable or disable the stamp card system
		add_settings_field(
			'mg_stamp_card_enabled_field',		// ID
			'Enable Stamp Card System',			// Title
			'mg_stamp_card_enabled_checkbox',	// Callback
			'mg_stamp_card-settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add the dropdown field to select the stamp card mode
		add_settings_field(
			'mg_stamp_card_stamp_count_field',	// ID
			'Number of stamps per card',		// Title
			'mg_stamp_card_stamp_count_input',	// Callback
			'mg_stamp_card-settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add the dropdown field to select the stamp card mode
		add_settings_field(
			'mg_stamp_card_mode_field',			// ID
			'Stamp Card Mode',					// Title
			'mg_stamp_card_mode_dropdown',		// Callback
			'mg_stamp_card-settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Price-Based mode
		add_settings_field(
			'mg_price_based_value_field',		// ID
			'Spend per stamp',					// Title
			'mg_price_based_value_input',		// Callback
			'mg_stamp_card-settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Price-Based mode
		add_settings_field(
			'mg_min_order_value_field',			// ID
			'Minimum order Value',				// Title
			'mg_min_order_value_input',			// Callback
			'mg_stamp_card-settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Hybrid mode
		add_settings_field(
			'mg_hybrid_discount_percentage_field',	// ID
			'Percentage value of order',			// Title
			'mg_hybrid_discount_percentage_input',	// Callback
			'mg_stamp_card-settings',				// Page
			'mg_stamp_card_settings_section',		// Section
		);
	});

	function mg_stamp_card_enabled_checkbox() {
		print('
			<input type="checkbox" name="mg_stamp_card_enabled" value="1" ' . (get_option('mg_stamp_card_enabled')? 'checked="checked': '') . ' />
			<label for="mg_stamp_card_enabled">Enable the stamp card system on the site.</label>
		');
	}
	function mg_stamp_card_mode_dropdown() {
		print('
			<select name="mg_stamp_card_mode" id="mg_stamp_card_mode">
				<option value="order_based" ' . (get_option('mg_stamp_card_mode') == 'order_based'? 'selected="selected"': '') . '>Order-Based</option>
				<option value="price_based" ' . (get_option('mg_stamp_card_mode') == 'price_based'? 'selected="selected"': '') . '>Price-Based</option>
				<option value="hybrid" ' . (get_option('mg_stamp_card_mode') == 'hybrid'? 'selected="selected"': '') . '>Hybrid</option>
			</select>
			<p class="description">
				Select the stamp card mode: Order-Based, Price-Based, or Hybrid. Default: ' . MG_STAMPCARD_DEFAULTS['mg_stamp_card_mode'] . '<br>
				Order-Based: this mode will add a stamp to the stamp card For each order placed.<br>
				Price-Based: this mode will add a stamp to the stamp card for each £x spent on the order.<br>
				Hybrid: this mode will add a stamp to the stamp card for each order. Each stamp has a value of x% of the order, at the end of the stamp card the customer will recieve a coupon code for the same value of the whole stamp card.
			</p>
		');
	}
	function mg_stamp_card_stamp_count_input() {
		print('
			<input type="number" name="mg_stamp_card_stamp_count" value="' . esc_attr(get_option('mg_stamp_card_stamp_count')) . '" />
			<p class="description">
				Set the number of stamps per card (Default: '. MG_STAMPCARD_DEFAULTS['mg_stamp_card_stamp_count'] .')
			</p>
		');
	}
	function mg_price_based_value_input() {
		print('
			<input type="number" class="price_based" name="mg_price_based_value" value="' . esc_attr(get_option('mg_price_based_value')) . '" />
			<p class="description">
				Set the price value for each stamp. Users can get multiple stamps per order (Default: '. esc_html($currency_symbol) . MG_STAMPCARD_DEFAULTS['mg_price_based_value'] .')
			</p>
		');
	}
	function mg_hybrid_discount_percentage_input() {
		print('
			<input type="number" class="hybrid_based" name="mg_hybrid_discount_percentage" value="' . esc_attr(get_option('mg_hybrid_discount_percentage')) . '" min="1" max="100" />
			<p class="description">
				Set the percentage for the Hybrid mode to allocate to the stamp value (Default: '.MG_STAMPCARD_DEFAULTS['mg_hybrid_discount_percentage'].'%).
			</p>
		');
	}
	function mg_min_order_value_input() {
		$currency_symbol = get_woocommerce_currency_symbol();

		print('
			<input type="number" class="min_order" name="mg_min_order_value" value="' . esc_attr(get_option('mg_min_order_value')) . '" />
			<p class="description">
				Minimum order value required to earn a stamp. (Default: '. esc_html($currency_symbol) . MG_STAMPCARD_DEFAULTS['mg_min_order_value'] .')
			</p>
		');
	}
//////////!										 !//////////