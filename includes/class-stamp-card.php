<?php

namespace MILG0IR_Store;

class Stamp_Card {
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
        add_action('init', [self::class, 'add_stamp_card_endpoint']);
        add_action('woocommerce_account_wishlist_endpoint', [self::class, 'stamp_card_content']);
        add_filter('woocommerce_account_menu_items', [self::class, 'add_stamp_card_to_account_menu']);
        add_action('rest_api_init', [self::class, 'register_api_routes']);
    }

    /**
     * Register the My-Account endpoint for Wishlist.
     */
    public static function add_stamp_card_endpoint() {
        add_rewrite_endpoint('Stamp Card', EP_ROOT | EP_PAGES);
    }

    /**
     * Display the content for the Wishlist endpoint in the My Account section.
     */
    public static function stamp_card_content() {
        require_once plugin_dir_path(__FILE__) . '../assets/php/stamp-card.php';
    }

    /**
     * Add the Wishlist link to the My Account menu in WooCommerce.
     *
     * @param array $items Array of account menu items.
     * @return array Modified array of account menu items with the wishlist included.
     */
    public static function add_stamp_card_to_account_menu($items) {
        $items['wishlist'] = __('Wishlist', 'MG_TEXT_DOMAIN');
        return $items;
    }

    /**
     * Registers the REST API routes.
     */
    public static function register_routes() {
        register_rest_route('milg0ir/v1', '/stamp-card-data', [
            'methods' => 'POST',
            'callback' => [self::class, 'get_stamp_card_data'],
            'permission_callback' => '__return_true', // Adjust this for security as needed.
        ]);
    }

    /**
     * Retrieves stamp card data based on the cart total.
     *
     * @param WP_REST_Request $request The REST API request object.
     * @return WP_REST_Response The response object.
     */
    public static function get_stamp_card_data(\WP_REST_Request $request) {
        // Retrieve the cart total from the request.
        $cart_total = $request->get_param('cart_total');

        $config = [
            'mg_stamp_card_enabled' => get_option('mg_stamp_card_enabled'),
            'mg_stamp_card_mode' => get_option('mg_stamp_card_mode'),
            'mg_min_order_value' => get_option('mg_min_order_value'),
            'mg_price_based_value' => get_option('mg_price_based_value'),
            'mg_hybrid_discount_percentage' => get_option('mg_hybrid_discount_percentage'),
        ];

        // Initialize variables for calculation.
        $number_of_stamps = 0;
        $message = '';

        if ($cart_total >= $config['mg_min_order_value']) {
            switch ($config['mg_stamp_card_mode']) {
                case 'order_based':
                    $message = 'You can earn a stamp with this order!';
                    break;
                case 'value_based':
                    $number_of_stamps = floor($cart_total / $config['mg_price_based_value']);
                    $message = "You can earn {$number_of_stamps} stamp(s) with this order!";
                    break;
                case 'hybrid':
                    $value = round(($cart_total / 100) * $config['mg_hybrid_discount_percentage'], 2);
                    $message = "Your stamp may be worth {$value} off a future order!";
                    break;
                default:
                    $message = 'Invalid stamp card mode!';
                    break;
            }
        } else {
            $difference = $config['mg_min_order_value'] - $cart_total;
            $message = "You are {$difference} away from earning a stamp!";
        }

        return new \WP_REST_Response([
            'stampsEnabled' => $config['mg_stamp_card_enabled'],
            'number_of_stamps' => $number_of_stamps,
            'message' => $message,
            'info' => [
                'cartTotal' => $cart_total,
                'config' => $config,
            ],
        ], 200);
    }
}
