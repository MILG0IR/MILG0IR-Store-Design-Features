<?php

namespace MILG0IR_Store;

class Admin_Settings {
	private static $stamp_card_defaults = [
		'mg_stamp_card_enabled' => false,
		'mg_stamp_card_mode' => 'order_based',
		'mg_price_based_value' => 10,
		'mg_min_order_value' => 10,
		'mg_hybrid_discount_percentage' => 10,
	];
    /**
     * Constructor to initialize the custom taxonomy registration process.
     */
    public static function init() {
        self::register_hooks();
    }

    /**
     * Register the necessary hooks for the custom taxonomy.
     */
    private static function register_hooks() {
		add_action('admin_menu', [self::class, 'register_admin_menu']);
		add_action('admin_init', [self::class, 'register_settings']);
	}

	/**
	 * Register the admin sidebar menu item and the page content.
	 */
	public static function register_admin_menu() {
		add_menu_page(
			__('MILG0IR', MG_TEXT_DOMAIN), // Page title
			__('Milg0ir', MG_TEXT_DOMAIN), // Menu title (what appears in the sidebar)
			'manage_options', // Capability required to access
			'mg', // Menu slug (used in the URL)
			[self::class, 'page_index'], // Function to display the page content
			plugin_dir_url(__FILE__) . '../assets/images/logo/transparent/x24.webp', // Icon for the menu item
			4 // Position in the admin sidebar
		);
	}

	/**
	 * Display the content of the admin page.
	 */
	public static function page_index() {
		require_once plugin_dir_path(__FILE__) . '../assets/php/index.php';
	}

	/**
	 * Register settings and add fields for the stamp card plugin.
	 */
	public static function register_settings() {
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_enabled', ['default' => self::$stamp_card_defaults['mg_stamp_card_enabled']]);
		register_setting('mg_stamp_card_settings_group', 'mg_stamp_card_mode', ['default' => self::$stamp_card_defaults['mg_stamp_card_mode']]);
		register_setting('mg_stamp_card_settings_group', 'mg_price_based_value', ['default' => self::$stamp_card_defaults['mg_price_based_value']]);
		register_setting('mg_stamp_card_settings_group', 'mg_min_order_value', ['default' => self::$stamp_card_defaults['mg_min_order_value']]);
		register_setting('mg_stamp_card_settings_group', 'mg_hybrid_discount_percentage', ['default' => self::$stamp_card_defaults['mg_hybrid_discount_percentage']]);

		add_settings_section('mg_stamp_card_settings_section', 'Stamp Card Configuration', null, 'mg_stamp_card-settings');

		add_settings_field('mg_stamp_card_enabled_field', 'Enable Stamp Card System', [self::class, 'mg_stamp_card_enabled_checkbox'], 'mg_stamp_card-settings', 'mg_stamp_card_settings_section');
		add_settings_field('mg_stamp_card_mode_field', 'Stamp Card Mode', [self::class, 'mg_stamp_card_mode_dropdown'], 'mg_stamp_card-settings', 'mg_stamp_card_settings_section');
		add_settings_field('mg_price_based_value_field', 'Spend per stamp', [self::class, 'mg_price_based_value_input'], 'mg_stamp_card-settings', 'mg_stamp_card_settings_section');
		add_settings_field('mg_min_order_value_field', 'Minimum Order Value', [self::class, 'mg_min_order_value_input'], 'mg_stamp_card-settings', 'mg_stamp_card_settings_section');
		add_settings_field('mg_hybrid_discount_percentage_field', 'Percentage value of order', [self::class, 'mg_hybrid_discount_percentage_input'], 'mg_stamp_card-settings', 'mg_stamp_card_settings_section');
	}

	/**
	 * Function to display the enable/disable checkbox for the stamp card system.
	 */
	public static function mg_stamp_card_enabled_checkbox() {
		echo '<input type="checkbox" name="mg_stamp_card_enabled" value="1" ' . checked(1, get_option('mg_stamp_card_enabled'), false) . ' />';
		echo '<label for="mg_stamp_card_enabled">Enable the stamp card system on the site.</label>';
	}

	/**
	 * Function to display the dropdown field for selecting the stamp card mode.
	 */
	public static function mg_stamp_card_mode_dropdown() {
		echo '<select name="mg_stamp_card_mode" id="mg_stamp_card_mode">';
		echo '<option value="order_based" ' . selected('order_based', get_option('mg_stamp_card_mode'), false) . '>Order-Based</option>';
		echo '<option value="price_based" ' . selected('price_based', get_option('mg_stamp_card_mode'), false) . '>Price-Based</option>';
		echo '<option value="hybrid" ' . selected('hybrid', get_option('mg_stamp_card_mode'), false) . '>Hybrid</option>';
		echo '</select>';
	}

	/**
	 * Function to display the input field for Price-Based Stamp Value.
	 */
	public static function mg_price_based_value_input() {
		echo '<input type="number" class="price_based" name="mg_price_based_value" value="' . esc_attr(get_option('mg_price_based_value')) . '" />';
	}

	/**
	 * Function to display the input field for Hybrid Discount Percentage.
	 */
	public static function mg_hybrid_discount_percentage_input() {
		echo '<input type="number" class="hybrid_based" name="mg_hybrid_discount_percentage" value="' . esc_attr(get_option('mg_hybrid_discount_percentage')) . '" min="1" max="100" />';
	}

	/**
	 * Function to display the input field for the minimum order value for the Order-Based Stamp Card mode.
	 */
	public static function mg_min_order_value_input() {
		$currency_symbol = get_woocommerce_currency_symbol();
		echo '<input type="number" class="min_order" name="mg_min_order_value" value="' . esc_attr(get_option('mg_min_order_value')) . '" />';
		echo '<p class="description">Minimum order value required to earn a stamp. (Default: ' . esc_html($currency_symbol) . '10)</p>';
	}
}
