<?php

namespace MILG0IR_Store;

class Endpoints {
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
		add_action('rest_api_init', [self::class, 'register_routes']);
    }

	private static function register_routes() {
		register_rest_route('milg0ir/v1', '/stamp-card-data', array(
			'methods' => 'POST',
			'callback' => 'get_stamp_card_data',
			'permission_callback' => '__return_true', // Adjust this for security
		));
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
}