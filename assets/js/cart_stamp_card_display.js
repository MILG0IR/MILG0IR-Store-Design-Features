// Example of a basic script to add interactivity
jQuery(document).ready(function($) {
	fetchStampData();



	/**
	 * Fetches stamp data from the custom endpoint, then updates the stamp message
	 * in the block.
	 * @returns {void}
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
});