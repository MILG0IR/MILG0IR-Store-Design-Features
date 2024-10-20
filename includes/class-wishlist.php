<?php

namespace MILG0IR_Store;

class Wishlist {
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
        add_action('init', [self::class, 'add_wishlist_endpoint']);
        add_action('woocommerce_account_wishlist_endpoint', [self::class, 'wishlist_content']);
        add_filter('woocommerce_account_menu_items', [self::class, 'add_wishlist_to_account_menu']);
    }

    /**
     * Register the My-Account endpoint for Wishlist.
     */
    public static function add_wishlist_endpoint() {
        add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    }

    /**
     * Display the content for the Wishlist endpoint in the My Account section.
     */
    public static function wishlist_content() {
        require_once plugin_dir_path(__FILE__) . '../assets/php/wishlist.php';
    }

    /**
     * Add the Wishlist link to the My Account menu in WooCommerce.
     *
     * @param array $items Array of account menu items.
     * @return array Modified array of account menu items with the wishlist included.
     */
    public static function add_wishlist_to_account_menu($items) {
        $items['wishlist'] = __('Wishlist', 'MG_TEXT_DOMAIN');
        return $items;
    }
}
