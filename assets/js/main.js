jQuery(document).ready(function($) {
	/**
	 * Defines constants
	 */
		const
			accountNavigation = $('.mg-account-nav'),
			attributeSelectors = $('.variations tr td select, .variations tr td input'),
			productID = $('input[name=product_id]').val(),
			wishlistButtonGroup = $('.mg-add-to-wishlist-group'),
			wishlistCheckbox = $('.mg-add-to-wishlist-group .mg-add-to-wishlist');

	/**
	 * On page load
	 */
		fetchStampData();
		checkAttributesSelected();

	/**
	 * Functions
	 */
		function fetchStampData() {
			// Check if the cart_params object is available
			if (typeof cart_params !== 'undefined' && cart_params !== null) {
				// Proceed to make your AJAX request using the cart total
				$.ajax({
					url: '/wp-json/milg0ir/v1/stamp-card-data',
					method: 'POST',
					data: {
						cart_total: cart_params.cart_total
					},

					success: function(response) {
						//console.log(response.message);
						$('.wp-block-milg0ir-stamp-card-preview-block').find('p').text(response.message);
					},
					error: function(xhr, status, error) {
						console.error('Error fetching stamp data:', error);
					}
				});
			} else {
				console.log('WooCommerce cart parameters are not available.');
			}
		}
		function getSelectedOptions() {
			let options = {};
			$('.variations tr td').find('input, select').each(function() {
				let optionName = $(this).attr('name');
				let optionValue = $(this).val();

				if (optionName && optionValue) {
					options[optionName] = optionValue;
				}
			});
			return options;
		}
		function add_to_wishlist() {
			let options = getSelectedOptions();
			$.post(
				mg_localization.ajax_url, 
				{
					action: 'mg_add_to_wishlist_ajax',
					product_id: productID,
					product_options: JSON.stringify(options)
				}, 
				function(response) {
					checkAttributesSelected();
				}
			);
		}
		function remove_from_wishlist() {
			let options = getSelectedOptions();
			$.post(
				mg_localization.ajax_url, 
				{
					action: 'mg_remove_from_wishlist_ajax',
					product_id: productID,
					product_options: JSON.stringify(options)
				}, 
				function(response) {
					checkAttributesSelected();
				}
			);
		}
		function checkAttributesSelected() { 
			let allSelected = true;
		
			// Iterate through each attribute selector using jQuery's .each()
			attributeSelectors.each(function() {
				const value = $(this).val();
				if (!value) { // If value is not selected (empty or null)
					allSelected = false;
					return false; // Break the loop if any attribute is not selected
				}
			});
		
			// Enable/disable the wishlist button based on attribute selection
			if (allSelected) {
				wishlistCheckbox.prop('disabled', false);
				checkWishlist(); // Run the wishlist check
			} else {
				wishlistCheckbox.prop('disabled', true).prop('checked', false);
			}
		}
		function checkWishlist() {
			const options = getSelectedOptions();

			$.post(mg_localization.ajax_url, {
				action: 'mg_check_wishlist',
				product_id: productID,
				product_options: JSON.stringify(options)
			}).done(function(response) {
				if (response.success) {
					wishlistCheckbox.prop('checked', true);
				} else {
					wishlistCheckbox.prop('checked', false);
				}
			}).fail(function() {
				console.log("Wishlist check failed.");
			});
		}
	

	/**
	 * Logic
	 */

		if (accountNavigation.length > 0) {
			if(!mg_localization.stampCardEnabled) {
				$('.mg-account-nav .mg-navigation-link[name=stamp-card]').hide();
			}
			if(!mg_localization.wishlistEnabled) {
				$('.mg-account-nav .mg-navigation-link[name=wishlist]').hide();
			}
		}
		if (wishlistButtonGroup.length) {
			wishlistCheckbox.on('change', function() {
				if ($(this).is(':checked')) {
					console.log('Adding to wishlist');
					add_to_wishlist($(this));
				} else {
					console.log('Removing from wishlist');
					remove_from_wishlist($(this));
				}
			});
			// Event listener for attribute changes
			attributeSelectors.on('change', function() {
				checkAttributesSelected();
			});
		}

	/**
	 * EOF
	 */
});
