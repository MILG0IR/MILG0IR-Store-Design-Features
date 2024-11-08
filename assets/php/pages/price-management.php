
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
			.mg-options-list .mg-option,
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
			.mg-accordion-section .mg-remove-section {
				margin-left: .8rem;
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
							<strong><input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][title]" value="<?=esc_attr($section['title'])?>" placeholder="Title"><button type="button" class="button mg-remove-section">Remove Section</button></strong>
							<div class="mg-options-list">
								<div class="mg-option-header">
									<h4>Name</h4>
									<h4>Value</h4>
								</div>
								<?php foreach ($section['options'] as $option_index => $option) { ?>
									<div class="mg-accordion">
										<input class="ac-input" id="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" type="checkbox" />
										<label class="ac-label" for="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]"><?=esc_attr($option['name'])?></label>

										<article class="ac-text">
											<input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" value="<?=esc_attr($option['name'])?>" placeholder="Option Name">
											<?php
												// Retrieve all stored suppliers data
												$suppliers_data = get_option('mg_suppliers_data');

												// Check if there are suppliers to display in the dropdown
												if ($suppliers_data && is_array($suppliers_data)) {
													echo '<select name="supplier_dropdown" id="supplier_dropdown">';
													echo '<option value="">Select a Supplier</option>'; // Default empty option

													// Loop through each supplier and add it to the dropdown
													foreach ($suppliers_data as $index => $supplier) {
														// Ensure the supplier has a 'name' field
														if (!empty($supplier['name'])) {
															// Use supplier name as the label and index as the value
															echo '<option value="' . esc_attr($index) . '">' . esc_html($supplier['name']) . '</option>';
														}
													}

													echo '</select>';
												} else {
													echo '<p>No suppliers available.</p>';
												}
											?>
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