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
		<style>
			optgroup[label].parent {
				font-weight: bold;
				color: blue;
			}
			option[label] {
				font-weight: unset;
				color: black;
			}

			/* Set the accordion container to flex */
			.mg-calculator-container {
				display: grid;
				grid-template-columns: repeat(2, 1fr); /* Two columns */
				grid-template-rows: auto; /* Automatically adjust row height based on content */
				gap: 1rem; /* Space between items */
				padding-bottom: 1rem;
			}

			/* Each accordion section will have a max-width of 50% */
			.mg-calculator-container .mg-calculator-section  {
				background-color: lightgrey;
				border: 1px solid #ccc;
				text-align: center;
				transition: all 0.3s ease; /* Smooth transition for expansion */
				border-radius: 4px;
			}
			.mg-calculator-container .mg-calculator-section h3 {
				font-size: 1.2rem;
				padding: 0.5rem;
				margin: unset;
				min-height: 1.7rem;
			}
			.mg-calculator-container .mg-calculator-section .section-content {
				display: none;
			}

			.mg-calculator-container .mg-calculator-section.expanded {
				background-color: lightblue;
			}
			.mg-calculator-container .mg-calculator-section.expanded .section-content {
				padding: .5rem 1rem;
				display: block;
			}

			.mg-options-list {
				margin-top: 1rem;
			}
			.mg-options-list .mg-option-header {
				display: flex;
				align-items: center;
				gap: .5rem;
				margin-bottom: 0.5rem;
			}
			.mg-options-list .mg-option-header h4 {
				width: 40%;
				padding-left: .8rem;
				margin: unset;
			}
			.mg-calculator-section .mg-remove-section,
			.mg-calculator-section .mg-section-allow-multiple {
				margin-left: .5rem;
			}
			.mg-section-allow-multiple-checkbox {
				display: none;
			}
			.mg-section-allow-multiple-checkbox:checked + label,
			.mg-section-allow-multiple-checkbox:checked + label:hover {
				background-color: rgb(170 226 177);
			}



			.option-header {
				font-weight: 700;
				min-height: 1.2rem;
				position: relative;
				padding: 0.5em 1em;
				margin-top: 0.5em;
				display: block;
				cursor: pointer;
				background-color: whiteSmoke;
				transition: background-color 0.15s ease-in-out;
				border-radius: 4px	;
			}

			.option-toggle:checked + label,
			.option-header:hover {
				background-color: #999;
			}
			</style>
			<style name="Accordian">
			.option-header:after,
			.option-toggle:checked + .option-header:after {
				content: "+";
				position: absolute;
				display: block;
				right: 0;
				top: 0;
				width: 2em;
				height: 100%;
				line-height: 2.25em;
				text-align: center;
				background-color: #e5e5e5;
				transition: background-color 0.15s ease-in-out;
				border-radius: 0px 4px 4px 0px;
			}

			.option-header:hover:after,
			.option-toggle:checked + .option-header:after {
				background-color: #b5b5b5;
			}

			.option-toggle:checked + .option-header:after {
				content: "-";
			}

			.option-toggle {
				display: none!important;
			}

			.option-text {
				opacity: 0;
				height: 0;
				margin-bottom: 0.5em;
				transition: opacity 0.5s ease-in-out;
				overflow: hidden;
			}

			.option-toggle:checked ~ .option-text {
				opacity: 1;
				height: auto;
			}
			.option-content {
				opacity: 0;
				height: 0;
				margin-bottom: .5em;
				transition: opacity .5s ease-in-out;
				overflow: hidden;
			}
			.option-toggle:checked ~ .option-content {
				opacity: 1;
				height: auto;
			}
			</style>
			<style name="">
			.input {
				position: relative;
				margin: auto;
				width: 100%;
				max-width: 280px;
				border-radius: 3px;
				overflow: hidden;
			}
			.input .label {
				position: absolute;
				top: 0px;
				left: 12px;
				font-size: 16px;
				color: rgba(0, 0, 0, 0.5);
				font-weight: 500;
				transform-origin: 0 0;
				transform: translate3d(0, 0, 0);
				transition: all 0.2s ease;
				pointer-events: none;
				width: max-content;
			}
			.input .focus-bg {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.05);
				z-index: -1;
				transform: scaleX(0);
				transform-origin: left;
			}
			.input input,
			.input select {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				border: 0;
				font-family: inherit;
				height: 3.5rem;
				background: rgba(0, 0, 0, 0.02);
				box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.3);
				color: #000;
				transition: all 0.15s ease;
			}
			.input input:hover,
			.input select:hover {
				background: rgba(0, 0, 0, 0.04);
				box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.5);
			}
			.input input:not(:-moz-placeholder-shown) + .label {
				color: rgba(0, 0, 0, 0.5);
				transform: translate3d(0, -20px, 0) scale(0.75);
			}
			.input input:not(:-ms-input-placeholder) + .label {
				color: rgba(0, 0, 0, 0.5);
				transform: translate3d(0, -20px, 0) scale(0.75);
			}
			.input input:not(:placeholder-shown) + .label {
				color: rgba(0, 0, 0, 0.5);
				transform: translate3d(0, -20px, 0) scale(0.75);
			}
			.input input:focus,
			.input select:focus {
				background: rgba(0, 0, 0, 0.05);
				outline: none;
				box-shadow: inset 0 -2px 0 #0077FF;
			}
			.input input:focus + .label,
			.input select:focus + .label {
				color: #0077FF;
				transform: translate3d(0, -20px, 0) scale(0.75);
			}
			.input select:valid ~ .label {
				color: #0077FF;
				transform: translate3d(0, -20px, 0) scale(0.75);
			}
			.input input:focus + .label + .focus-bg,
			.input select:focus + .label + .focus-bg {
				transform: scaleX(1);
				transition: all 0.1s ease;
			}
		</style>
		<script>
		</script>
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
					settings_fields('mg_product_calculator_group');			// Register settings group
				?>
				<textarea class="currentData" hidden>
					<?=json_encode(get_option('product_calculator_data', []))?>
				</textarea>
				<h2>Custom Price Calculator Options</h2>

				<div class="mg-calculator-container"></div>

				<button type="button" class="mg-add-section button">Add Section</button>

				<?php submit_button()?>
			</form>
		</div>
	</div>
</div>