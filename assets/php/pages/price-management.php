<?php
	function showUnits($input, $current) {
		// Define all units by type and category
		$all_units = [
			'weight' => [
				'Metric' => ['kg', 'g'],
				'Imperial' => ['lb', 'oz']
			],
			'length' => [
				'Metric' => ['m', 'cm', 'mm'],
				'Imperial' => ['ft', 'in']
			],
			'volume' => [
				'Metric' => ['l', 'ml'],
				'Imperial' => ['gal', 'qt']
			],
			'temperature' => [
				'Standard' => ['°C', '°F', 'K']
			],
			'currency' => [
				'Standard' => ['$', '€', '£']
			],
			'generic' => [
				'Standard' => ['Each', 'pair', 'dozen', 'box']
			]
		];

		// Split the input string into unit types
		$unit_types = array_map('trim', explode(',', $input));
		$res = '';
		$valid_unit_types = [];

		foreach ($unit_types as $type) {
			$type = match (strtolower($type)) {
				'w', 'weight', '1' => 'weight',
				'l', 'length', '2' => 'length',
				'v', 'volume', '3' => 'volume',
				't', 'temperature', '4' => 'temperature',
				'c', 'currency', '5' => 'currency',
				'g', 'generic', '6' => 'generic',
				
				default => null,
			};

			if ($type && isset($all_units[$type])) {
				$valid_unit_types[$type] = $all_units[$type];
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
			foreach ($categories as $category => $units) {
				// Add a sub optgroup for each unit type only if there are multiple types
				if ($use_sub_optgroup) {
					$res .= '<optgroup class="sub" label="' . $category . '">';
				}

				// Add each unit in the category
				foreach ($units as $unit) {
					$res .= "<option value='$unit' " . ($unit === $current ? "selected" : "") . ">$unit</option>";
				}

				// Close sub optgroup if needed
				if ($use_sub_optgroup) {
					$res .= '</optgroup>';
				}
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
				text-decoration: underline;
				color: blue;
			}
			optgroup[label].sub {
				font-weight: bold;
				text-decoration: unset;
				color: #333;
			}
			option {
				font-weight: unset;
				text-decoration: unset;
				color: #333;
			}

			/* Set the accordion container to flex */
			#mg-accordion {
				margin-top: .8rem;
				display: flex;
				flex-wrap: wrap;
				gap: .8rem; /* Adds spacing between the sections */
			}

			/* Each accordion section will have a max-width of 50% */
			#mg-accordion .mg-accordion-section {
				flex: 1 1 45%; /* Allows two sections per row with some spacing */
				max-width: 50%;
				box-sizing: border-box; /* Ensures padding doesn't increase width */
				padding: .1rem;
				background: #f7f7f7;
				border: 0.1rem solid #ccc;
				border-radius: 1rem;
			}

			/* Optional: Style the section title */
			#mg-accordion .mg-accordion-section h3 {
				font-size: 1.2rem;
				margin-bottom: 0.5rem;
			}

			.mg-options-list {
				margin-top: 1rem;
			}
			#mg-accordion .mg-accordion-section {
				padding: 1.5rem;
				border: 0.1rem solid #ccc;
				background: #f9f9f9;
			}
			.mg-options-list .mg-option-header {
				display: flex;
				align-items: center;
				gap: .5rem;
				margin-bottom: 0.5rem;
			}
			.mg-options-list .mg-option input {
				width: 40%;
			}
			.mg-options-list .mg-option-header h4 {
				width: 40%;
				padding-left: .8rem;
				margin: unset;
			}
			.mg-accordion-section .mg-remove-section,
			.mg-accordion-section .mg-section-allow-multiple {
				margin-left: .5rem;
			}
			.mg-section-allow-multiple-checkbox {
				display: none;
			}
			.mg-section-allow-multiple-checkbox:checked + label,
			.mg-section-allow-multiple-checkbox:checked + label:hover {
				background-color: rgb(170 226 177);
			}




			.ac-label {
				font-weight: 700;
				position: relative;
				padding: 0.5em 1em;
				margin-bottom: 0.5em;
				display: block;
				cursor: pointer;
				background-color: whiteSmoke;
				transition: background-color 0.15s ease-in-out;
			}

			.ac-input:checked + label,
			.ac-label:hover {
				background-color: #999;
			}

			.ac-label:after,
			.ac-input:checked + .ac-label:after {
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
			}

			.ac-label:hover:after,
			.ac-input:checked + .ac-label:after {
				background-color: #b5b5b5;
			}

			.ac-input:checked + .ac-label:after {
				content: "-";
			}

			.ac-input {
				display: none!important;
			}

			.ac-text,
			.ac-sub-text {
				opacity: 0;
				height: 0;
				margin-bottom: 0.5em;
				transition: opacity 0.5s ease-in-out;
				overflow: hidden;
			}

			.ac-input:checked ~ .ac-text,
			.ac-sub .ac-input:checked ~ .ac-sub-text {
				opacity: 1;
				height: auto;
			}

			.ac-sub .ac-label {
				background: none;
				font-weight: 600;
				padding: 0.5em 2em;
				margin-bottom: 0;
			}

			.ac-sub .ac-label:checked {
				background: none;
				border-bottom: 1px solid whitesmoke;
			}

			.ac-sub .ac-label:after,
			.ac-sub .ac-input:checked + .ac-label:after {
				left: 0;
				background: none;
			}

			.ac-sub .ac-input:checked + label,
			.ac-sub .ac-label:hover {
				background: none;
			}

			.ac-sub-text {
				padding: 0 1em 0 2em;
			}
		</style>

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
			<div class="container">
				<form method="post" action="options.php">
					<h2>Custom Price Calculator Options</h2>
					<?php
						settings_fields('mg_dynamic_price_calculator_options');
						$pricing_data = get_option('mg_dynamic_pricing_data', []);
					?>
					<button type="button" class="mg-add-section button">Add Section</button>

					<div id="mg-accordion">
						<?php
							if (!empty($pricing_data)) {
								foreach ($pricing_data as $section_index => $section) {
						?>
						<div class="mg-accordion-section" data-index="<?=$section_index?>">
							<strong>
								<input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][title]" value="<?=esc_attr($section['title'])?>" placeholder="Title">
								<button type="button" class="button mg-remove-section">Remove Section</button>
								<input type="checkbox" name="mg_dynamic_pricing_data[<?=$section_index?>][multiple]" value="1" id="mg_dynamic_pricing_data[<?=$section_index?>][multiple]" <?php echo $section['multiple'] ? 'checked' : ''; ?> style="display: none;" class="mg-section-allow-multiple-checkbox">
								<label for="mg_dynamic_pricing_data[<?=$section_index?>][multiple]" class="button mg-section-allow-multiple">Allow multiple</label>
							</strong>
							<div class="mg-options-list">
								<?php foreach ($section['options'] as $option_index => $option) { ?>
									<div class="mg-option">
										<input class="ac-input" id="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" type="checkbox" />
										<label class="ac-label" for="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]"><?=esc_attr($option['name'])?></label>

										<article class="ac-text">
										<input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" value="<?=esc_attr($option['name'])?>" placeholder="Option Name">
											<?php
												// Retrieve all stored suppliers data
												$suppliers_data = get_option('mg_suppliers_data');

												// Check if there are suppliers to display in the dropdown
												if ($suppliers_data && is_array($suppliers_data)) {
													echo '<select name="mg_dynamic_pricing_data['.$section_index.'][options]['.$option_index.'][supplier]" id="supplier_dropdown">';
													echo '<option value="">Select a Supplier</option>'; // Default empty option
													
													// Loop through each supplier and add it to the dropdown
													foreach ($suppliers_data as $index => $supplier) {
														// Ensure the supplier has a 'name' field
														$selected = esc_attr($option['supplier']) == $supplier['name']? 'selected': '';
														if (!empty($supplier['name'])) {
															// Use supplier name as the label and index as the value
															echo '<option value="' . esc_html($supplier['name']) . '" '.$selected.'>' . esc_html($supplier['name']) . '</option>';
														}
													}

													echo '</select>';
												} else {
													echo '<p>No suppliers available.</p>';
												}
											?>
											<input type="number" step="0.01" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][buyingQuantity]" value="<?=esc_attr($option['buyingQuantity'])?>" placeholder="Buying Quantity">
											<select name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][buyingQuantityUnit]">
												<?=showUnits('weight, generic', esc_attr($option['buyingQuantityUnit']))?>
											</select>
											<input type="number" step="0.00001" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][value]" value="<?=esc_attr($option['value'])?>" placeholder="Option Value">

											<a class="button mg-remove-option">Remove</a>
										</article>
									</div>
								<?php }?>
							</div>
							<button type="button" class="button mg-add-option" data-section-index="<?=$section_index?>">Add Option</button>
						</div>
						<?php
								}
							}
						?>
					</div>

					<?php submit_button()?>
				</form>
			</div>
		</div>
	</div>
</div>