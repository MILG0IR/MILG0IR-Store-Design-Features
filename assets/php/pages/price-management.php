<?php
function showSuppliers() {
	$res = '';
	// Retrieve all stored suppliers data
	$suppliers_data = get_option('mg_suppliers_data');
	// Supplier Dropdown
	if ($suppliers_data && is_array($suppliers_data)) {
		$res .= '<option value="">Select a Supplier</option>'; // Default empty option

		foreach ($suppliers_data as $index => $supplier) {
			$selected = esc_attr($option['supplier']) == $supplier['name'] ? 'selected' : '';
			if (!empty($supplier['name'])) {
				$res .= '<option value="' . esc_html($supplier['name']) . '" ' . $selected . '>' . esc_html($supplier['name']) . '</option>';
			}
		}
	} else {
		$res =  '<p>No suppliers available.</p>';
	}
	return $res;
}
function showUnits($input, $current) {
	// Define all units by type and category
	$all_units = [
		'generic' => [
			['Each', 1],
			['Pair', 2],
			['Dozen', 12]
		],
		'weight' => [
			['kg', 1000],
			['gr', 1],
			['mg', 0.1],
		],
		'length' => [
			['m', 1000],
			['cm', 1],
			['mm', 0.1],
		],
		'volume' => [
			['l', 1000],
			['cl', 1],
			['ml', 0.1],
		],
		'energy' => [
			['KiloWatt 28d', 40320],
			['KiloWatt 7d', 10080],
			['KiloWatt 24h', 1440],
			['KiloWatt 12h', 720],
			['KiloWatt Hour', 60],
			['KiloWatt Minute', 1],
		]
	];

	// Split the input string into unit types
	$unit_types = array_map('trim', explode(',', $input));
	$res = '';
	$valid_unit_types = [];
	if($unit_types[0] == 'all') {

	} else {
		foreach ($unit_types as $type) {
			$type = match (strtolower($type)) {
				'g', 'generic', '1' => 'generic',
				'w', 'weight', '2' => 'weight',
				'l', 'length', '3' => 'length',
				'v', 'volume', '4' => 'volume',
				'e', 'energy', '5' => 'energy',

				default => null,
			};

			if ($type && isset($all_units[$type])) {
				$valid_unit_types[$type] = $all_units[$type];
			}
		}
	}
	// Only add parent optgroup if multiple unit types are specified
	$use_parent_optgroup = count($valid_unit_types) > 1;

	foreach ($valid_unit_types as $type => $categories) {
		// Add a main optgroup for each unit type only if there are multiple types
		if ($use_parent_optgroup) {
			$res .= '<optgroup class="parent" label="' . ucfirst($type) . '">';
		}

		// Only add sub optgroup if multiple unit types are specified
		$use_sub_optgroup = count($categories) > 1;

		// Loop through each category within the unit type
		foreach ($categories as $category) {
			$value = $category[1];
			$key = $category[0];
			// Add each unit in the category
			$res .= "<option value='$key$value' data-key='$key' " . ($key.$value === $current ? "selected" : "") . ">$key</option>";
		}

		// Close parent optgroup if needed
		if ($use_parent_optgroup) {
			$res .= '</optgroup>';
		}
	}

	return $res;
}
?>

<div class="responsive-wrapper" id="price-management">
	<div class="main-header">
		<span>
			<h1>MILG0IR Store Designs & Features</h1>
			<h2>Price Management</h2>
		</span>
		<div class="search">
			<input type="text" placeholder="Search" />
			<button type="submit">
				<i class="dashicons dashicons-search"></i>
			</button>
		</div>
	</div>
	<div class="horizontal-tabs">
		<a href="#price-management/defaults">Defaults</a>
		<a href="#price-management/calculator">Calculator</a>
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
			<form method="post" action="options.php">
				<?php
					settings_fields('mg_pricing_settings_group');	// Register settings group
					do_settings_sections('mg_pricing_settings');	// Display settings sections
					submit_button();								// Display the save button
				?>
			</form>
		</div>
		<div class="section" id="calculator">
			<form method="post" action="options.php">
				<?php 
					//settings_fields('mg_product_calculator_group');			// Register settings group
				?>
				<textarea class="currentMaterialData" hidden>
					<?=json_encode(get_option('product_calculator_data', []))?>
				</textarea>
				<h2>Custom Price Calculator Options</h2>

				<div class="mg-masonry-container calculator-container"></div>

				<button type="button" class="mg-add-material-section button">Add Section</button>

				<?php submit_button()?>
			</form>
		</div>
	</div>
</div>