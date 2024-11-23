<?php
/**
 * Plugin Name: MILG0IR Store Design & Features
 * Description: Enhances store functionality and design for MILG0IR stores.
 * Plugin URI:	https://github.com/MILG0IR/MILG0IR-Store-Design-Features
 * Version:		0.0.7
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
		'enabled' => false,
		'stamp_count' => 6,
		'mode' => 'order_based',
		'price_based_value' => 10,
		'min_order_value' => 10,
		'hybrid_discount_percentage' => 10,
	]);
	define("MG_WISHLIST_DEFAULTS", [
		'enabled' => true
	]);
	define("MG_TAXONOMIES_DEFAULTS", [
		'enabled' => true,
		'max' => 100,
		'total_qty' => 1
	]);
	define("MG_PRICING_DEFAULTS", [
		'margin' => 20
	]);
	define('UNIT_OPTIONS', [
		'generic' => [
			['Each', 1],
			['Pair', 2],
			['Dozen', 12]
		],
		'weight' => [
			['kg', 1000],
			['gr', 1],
			['mg', 0.1],
		],
		'length' => [
			['m', 1000],
			['cm', 1],
			['mm', 0.1],
		],
		'volume' => [
			['l', 1000],
			['cl', 1],
			['ml', 0.1],
		],
		'energy' => [
			['KiloWatt 28d', 40320],
			['KiloWatt 7d', 10080],
			['KiloWatt 24h', 1440],
			['KiloWatt 12h', 720],
			['KiloWatt Hour', 60],
			['KiloWatt Minute', 1],
		]
	]);
//////////!         STYLES AND SCRIPTS           !//////////
	add_action('wp_enqueue_scripts', function () {
		wp_enqueue_style('milg0ir-store-style', plugin_dir_url(__FILE__) . 'assets/css/main.css', array(), '1.0.0');
		wp_enqueue_style('milg0ir-store-icons', plugin_dir_url(__FILE__) . 'assets/css/icons.css', array(), '1.0.0');

		wp_enqueue_script('milg0ir-analytics-script', plugin_dir_url(__FILE__) . 'assets/js/analytics.js', array('jquery'), '1.0.0', true);
		wp_localize_script('milg0ir-analytics-script', 'mg_analytics', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('analytics_nonce'),
			'session_id' => wp_get_session_token(), // Ensure this is correctly set or handled
			'user_id' => get_current_user_id() ?: 0, // If no user logged in, send 0
		]);
		wp_enqueue_script('milg0ir-store-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), '1.0.0', true);
		wp_localize_script('milg0ir-store-script', 'mg_localization', [
			'stampCardEnabled' => get_option('mg_stamp_card_enabled'),
			'wishlistEnabled' => get_option('mg_wishlist_enabled'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		if (is_cart() || is_checkout() || is_front_page() || is_product()) {
			wp_enqueue_script('mg_cart_stamp_card_display', plugin_dir_url(__FILE__) . 'assets/js/cart_stamp_card_display.js', array('jquery'), '1.0.0', true);
			wp_localize_script('mg_cart_stamp_card_display', 'cart_params', array(
				'cart_total' => WC()->cart->get_total('edit'),
			));
		}
	});

	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_style('milg0ir-store-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), '1.0.0');
		wp_enqueue_style('milg0ir-store-icons', plugin_dir_url(__FILE__) . 'assets/css/icons.css', array(), '1.0.0');

		wp_enqueue_script('milg0ir-store-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0.0', true);
		wp_localize_script('milg0ir-store-admin-script', 'mg_localization', [
			'suppliers' => get_option('mg_suppliers_data', []),
			'units' => UNIT_OPTIONS
		]);
	});

//////////!          ADMIN TAB / PAGE            !//////////
	add_action('admin_menu', function () {
		add_menu_page(
			__('MILG0IR ', MG_TEXT_DOMAIN),		// Page title
			__('Milg0ir', MG_TEXT_DOMAIN),		// Menu title (what appears in the sidebar)
			'manage_options',					// Capability required to access
			'mg',								// Menu slug (used in the URL)
			'page_index',						// Function to display the page content
			plugin_dir_path(__FILE__) . 'assets/images/logo/transparent/x128.webp',			// Icon for the menu item
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
					'icon'  => plugin_dir_path(__FILE__) . 'assets/images/logo/transparent/x64.webp',
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
			'account-nav',
			plugin_dir_url(__FILE__) . 'blocks/account-nav.js',
			array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data'),
			filemtime(plugin_dir_path(__FILE__) . 'blocks/account-nav.js'),
			true
		);
		wp_localize_script('account-nav', 'mg_localization', [
			'stampCardEnabled' => get_option('mg_stamp_card_enabled'),
			'wishlistEnabled' => get_option('mg_wishlist_enabled'),
		]);
		register_block_type('milg0ir/account-nav', array(
			'editor_script' => 'account-nav',
		));

	});
//////////!               DATABASE               !//////////
	function mg_get_existing_schema($table_name) {
		global $wpdb;
		$result = $wpdb->get_row("SHOW CREATE TABLE $table_name", ARRAY_A);
		if ($result) {
			return $result['Create Table'];
		} else {
			return null;
		}
	}
	function mg_parse_create_table_sql($schema) {
		$columns = [];
		preg_match_all('/^\s*`(\w+)`\s+[^,]+/m', $schema, $matches);
		if (!empty($matches[1])) {
			foreach ($matches[1] as $index => $col_name) {
				$columns[$col_name] = trim($matches[0][$index]);
			}
		}
		return $columns;
	}
	function mg_update_database($desired_schema, $existing_schema) {
		global $wpdb;

		// If the table does not exist, create it
		if (!$existing_schema) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($desired_schema);
			return;
		}

		// Compare column definitions
		$desired_columns = mg_parse_create_table_sql($desired_schema);
		$current_columns = mg_parse_create_table_sql($existing_schema);

		// Generate ALTER TABLE statements for missing or modified columns
		$alter_statements = [];
		foreach ($desired_columns as $col_name => $col_def) {
			if (!isset($current_columns[$col_name])) {
				$alter_statements[] = "ADD COLUMN $col_def";
			} elseif ($current_columns[$col_name] !== $col_def) {
				$alter_statements[] = "MODIFY COLUMN $col_def";
			}
		}

		// Execute ALTER TABLE statements if any differences were found
		if (!empty($alter_statements)) {
			$sql = "ALTER TABLE $table_name " . implode(', ', $alter_statements);
			$wpdb->query($sql);
		}
	}

//////////!              STAMP CARD              !//////////
	function update_stampcard_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_stampcards';
		$charset_collate = $wpdb->get_charset_collate();

		$existing_schema = mg_get_existing_schema($table_name);
		$desired_schema = "CREATE TABLE `$table_name` (
			`id` BIGINT(20) UNSIGNED AUTO_INCREMENT,
			`user_id` BIGINT(20) UNSIGNED NOT NULL,
			`order_id` BIGINT(20) UNSIGNED DEFAULT NULL,
			`total_stamps` INT(11) NOT NULL,
			`date_earned` DATETIME DEFAULT CURRENT_TIMESTAMP,
			`stamp_value` DECIMAL(10, 2) DEFAULT 0.00,
			`redeemed` BOOLEAN DEFAULT FALSE,
			UNIQUE KEY user_stamp_unique (`user_id`, `order_id`),
			PRIMARY KEY (`id`),
			FOREIGN KEY (`user_id`) REFERENCES `wp_users` (`ID`) ON DELETE CASCADE
		) $charset_collate;";
		if($existing_schema == null) {
			$wpdb->query($desired_schema);
		} else {
			mg_update_database($desired_schema, $existing_schema);
		}
	}
	register_activation_hook(__FILE__, 'update_stampcard_table');

//////////!          STAMP CARD OPTIONS          !//////////
	add_action('admin_init', function() {
		// Register settings for the stamp card mode and enable/disable option
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_enabled', [ 'default' => MG_STAMPCARD_DEFAULTS['enabled'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_stamp_count', [ 'default' => MG_STAMPCARD_DEFAULTS['stamp_count'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_mode', [ 'default' => MG_STAMPCARD_DEFAULTS['mode'] ]);
		// Register settings specific to each mode
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_price_based_value', [ 'default' => MG_STAMPCARD_DEFAULTS['price_based_value'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_min_order_value', [ 'default' => MG_STAMPCARD_DEFAULTS['min_order_value'] ]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_hybrid_discount_percentage', [ 'default' => MG_STAMPCARD_DEFAULTS['hybrid_discount_percentage'] ]);

		// Add a new section to the settings page
		// This section contains the stamp card mode setting
		add_settings_section('mg_stamp_card_settings_section', 'Stamp Card Configuration', null, 'mg_stamp_card_settings');

		// Add the checkbox field to enable or disable the stamp card system
		add_settings_field(
			'mg_stamp_card_enabled_field',		// ID
			'Enable Stamp Card System',			// Title
			'mg_stamp_card_enabled_checkbox',	// Callback
			'mg_stamp_card_settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add the dropdown field to select the stamp card mode
		add_settings_field(
			'mg_stamp_card_stamp_count_field',	// ID
			'Number of stamps per card',		// Title
			'mg_stamp_card_stamp_count_input',	// Callback
			'mg_stamp_card_settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add the dropdown field to select the stamp card mode
		add_settings_field(
			'mg_stamp_card_mode_field',			// ID
			'Stamp Card Mode',					// Title
			'mg_stamp_card_mode_dropdown',		// Callback
			'mg_stamp_card_settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Price-Based mode
		add_settings_field(
			'mg_stamp_card_price_based_value_field',		// ID
			'Spend per stamp',					// Title
			'mg_stamp_card_price_based_value_input',		// Callback
			'mg_stamp_card_settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Price-Based mode
		add_settings_field(
			'mg_stamp_card_min_order_value_field',			// ID
			'Minimum order Value',				// Title
			'mg_stamp_card_min_order_value_input',			// Callback
			'mg_stamp_card_settings',			// Page
			'mg_stamp_card_settings_section',	// Section
		);
		// Add settings specific to the Hybrid mode
		add_settings_field(
			'mg_stamp_card_hybrid_discount_percentage_field',	// ID
			'Percentage value of order',			// Title
			'mg_stamp_card_hybrid_discount_percentage_input',	// Callback
			'mg_stamp_card_settings',				// Page
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
					Select the stamp card mode: Order-Based, Price-Based, or Hybrid. Default: ' . MG_STAMPCARD_DEFAULTS['mode'] . '<br>
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
					Set the number of stamps per card (Default: '. MG_STAMPCARD_DEFAULTS['stamp_count'] .')
				</p>
			');
	}
	function mg_stamp_card_price_based_value_input() {
		print('
				<input type="number" class="price_based" name="mg_stamp_card_price_based_value" value="' . esc_attr(get_option('mg_stamp_card_price_based_value')) . '" />
				<p class="description">
					Set the price value for each stamp. Users can get multiple stamps per order (Default: '. esc_html($currency_symbol) . MG_STAMPCARD_DEFAULTS['price_based_value'] .')
				</p>
			');
	}
	function mg_stamp_card_hybrid_discount_percentage_input() {
		print('
				<input type="number" class="hybrid_based" name="mg_stamp_card_hybrid_discount_percentage" value="' . esc_attr(get_option('mg_stamp_card_hybrid_discount_percentage')) . '" min="1" max="100" />
				<p class="description">
					Set the percentage for the Hybrid mode to allocate to the stamp value (Default: '.MG_STAMPCARD_DEFAULTS['hybrid_discount_percentage'].'%).
				</p>
			');
	}
	function mg_stamp_card_min_order_value_input() {
		$currency_symbol = get_woocommerce_currency_symbol();
		print('
				<input type="number" class="min_order" name="mg_stamp_card_min_order_value" value="' . esc_attr(get_option('mg_stamp_card_min_order_value')) . '" />
				<p class="description">
					Minimum order value required to earn a stamp. (Default: '. esc_html($currency_symbol) . MG_STAMPCARD_DEFAULTS['min_order_value'] .')
				</p>
			');
	}

//////////!         STAMP CARD REST API          !//////////
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
//////////!               WISHLIST               !//////////
	function update_wishlist_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_wishlists';
		$charset_collate = $wpdb->get_charset_collate();

		$existing_schema = mg_get_existing_schema($table_name);
		$desired_schema = "CREATE TABLE `$table_name` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) unsigned NOT NULL,
			`product_id` bigint(20) unsigned NOT NULL,
			`product_options` text,
			`date_added` datetime DEFAULT current_timestamp(),
			`date_removed` datetime DEFAULT NULL,
			`product_options` TEXT,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`user_id`) REFERENCES `wp_users` (`ID`) ON DELETE CASCADE
		) $charset_collate;";
		if($existing_schema == null) {
			$wpdb->query($desired_schema);
		} else {
			mg_update_database($desired_schema, $existing_schema);
		}
	}
	register_activation_hook(__FILE__, 'update_wishlist_table');

	function mg_get_wishlist($user_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_wishlists';

		return $wpdb->get_results($wpdb->prepare(
			"SELECT product_id, product_options FROM $table_name WHERE user_id = %d", 
			$user_id
		));
	}

	function mg_add_to_wishlist($user_id, $product_id, $product_options = null) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_wishlists';

		// Check if the item with options already exists
		$exists = $wpdb->get_var($wpdb->prepare(
			"SELECT `id` FROM `$table_name` WHERE `user_id`=%d AND `product_id`=%d AND `product_options`='%s'",
			$user_id, 
			$product_id,
			json_encode($product_options)
		));

		if (!$exists) {
			$wpdb->insert(
				$table_name,
				array(
					'user_id' => $user_id,
					'product_id' => $product_id,
					'product_options' => json_encode($product_options)
				),
				array(
					'%d',
					'%d',
					'%s'
				)
			);
		}
	}
	function mg_add_to_wishlist_ajax() {
		$user_id = get_current_user_id();
		$product_id = intval($_POST['product_id']);
		$product_options = isset($_POST['product_options']) ? json_decode(stripslashes($_POST['product_options']), true) : [];
	
		if ($user_id && $product_id && $product_options) {
			mg_add_to_wishlist($user_id, $product_id, $product_options);
			wp_send_json_success('Added to wishlist!');
		} else {
			wp_send_json_error('Failed to add to wishlist.');
		}
	}
	add_action('wp_ajax_mg_add_to_wishlist_ajax', 'mg_add_to_wishlist_ajax');

	function mg_remove_from_wishlist($user_id, $product_id, $product_options = null) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_wishlists';

		// Check if the item with options exists
		$exists = $wpdb->get_var($wpdb->prepare(
			"SELECT `id` FROM `$table_name` WHERE `user_id`=%d AND `product_id`=%d AND `product_options`='%s'",
			$user_id, 
			$product_id,
			json_encode($product_options)
		));

		if ($exists) {
			$wpdb->delete(
				$table_name,
				array(
					'user_id' => $user_id,
					'product_id' => $product_id,
					'product_options' => json_encode($product_options)
				),
				array(
					'%d',
					'%d',
					'%s'
				)
			);
		}
	}
	function mg_remove_from_wishlist_ajax() {
		$user_id = get_current_user_id();
		$product_id = intval($_POST['product_id']);
		$product_options = isset($_POST['product_options']) ? json_decode(stripslashes($_POST['product_options']), true) : [];

		if ($user_id && $product_id && $product_options) {
			mg_remove_from_wishlist($user_id, $product_id, $product_options);
			wp_send_json_success('Removed from wishlist!');
		} else {
			wp_send_json_error('Failed to remove from wishlist.');
		}
	}
	add_action('wp_ajax_mg_remove_from_wishlist_ajax', 'mg_remove_from_wishlist_ajax');

	function mg_check_wishlist($user_id, $product_id, $product_options = null) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_wishlists';
		$is_in_wishlist = $wpdb->get_var($sql_prep = $wpdb->prepare(
			"SELECT COUNT(*) FROM `$table_name` WHERE `user_id`=%d AND `product_id`=%d AND `product_options`='%s'",
			$user_id, 
			$product_id,
			json_encode($product_options)
		));

		if ($is_in_wishlist > 0) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}
	function mg_check_wishlist_ajax() {
		$user_id = get_current_user_id();
		$product_id = intval($_POST['product_id']);
		$product_options = isset($_POST['product_options']) ? json_decode(stripslashes($_POST['product_options']), true) : [];
	
		if ($user_id && $product_id && $product_options) {
			mg_check_wishlist($user_id, $product_id, $product_options);
			wp_send_json_success('Item is in the wishlist');
		} else {
			wp_send_json_error('Item is not in the wishlist');
		}
	}
	add_action('wp_ajax_mg_check_wishlist', 'mg_check_wishlist_ajax');

	function mg_wishlist_button() {
		global $product;
		$product_id = $product->get_id(); // Unique ID for each product's wishlist button
	
		// Output a heart icon with a unique ID for each product
		print('
			<div class="mg-add-to-wishlist-group">
				<input class="mg-add-to-wishlist" id="wishlist-' . esc_attr($product_id) . '" name="wishlist-' . esc_attr($product_id) . '" title="Add to Wish List" type="checkbox"/>
				<label class="mg-add-to-wishlist-icon" for="wishlist-' . esc_attr($product_id) . '" data-hover-text="Wish List" style="vertical-align: middle;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
						<g transform="translate(0,-952.36218)">
							<path style="color:#000000;enable-background:accumulate;" d="m 34.166665,972.36218 c -11.41955,0 -19.16666,8.91891 -19.16666,20.27029 0,19.45943 15,27.56753 35,39.72973 20.00001,-12.1622 34.99999,-20.2703 34.99999,-39.72973 0,-11.35137 -7.7471,-20.27029 -19.16665,-20.27029 -7.35014,0 -13.39148,4.05405 -15.83334,6.48647 -2.44185,-2.43241 -8.48319,-6.48647 -15.83334,-6.48647 z" fill="transparent" id="heart-path" stroke="#737373" stroke-width="5"/>
						</g>
					</svg>
				</label>
			</div>
	
			<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function() {
					const wishlistCheckbox = document.getElementById("wishlist-' . esc_attr($product_id) . '");
					
					// Prevent page scroll and form submission
					wishlistCheckbox.addEventListener("click", function(event) {
						event.preventDefault();
						event.stopPropagation();
	
						// Toggle the checkbox state manually
						wishlistCheckbox.checked = !wishlistCheckbox.checked;
	
						// Custom function to handle wishlist adding/removing logic
						handleWishlistToggle(wishlistCheckbox.checked, ' . esc_js($product_id) . ');
					});
				});
	
				function handleWishlistToggle(isChecked, productId) {
					// Implement AJAX or other logic here to add/remove item from wishlist
					console.log("Wishlist toggled for product ID:", productId, "Checked:", isChecked);
				}
			</script>
		');
	}
	add_action('woocommerce_after_add_to_cart_button', 'mg_wishlist_button', 10);
	

//////////!           WISHLIST OPTIONS           !//////////
	add_action('admin_init', function() {
		// Register settings for the stamp card mode and enable/disable option
		register_setting('mg_wishlist_settings_group', 'mg_wishlist_enabled', [ 'default' => MG_WISHLIST_DEFAULTS['enabled'] ]);

		// Add a new section to the settings page
		// This section contains the stamp card mode setting
		add_settings_section('mg_wishlist_settings_section', 'Wishlist Configuration', null, 'mg_wishlist_settings');

		// Add the checkbox field to enable or disable the stamp card system
		add_settings_field(
			'mg_wishlist_enabled_field',	// ID
			'Enable Wishlist System',		// Title
			'mg_wishlist_enabled_checkbox',	// Callback
			'mg_wishlist_settings',			// Page
			'mg_wishlist_settings_section',	// Section
		);
	});

	function mg_wishlist_enabled_checkbox() {
		print('
				<input type="checkbox" name="mg_wishlist_enabled" value="1" ' . (get_option('mg_wishlist_enabled')? 'checked="checked': '') . ' />
				<label for="mg_wishlist_enabled">Enable the wishlist system on the site.</label>
			');
	}

//////////!           PRICE CALCULATOR           !//////////
	function mg_price_calculator_meta_box() {
		add_meta_box(
			'mg_price_calculator',		// Unique ID for the box
			'Custom Price Calculator',	// Box title
			'mg_price_calculator',		// Content callback
			'product',					// Post type
			'side',						// Context (side for sidebar)
			'default'					// Priority
		);
	}
	add_action('add_meta_boxes', 'mg_price_calculator_meta_box');

	function mg_price_calculator($post) {
		$post_id = $post->ID;
		$pricing_data = get_option('product_calculator_data'); // Retrieve all options saved in admin settings
		print('
			<style>
				.mg-price-calculator-section label h4 {
					margin-bottom: 5px;
					text-transform: capitalize;
				}
				.mg-price-calculator-input {
					display: flex;
					align-items: center;
				}
				.mg-price-calculator-input > select {
					width: 40%;
				}
				.mg-price-calculator-input > input {
					width: 60%;
				}
			</style>
		');
		

		if (!empty($pricing_data)) {
			foreach ($pricing_data as $index => $section) {
				print('
				<div class="mg-price-calculator-section">
					<label for="mg_price_calculator_' . urlencode($section['name']) . '"><h4>' . esc_html($section['name']) . ': <span class="dashicons dashicons-info-outline" style="color: grey;"></span>');
				if($section['multiple']) {
					print('<span style="float: right;" class="dashicons dashicons-plus-alt" style="color: grey;"></span>');
				}
				print('
					</h4></label>
					<div class="mg-price-calculator-input">
						<select id="mg_price_calculator_' . urlencode($section['name']) . '" name="mg_price_calculator_' . urlencode($section['name']) . '">
							<option>Select</option>');

							foreach ($section['options'] as $option) {
								// Get the saved value from post meta, with the format 'key.value'
								$saved_value = get_post_meta($post_id, 'mg_price_calculator_' . urlencode($section['name']), true);

								// Generate the current option value in the same 'key.value' format
								$option_value = $option['key'] . $option['retailPrice'];

								// Compare and mark as selected if it matches the saved value
								$selected = ($saved_value == $option_value) ? 'selected' : '';

								// Output the option with the key, value, and selected status
								print('<option value="' . urlencode($option_value) . '" data-key="' . urlencode($option['key']) . '" ' . $selected . '>' . esc_html($option['name']) . '</option>');
							}
				$quantity = get_post_meta($post_id, 'mg_quantity_'.urlencode($section['name']), true);
				$quantity = !empty($quantity) ? $quantity : esc_html($option['default']);
				print('</select><input type="number" id="mg_quantity_' . urlencode($section['name']) . '" name="mg_quantity_' . urlencode($section['name']) . '" placeholder="Enter quantity" min="0" step="0.01" value="'.$quantity.'" ></div></div>');
			}
		} else {
			print('<p>No options configured in the settings.</p>');
		}

		print('<div class="mg-price-calculator-section"><h4>Margin %: </h4><input type="number" class="mg_pricing_margin" name="mg_pricing_margin" placeholder="Enter margin" min="0" step=".01" value="'.get_option('mg_pricing_margin').'"></div>');
		print('<p><strong>Suggested Price: </strong>'.$currency_symbol.'<h1 id="mg_suggested_price">0.00</h1></p>');

		wp_nonce_field('mg_save_price_calculator_data', 'mg_price_calculator_nonce');
	}

	function mg_save_price_calculator_data($post_id, $post) {
		// Check if it's a product
		if ('product' !== $post->post_type) {
			return;
		}
	
		// Verify nonce if added to form (optional but recommended)
		if (!isset($_POST['mg_price_calculator_nonce']) || !wp_verify_nonce($_POST['mg_price_calculator_nonce'], 'mg_save_price_calculator_data')) {
			return;
		}
	
		// Get all pricing data settings
		$pricing_data = get_option('product_calculator_data'); // Options set in the admin
	
		if (!empty($pricing_data)) {
			foreach ($pricing_data as $section) {
				// Retrieve the option selected and quantity for each section
				$option_key = 'mg_price_calculator_' . urlencode($section['name']);
				$quantity_key = 'mg_quantity_' . urlencode($section['name']);
	
				// Check if the option and quantity fields were submitted
				if (isset($_POST[$option_key]) && isset($_POST[$quantity_key])) {
					$selected_option = sanitize_text_field($_POST[$option_key]);
					$quantity = intval($_POST[$quantity_key]);
	
					// Save the selected option and quantity as meta fields
					update_post_meta($post_id, $option_key, $selected_option);
					update_post_meta($post_id, $quantity_key, $quantity);
				}
			}
		}
	
		// Save margin if provided
		if (isset($_POST['mg_pricing_margin'])) {
			$margin = floatval($_POST['mg_pricing_margin']);
			update_post_meta($post_id, 'mg_pricing_margin', $margin);
		}
	}
	add_action('save_post_product', 'mg_save_price_calculator_data', 10, 2);

//////////!       PRICE CALCULATOR OPTIONS       !//////////
	add_action('admin_init', function () {
		register_setting('mg_product_calculator_group', 'product_calculator_data');

		register_setting('mg_suppliers_options', 'mg_suppliers_data');

		register_setting('mg_pricing_settings_group', 'mg_pricing_margin', [ 'default' => MG_PRICING_DEFAULTS['margin'] ]);

		add_settings_section('mg_pricing_settings_section', 'Default pricing configuration', null, 'mg_pricing_settings');

		add_settings_field(
			'mg_pricing_margin_field',		// ID
			'Target Margin',				// Title
			'mg_pricing_margin_checkbox',	// Callback
			'mg_pricing_settings',			// Page
			'mg_pricing_settings_section',	// Section
		);
	});

	function mg_pricing_margin_checkbox() {
		print('
			<input type="number" name="mg_pricing_margin" value="' . urlencode(get_option('mg_pricing_margin')) . '" />
			<p class="description">
				Set the margin percentage to add onto the price (Default: '. MG_PRICING_DEFAULTS['margin'] .')
			</p>
		');
	}
//////////!             TAXONOMIES               !////////// TODO: Custom taxonomies,
//////////!          TAXONOMIES OPTIONS          !////////// create up to 100 seperate taxonomies
	add_action('admin_init', function() {
		// Register settings for the stamp card mode and enable/disable option
		register_setting('mg_taxonomies_settings_group', 'mg_taxonomies_enabled', [ 'default' => MG_TAXONOMIES_DEFAULTS['enabled'] ]);
		register_setting('mg_taxonomies_settings_group', 'mg_taxonomies_total_qty', [ 'default' => MG_TAXONOMIES_DEFAULTS['total_qty'] ]);

		// Add a new section to the settings page
		// This section contains the stamp card mode setting
		add_settings_section('mg_taxonomies_settings_section', 'Taxonomies Configuration', null, 'mg_taxonomies_settings');

		// Add the checkbox field to enable or disable the stamp card system
		add_settings_field(
			'mg_taxonomies_enabled_field',		// ID
			'Enable Custom Taxonomies',			// Title
			'mg_taxonomies_enabled_checkbox',	// Callback
			'mg_taxonomies_settings',			// Page
			'mg_taxonomies_settings_section',	// Section
		);

		// Add the checkbox field to enable or disable the stamp card system
		add_settings_field(
			'mg_taxonomies_total_qty_field',	// ID
			'Total number of cutom taxonomies',	// Title
			'mg_taxonomies_total_qty_input',	// Callback
			'mg_taxonomies_settings',			// Page
			'mg_taxonomies_settings_section',	// Section
		);
	});

	function mg_taxonomies_enabled_checkbox() {
		print('
				<input type="checkbox" name="mg_taxonomies_enabled" value="1" ' . (get_option('mg_taxonomies_enabled')? 'checked="checked"': '') . ' />
				<label for="mg_taxonomies_enabled">Enable the custom taxonomies system on the site. (Default: '.MG_TAXONOMIES_DEFAULTS['enabled'].').</label>
			');
	}
	function mg_taxonomies_total_qty_input() {
		print('
				<input type="number" name="mg_taxonomies_total_qty" value="' . get_option('mg_taxonomies_total_qty') . '" min="1" max="'.MG_TAXONOMIES_DEFAULTS['total_qty'].'" />
				<p class="description">
					How many custom taxonomies would you like? (Default: '.MG_TAXONOMIES_DEFAULTS['total_qty'].', Max: '.MG_TAXONOMIES_DEFAULTS['total_qty'].'). 
				</p>
			');
	}
//////////!               ANALYTICS              !//////////
	function update_analytics_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mg_user_analytics';
		$charset_collate = $wpdb->get_charset_collate();

		$existing_schema = mg_get_existing_schema($table_name);
		$desired_schema = "CREATE TABLE `$table_name`(
			`id` BIGINT AUTO_INCREMENT,
			`session_id` VARCHAR(64) NOT NULL NULL,
			`user_id` BIGINT(20) UNSIGNED NULL NULL,
			`timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, NULL
			`action_type` VARCHAR(50) NOT NULL NULL,
			`page_url` TEXT NULL,
			`next_page_url` TEXT NULL,
			`page_load_time` FLOAT NULL,
			`time_spent` FLOAT NULL,
			`mouse_pos_x` INT(20) NULL,
			`mouse_pos_y` INT(20) NULL,
			`viewport_height` FLOAT NULL,
			`viewport_width` FLOAT NULL,
			`additional_data` LONGTEXT NULL,
			PRIMARY KEY (`id`),
			FOREIGN KEY (`user_id`) REFERENCES `wp_users` (`ID`) ON DELETE CASCADE
		) $charset_collate;";
		mg_update_database($desired_schema, $existing_schema);
	}
	register_activation_hook(__FILE__, 'update_analytics_table');

//////////!          ANALYTICS OPTIONS           !//////////
//////////!          ANALYTICS REST API          !//////////	
	function log_analytics_data() {
		global $wpdb;
		check_ajax_referer('analytics_nonce', '_ajax_nonce');

		$session_id = sanitize_text_field($_POST['session_id'] ?? null);
		$user_id = intval($_POST['user_id'] ?? null);
		$action_type = sanitize_text_field($_POST['action_type'] ?? null);
		$page_url = esc_url_raw($_POST['page_url'] ?? null);
		$next_page_url = esc_url_raw($_POST['next_page_url'] ?? null);
		$page_load_time = floatval($_POST['page_load_time'] ?? null);
		$time_spent = floatval($_POST['time_spent'] ?? null);
		$mouse_pos_x = intval($_POST['x'] ?? null);
		$mouse_pos_y = intval($_POST['y'] ?? null);
		$viewport_height = floatval($_POST['viewport_height'] ?? null);
		$viewport_width = floatval($_POST['viewport_width'] ?? null);
		$next_page_url = esc_url_raw($_POST['next_page_url'] ?? null);
		$additional_data = isset($_POST['additional_data']) 
			? wp_json_encode(sanitize_text_field(wp_unslash($_POST['additional_data']))) 
			: null;

			// Validate the additional data JSON
		if (isset($_POST['additional_data'])) {
			$decoded_data = json_decode(stripslashes($_POST['additional_data']), true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				wp_send_json_error('Invalid JSON in additional data.', 400);
			}
			$additional_data = wp_json_encode($decoded_data);
		} else {
			$additional_data = null;
		}
		// Insert into your analytics table
		$res = $wpdb->insert(
			"{$wpdb->prefix}mg_user_analytics",
			[
				'session_id' => $session_id,
				'user_id' => $user_id,
				'action_type' => $action_type,
				'page_url' => $page_url,
				'next_page_url' => $next_page_url,
				'page_load_time' => $page_load_time,
				'time_spent' => $time_spent,
				'mouse_pos_x' => $mouse_pos_x,
				'mouse_pos_y' => $mouse_pos_y,
				'viewport_height' => $viewport_height,
				'viewport_width' => $viewport_width,
				'additional_data' => $additional_data,
			],
			['%s', '%d', '%s', '%s', '%s', '%f', '%f', '%d', '%d', '%f', '%f', '%s', '%s']
		);
		if ($res === false) {
			$error_message = $wpdb->last_error ? $wpdb->last_error : 'Failed to log analytics data';
			wp_send_json_error(['message' => $error_message], 500);
		}
		wp_send_json_success('Analytics data logged.');
	}
	add_action('wp_ajax_log_analytics_data', 'log_analytics_data');
	add_action('wp_ajax_nopriv_log_analytics_data', 'log_analytics_data');
//////////!										 !//////////