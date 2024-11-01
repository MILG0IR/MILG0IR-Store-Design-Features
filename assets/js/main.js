jQuery(document).ready(function($) {
	/**
	 * Defines constants
	 */
		const wishlistButtonGroup = $('.mg-add-to-wishlist');
		const accountNavigation = $('.mg-account-nav');
	    const attributeSelectors = $('.variations tr td select, .variations tr td input');
	    const wishlistCheckbox = $('.mg-add-to-wishlist-group #wishlist');
	    const productId = $('input[name=product_id]').val();

	/**
	 * On page load
	 */
		fetchStampData();
		checkAttributesSelected();
	    checkWishlist();

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
		function add_to_wishlist(button) {
			if(button.hasClass('disabled')) return false;
			var productId = button.data('product-id');
			let options = getSelectedOptions();
			$.post(
				mg_localization.ajax_url, 
				{
					action: 'mg_add_to_wishlist_ajax',
					product_id: productId,
					product_options: JSON.stringify(options)
				}, 
				function(response) {
					if (response.success) {
						alert('Added to wishlist!');
						// Optionally update button UI to indicate it's in wishlist
						button.find('.wishlist-heart-icon').css('filter', 'brightness(0.5)');
					} else {
						alert('Failed to add to wishlist.');
					}
				}
			);
		}
		function checkAttributesSelected() {
			let allSelected = true;
			
			// Loop through each attribute selection in the '.variations tr td' container
			document.querySelectorAll('.variations tr td select').forEach(select => {
				if (!select.value) {
					allSelected = false;
				}
			});
			if (allSelected) {
				wishlistButtonGroup.removeClass('disabled');
			} else {
				wishlistButtonGroup.addClass('disabled');
			}
		}
		function checkWishlist() {
			const options = getSelectedOptions();
			console.log('Here');

			$.post(mg_localization.ajax_url, {
				action: 'mg_check_wishlist',
				product_id: productId,
				options: JSON.stringify(options)
			}).done(function(response) {
				if (response.success) {
					console.log('In Wishlist');
					wishlistCheckbox.prop('checked', true);
				} else {
					console.log('Not in Wishlist');
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
			wishlistButtonGroup.find('label').on('click', function() {
				add_to_wishlist($(this));
			});
		}

		if (wishlistButtonGroup.length) {
			wishlistButtonGroup.addClass('disabled');
		}

		// Add event listeners to attribute dropdowns
		$('.variations tr td').find('input, select').each(function() {
			$(this).on('change', function() {
				checkAttributesSelected();
			});
		});
		// Event listener for attribute changes
		attributeSelectors.on('change', function() {
			checkWishlist();
		});

	/**
	 * EOF
	 */
});
