<?php
	$user_id = get_current_user_id();
	$wishlist_items = mg_get_wishlist($user_id);

	if ($wishlist_items) {
		print('<div class="mg_wishlist_items"><h2>Your Wishlist</h2>');
		foreach ($wishlist_items as $item) {
			$product = new WC_product($item->product_id);
			$product_url = get_permalink($item->product_id);
			$product_options = json_decode($item->product_options, true);
			$product_image = wp_get_attachment_url($product->get_gallery_image_ids()[1]);
			$urlQueryString = '';
			$prodAttributes = '';
				
			$options = count($product_options);
			$option = 1;
			foreach ($product_options as $option_name => $option_value) {
				$urlQueryString .= $option_name.'='.$option_value.'&';
				$display_name = preg_replace('/^attribute_/', '', $option_name);
				$comma = ($option < $options) ? ', ' : '';
				$prodAttributes .='<span class="mg_wishlist_item_option" style="text-transform: capitalize;"><strong>' . esc_html($display_name) . '</strong>: ' . esc_html($option_value) . $comma . '</span>';
				$option++;
			}
			print('<a class="mg_wishlist_item" href="' . esc_url($product_url) . '?' . $urlQueryString . '"><img class="mg_wishlist_item_image" src="'.$product_image.'"><div class="mg_wishlist_item_info"><h3 class="mg_wishlist_item_title">' . get_the_title($item->product_id) . '</h3><span class="mg_wishlist_item_description">'.$product->short_description.'</span>');
			if ($product_options) {
				print("<span class=\"mg_wishlist_item_options\"> $prodAttributes </div>");
			}

			print('</a>');
		}
		print('</div>');
	} else {
		print('Your wishlist is empty.');
	}

?>