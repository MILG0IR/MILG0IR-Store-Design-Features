<div class="responsive-wrapper" id="summary">
	<div class="main-header">
		<span>
			<h1>MILG0IR Store Designs & Features</h1>
			<h2>Summary</h2>
		</span>
		<div class="search">
			<input type="text" placeholder="Search" />
			<button type="submit">
				<i class="dashicons dashicons-search"></i>
			</button>
		</div>
	</div>
	<div class="horizontal-tabs">
	</div>
	<div class="content-header" style="display: none;">
		<div class="content-header-intro">
			<h2></h2>
			<p></p>
		</div>
		<div class="content-header-actions">
		</div>
	</div>
	<div class="content">
		<div class="section default" id="defaults">
			<div class="mg-settings-container">
				<?php
				print(display_settings_summary("mg_stamp_card_", "Stamp Card"));
				print(display_settings_summary("mg_wishlist_", "Wishlist"));
				print(display_settings_summary("mg_taxonomies_", "Taxonomies"));

				// Retrieve all stored settings for 'mg_pricing_settings'
				$pricing_settings = get_option('product_calculator_data');
				// Check if there are settings to display
				if ($pricing_settings && is_array($pricing_settings)) {
					echo '<div class="mg-settings-summary">';
					echo '<h2>Pricing Settings Summary</h2>';

					// Loop through each section and display its options
					foreach ($pricing_settings as $section) {
						print('<h4>' . esc_html($section['name']) . ': </h3><table><tbody>');
						// Display each option and its value within the section
						if (!empty($section['options']) && is_array($section['options'])) {
							foreach ($section['options'] as $option) {
								print('<tr>');
								print('<td><strong>' . esc_html($option['name']) . '</strong></td>');
								print('<td>' . get_woocommerce_currency_symbol() . esc_html($option['retailPrice']) . '</td>');
								print('</tr>');
							}
						}
						print('</tbody></table>');
					}
					print('</div>');

				} else {
					echo '<p>No pricing settings have been configured yet.</p>';
				}

				// Retrieve all stored settings for 'mg_suppliers_data'
				$suppliers_data = get_option('mg_suppliers_data');

				// Check if there are suppliers to display
				if ($suppliers_data && is_array($suppliers_data)) {
					echo '<div class="mg-settings-summary">';
					echo '<h2>Suppliers Summary</h2>';

					// Loop through each supplier and display its details
					foreach ($suppliers_data as $supplier) {
						// Display supplier name as a header if available
						if (!empty($supplier['name'])) {
							echo '<h4>' . esc_html($supplier['name']) . '</h4>';
						}

						// Display each key-value pair in the supplier data
						echo '<table>';
						echo '<tbody>';

						foreach ($supplier as $field => $value) {
							// Skip displaying the 'name' field again in the table
							if ($field === 'name') continue;

							// Capitalize the first letter of the field name for display as a label
							$label = ucfirst(str_replace('_', ' ', $field));
							echo '<tr>';
							echo '<td><strong>' . esc_html($label) . ':</strong></td>';
							echo '<td>' . esc_html($value) . '</td>';
							echo '</tr>';
						}

						echo '</tbody>';
						echo '</table>';
						echo '<hr>'; // Optional divider between suppliers
					}

					echo '</div>';
				} else {
					echo '<p>No suppliers have been added yet.</p>';
				}

				?>
			</div>
		</div>
	</div>
</div>