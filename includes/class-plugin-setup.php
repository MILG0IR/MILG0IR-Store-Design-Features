<?php

namespace MILG0IR_Store;

class Plugin_Setup {
	public static function init() {
		self::define_constants();
		self::load_dependencies();
		self::register_hooks();
	}

	private static function define_constants() {
		define("MG_FILE", __FILE__);
		define("MG_DIRECTORY", dirname(__FILE__));
		define("MG_TEXT_DOMAIN", 'milg0ir-store');
		define("MG_DIRECTORY_BASENAME", plugin_basename(MG_FILE));
		define("MG_DIRECTORY_PATH", plugin_dir_path(MG_FILE));
		define("MG_DIRECTORY_URL", plugins_url(null, MG_FILE));
	}

	private static function load_dependencies() {
		Admin_Settings::init();
        Blocks::init();
        Custom_Taxonomy::init();
		Endpoints::init();
		Stamp_Card::init();
		//Updater::init();
		Wishlist::init();
	}

	private static function register_hooks() {
		register_activation_hook(MG_FILE, [self::class, 'on_activation']);
		add_action('wp_enqueue_scripts', [self::class, 'enqueue_scripts']);
		add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_scripts']);
	}

	public static function on_activation() {
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
	}

	public static function enqueue_scripts() {
		wp_enqueue_style('milg0ir-store-style', plugin_dir_url(__FILE__) . '../assets/css/main.css', array(), '1.0.0');
		wp_enqueue_script('milg0ir-store-script', plugin_dir_url(__FILE__) . '../assets/js/main.js', array('jquery'), '1.0.0', true);
		if (is_cart() || is_checkout() || is_front_page() || is_product()) {
			wp_enqueue_script('mg_cart_stamp_card_display', plugin_dir_url( __FILE__ ) . '../assets/js/cart_stamp_card_display.js', array('jquery'), '1.0.0', true);
			wp_localize_script('mg_cart_stamp_card_display', 'cart_params', array(
				'cart_total' => WC()->cart->get_total('edit'),
			));
		}
	}

	public static function enqueue_admin_scripts() {
		wp_enqueue_style('milg0ir-store-admin-style', plugin_dir_url(__FILE__) . '../assets/css/admin.css', array(), '1.0.0');
		wp_enqueue_script('milg0ir-store-admin-script', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), '1.0.0', true);
	}
}
