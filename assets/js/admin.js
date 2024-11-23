jQuery(document).ready(function ($) {
	/**
	 * Localization
	 */
	// Ensure the mg_localization object exists
	if (typeof mg_localization === 'undefined') {
		console.log("Script localization failled.");
	}
	/**
	 * Defines constants
	 */
	const sections = $('.milg0ir > .responsive-wrapper');
	const links = $('.milg0ir > .responsive-wrapper a[href^="#"]');
	const modeDropdown = $('#mg_stamp_card_mode');
	const orderBasedFields = $('.order_based');
	const priceBasedFields = $('.price_based');
	const hybridFields = $('.hybrid_based');

	/**
	 * Define the variables
	 */
	let supplierSectionCounter = 0,
		materialSectionCounter = 0;

	// Show the correct section when the page loads
	if ($('main.milg0ir').length > 0) {
		showSection();
		toggleFields();

		autoPopulateMaterialSections();
		autoPopulateSupplierSections();
	}
	if ($('#mg_price_calculator').length > 0) {
		calculateSuggestedPrice();
	}

	/**
	 * Hooks
	 */
	$(window).on('hashchange', showSection);
	modeDropdown.on('change', toggleFields);
	$('.mg-price-calculator-section select, .mg-price-calculator-section input[type="number"]').on('change', calculateSuggestedPrice);

	$('.mg-add-supplier-section').on('click', () => { addNewSupplierSection() });
	$('.mg-add-material-section').on('click', () => { addNewMaterialOption(addNewMaterialSection()) });
	$('.header-nav-btn').on('click', () => { $('.header-navigation').toggleClass('open') });
	$('.header-navigation-links a').on('click', () => { $('.header-navigation').removeClass('open') });
	$('.option-buyingPrice, .option-retailUnit, .option-buyingUnit').on('input', calculateRetailPrice);

	/**
	 * Observers
	 */

	// Create a ResizeObserver
	const resizeObserver = new ResizeObserver((entries) => {
		entries.forEach((entry) => {
			const $section = $(entry.target); // Convert to jQuery object
			updateSectionRows($section);
		});
	});
	/**
	 * Functions
	 */
	function toggleNav() {
		;
	}
	function showSection() {
		sections.removeClass('active').find('.section').removeClass('active');
		links.removeClass('active');

		let hash = window.location.hash.substring(1);
		let hashParts = hash.split('/');

		if (hashParts.length > 0 && hashParts[0]) {
			const mainSection = $(`#${hashParts[0]}`);
			if (mainSection.length) {
				mainSection.addClass('active');
				let currentSection = mainSection;
				// If no subsection is in the URL, Enable the one with the class 'default'.
				if (hashParts.length === 1) {
					const defaultSubSection = currentSection.find('.default');
					if (defaultSubSection.length) {
						defaultSubSection.addClass('active');
						currentSection = defaultSubSection;
					}
				} else {
					for (let i = 1; i < hashParts.length; i++) {
						const subSection = currentSection.find(`#${hashParts[i]}`);
						if (subSection.length) {
							subSection.addClass('active');
							currentSection = subSection;
						}
					}
				}
			}
			links.each(function () {
				if ($(this).attr('href').substring(1) === hash || $(this).attr('href').substring(1) === hashParts[0]) {
					$(this).addClass('active');
				}
			});
		} else {
			window.location.replace(window.location + '#summary');
		}
	}
	function toggleFields() {
		const selectedMode = modeDropdown.val();
		orderBasedFields.closest('tr').toggle(selectedMode === 'order_based');
		priceBasedFields.closest('tr').toggle(selectedMode === 'price_based');
		hybridFields.closest('tr').toggle(selectedMode === 'hybrid');
	}
	/* Suppliers - Admin page */
	function addNewSupplierSection() {
		let name= 'mg_suppliers_data['+supplierSectionCounter+']';

		const newSection = $('<div>', { class: 'mg-masonry-section', 'data-index': supplierSectionCounter }).append(
			$('<h3>', {
				class: 'section-title',
				text: 'Section ' + supplierSectionCounter,
			}),
			$('<content>', { class: 'section-content' }).append(

				$('<label>', { class: 'input', for: name+'[title]' }).append(
					$('<input>', { class: 'section-title-edit', name: name+'[name]', id: name+'[name]', type: 'text', placeholder: 'Section ' + supplierSectionCounter, value: 'Section ' + supplierSectionCounter, required: true }),
					$('<span>', { class: 'label', text: 'Name' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-nametag' })
					)
				),

				$('<input>', { class: 'remove-section button', type: 'button', value: 'Remove this Supplier' }),
				$('<div>', { class: 'options-container' }).append(

					$('<input>', { class: 'option-key', name: name + '[key]', id: name + '[key]', type: 'text', hidden: true }),

					$('<label>', { class: 'input', for: name + '[phone]' }).append(
						$('<input>', { class: 'option-phone', name: name + '[phone]', id: name + '[phone]', type: 'phone', placeholder: ' ' }),
						$('<span>', { class: 'label', text: 'Phone Number' }),
						$('<button>', { type: 'submit' }).append(
							$('<i>', { class: 'dashicons dashicons-phone' }),
						)
					),

					$('<label>', { class: 'input', for: name + '[email]' }).append(
						$('<input>', { class: 'option-email', name: name + '[email]', id: name + '[email]', type: 'email', placeholder: ' ' }),
						$('<span>', { class: 'label', text: 'E-Mail Address' }),
						$('<button>', { type: 'submit' }).append(
							$('<i>', { class: 'dashicons dashicons-email' }),
						)
					),

					$('<label>', { class: 'input', for: name + '[url]' }).append(
						$('<input>', { class: 'option-url', name: name + '[url]', id: name + '[url]', type: 'url', placeholder: ' ' }),
						$('<span>', { class: 'label', text: 'Website' }),
						$('<button>', { type: 'submit' }).append(
							$('<i>', { class: 'dashicons dashicons-admin-site-alt3' }),
						)
					),

					$('<label>', { class: 'input', for: name + '[description]' }).append(
						$('<input>', { class: 'option-description', name: name + '[description]', id: name + '[description]', type: 'text', placeholder: ' ' }),
						$('<span>', { class: 'label', text: 'Breif Description' }),
						$('<button>', { type: 'submit' }).append(
							$('<i>', { class: 'dashicons dashicons-welcome-write-blog' }),
						)
					)
				)
			)
		)

		// Attach listeners specifically to the newly added section
		newSection.find('.remove-section').on('click', removeSection);
		newSection.find('.add-option').on('click', (addNewMaterialOption));
		newSection.find('.section-title').on('click', toggleSection);

		newSection.find('.section-title-edit').on('input', function () {
			const newTitle = $(this).val(); // Get the new title from the input field
			newSection.find('.section-title').text(newTitle); // Update the title
		});

		// Append the new section to the container
		$('.supplier-container').append(newSection);

		// Increment the counter every time a new section is added
		supplierSectionCounter++;
		return newSection;
	}
	function autoPopulateSupplierSections() {

		// Get the JSON data from the hidden input field
		let sectionsJSON = $('.currentSupplierData').val();

		// Parse the JSON data into an object
		let sectionsData = JSON.parse(sectionsJSON);


		// Loop through each section in the JSON data
		sectionsData.forEach((section, sectionIndex) => {
			let newSection = addNewSupplierSection();
			newSection.find('.section-title').text(section.name);
			newSection.find('.section-title-edit').val(section.name);

			// Loop through each key-value pair in the section object
			Object.keys(section).forEach((key) => {
				$(newSection).find('.option-' + key).val(section[key]); // Now assign the class and value to the input elements
			});
		});
	}
	/* Price Suggestion Calculator - Admin page */
	function addNewMaterialSection() {
		let name = 'product_calculator_data[' + materialSectionCounter + ']'
		const newSection = $('<div>', { class: 'mg-masonry-section', 'data-index': materialSectionCounter }).append(
			$('<h3>', {
				class: 'section-title',
				text: 'Section ' + materialSectionCounter,
			}),
			$('<content>', { class: 'section-content' }).append(

				$('<label>', { class: 'input', for: 'section_' + materialSectionCounter + '_title' }).append(
					$('<input>', { class: 'section-title-edit', type: 'text', placeholder: ' ', value: 'Section ' + materialSectionCounter, name: name + '[name]' }),
					$('<span>', { class: 'label', text: 'Name' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-nametag' }),
					)
				),

				$('<div>', { class: 'buttons-container' }).append(
					$('<input>', { class: 'remove-section button', type: 'button', value: 'Remove the section', style: 'width: 50%' }),
					$('<input>', { class: 'add-option button', type: 'button', value: 'Add an option', style: 'width: 50%' }),
					$('<label>', { class: 'toggle', for: name + '[multiple]', text: 'Allow multiple' }).append(
						$('<input>', { class: 'allow-multiple button', type: 'checkbox', name: name + '[multiple]', id: name + '[multiple]' }),
						$('<span>', { }),
					),
				),
				$('<div>', { class: 'options-container' })
			)
		)

		// Attach listeners specifically to the newly added section
		newSection.find('.remove-section').on('click', removeSection);
		newSection.find('.add-option').on('click', (addNewMaterialOption));
		newSection.find('.section-title').on('click', toggleSection);

		newSection.find('.section-title-edit').on('input', function () {
			const newTitle = $(this).val(); // Get the new title from the input field
			newSection.find('.section-title').text(newTitle); // Update the title
		});

		// Append the new section to the container
		$('.calculator-container').append(newSection);

		// Increment the counter every time a new section is added
		materialSectionCounter++;
		return newSection;
	}
	function toggleSection() {
		$(this).closest('.mg-masonry-section').toggleClass('expanded'); // Toggle expanded class
	}
	function removeSection() {
		$(this).closest('.mg-masonry-section').remove(); // Remove the parent section
	}
	function addNewMaterialOption(parentElement) {
		let parent;

		// Determine the parent container
		if (typeof parentElement.type === 'undefined') {
			parent = $(parentElement).find('.add-option'); // If called via event, "this" is the clicked element
		} else {
			parent = $(parentElement.target); // Explicit parent element provided
		}

		let sectionIndex = parent.closest('.mg-masonry-section').data('index')
		let optionIndex = parent.parent().siblings('.options-container').children().length;
		let name = 'product_calculator_data[' + sectionIndex + '][options][' + optionIndex + ']';

		// Create the new option element
		let newOption = $('<div>', { class: 'option' }).append(
			$('<input>', { type: 'checkbox', class: 'option-toggle', id: name + '[open]', name: name + '[open]', hidden: true }),
			$('<label>', { text: 'Option ' + optionIndex, class: 'option-header', for: name + '[open]' }),
			$('<article>', { class: 'option-content' }).append(
				$('<input>', { class: 'option-key', name: name + '[key]', id: name + '[key]', type: 'hidden', value: optionIndex }),

				$('<label>', { class: 'input', for: name + '[name]' }).append(
					$('<input>', { class: 'option-name', name: name + '[name]', id: name + '[name]', type: 'text', value: 'Option ' + optionIndex, placeholder: ' ', required: true }),
					$('<span>', { class: 'label', text: 'Name' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-nametag' }),
					)
				),

				$('<label>', { class: 'input', for: name + '[supplier]' }).append(
					$('<select>', { class: 'option-supplier', name: name + '[supplier]', id: name + '[supplier]', type: 'text', required: true }).append(
						// Add a placeholder option
						$('<option>', { value: '', text: '', selected: true, disabled: true }),
						// Append options dynamically from mg_localization.suppliers
						mg_localization.suppliers.map(supplier =>
							$('<option>', { value: supplier.id, text: supplier.name || 'Unknown' })
						)
					),
					$('<span>', { class: 'label', text: 'Supplier' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-archive' }),
					)
				),

				$('<label>', { class: 'input', for: name + '[buyingUnit]' }).append(
					$('<select>', { class: 'option-buyingUnit', name: name + '[buyingUnit]', type: 'text', required: true }).append(
						// Add a placeholder option
						$('<option>', { value: '', text: '', selected: true, disabled: true }),
						// Append options dynamically from mg_localization.unit
						Object.entries(mg_localization.units).map(([category, unitList]) =>
							$('<optgroup>', { label: category }).append(
								unitList.map(unit =>
									$('<option>', { value: unit[0] + unit[1], 'data-key': unit[0], text: unit[0] })
								)
							)
						)
					),
					$('<span>', { class: 'label', text: 'Buying Unit' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-performance' }),
					)
				),

				$('<label>', { class: 'input', for: name + '[retailUnit]' }).append(
					$('<select>', { class: 'option-retailUnit', name: name + '[retailUnit]', type: 'text', required: true }).append(
						// Add a placeholder option
						$('<option>', { value: '', text: '', selected: true, disabled: true }),
						// Append options dynamically from mg_localization.unit
						Object.entries(mg_localization.units).map(([category, unitList]) =>
							$('<optgroup>', { label: category }).append(
								unitList.map(unit =>
									$('<option>', { value: unit[0] + unit[1], 'data-key': unit[0], text: unit[0] })
								)
							)
						)
					),
					$('<span>', { class: 'label', text: 'Retail Unit' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-performance' }),
					)
				),

				$('<label>', { class: 'input', for: name + '[buyingPrice]' }).append(
					$('<input>', { class: 'option-buyingPrice', name: name + '[buyingPrice]', type: 'text', placeholder: ' ', required: true }),
					$('<span>', { class: 'label', text: 'Buying Price' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-money' }),
					)
				),

				$('<label>', { class: 'input', for: name + '[retailPrice]' }).append(
					$('<input>', { class: 'option-retailPrice', name: name + '[retailPrice]', type: 'hidden' }),
					$('<input>', { class: 'option-retailPrice', name: name + '[retailPrice]', type: 'text', placeholder: ' ', disabled: true }),
					$('<span>', { class: 'label', text: 'Retail Price' }),
					$('<button>', { type: 'submit' }).append(
						$('<i>', { class: 'dashicons dashicons-money' }),
					)
				),

				$('<a>', { class: 'option-remove button', text: 'Remove' }),
			)
		)
		// Append the new option to the options container
		parent.parent().siblings('.options-container').append(newOption);

		// Attach event listeners to the new option
		newOption.find('.option-name').on('input', function () {
			const newTitle = $(this).val(); // Get the new title from the input field
			newOption.find('.option-header').text(newTitle); // Update the title
		});

		newOption.find('.option-remove').on('click', function () {
			newOption.remove(); // Remove the option
		});
		newOption.find('.mg-price-calculator-section select, .mg-price-calculator-section input[type="number"]').on('click', calculateSuggestedPrice)
		return newOption;
	}
	function autoPopulateMaterialSections() {

		// Get the JSON data from the hidden input field
		let sectionsJSON = $('.currentMaterialData').val();

		// Parse the JSON data into an object
		let sectionsData = JSON.parse(sectionsJSON);


		// Loop through each section in the JSON data
		sectionsData.forEach((section, sectionIndex) => {
			let newSection = addNewMaterialSection();
			newSection.find('.section-title').text(section.name);
			newSection.find('.section-title-edit').val(section.name);
			newSection.find('.allow-multiple').attr('checked', section.multiple=='on'?true:false);

			section.options.forEach((option, optionIndex) => {
				let newOption = addNewMaterialOption(newSection);
				$(newOption).find('.option-header').text(option.name);
				Object.keys(option).forEach((key) => {
					console.log(`Key: ${key}, Value: ${option[key]}`);
					$(newSection).find('.option-' + key).val(option[key]); // Now assign the class and value to the input elements
				});
			});
		});
	}
	/* Auto resizing sections */
	function updateSectionRows($section) {
		if ($section.hasClass("expanded")) {
			// Get the section height in pixels
			const sectionHeightPx = $section.outerHeight();
			// Convert rem to pixels for calculation
			const remToPx = parseFloat(getComputedStyle(document.documentElement).fontSize);
			// Calculate the number of rows (round up to ensure it fits)
			const rows = Math.ceil(sectionHeightPx / (5 * remToPx));

			// Set the grid-row CSS property
			$section.css("grid-row", `span ${rows}`);
		} else {
			// If not expanded, default to 1 row
			$section.css("grid-row", "span 1");
		}
	};
	/* Calculate the material retail price */
	function calculateRetailPrice() {
		// Find the closest form section (container) to ensure calculations are material-specific
		let $section = $(this).closest('article'); // assuming <article> wraps each material's form

		// Get the buying unit and buying value (price)
		let buyingUnit = $section.find('.option-buyingUnit').val();
		let buyingPrice = parseFloat($section.find('.option-buyingPrice').val()) || 0;

		// Get the retail unit
		let retailUnit = $section.find('.option-retailUnit').val();

		// Extract the numeric conversion factors from the selected values
		let buyingUnitFactor = extractConversionFactor(buyingUnit);
		let retailUnitFactor = extractConversionFactor(retailUnit);

		// Calculate retail unit price based on the formula
		let retailPrice = (buyingPrice / buyingUnitFactor) * retailUnitFactor;

		// Update the retail value field
		$section.find('.option-retailPrice').val(retailPrice.toFixed(5));
	}
	function extractConversionFactor(unitValue) {
		let match = unitValue.match(/(\d+)$/); // Match the number at the end (e.g., 1000 in kg1000)
		if (match) {
			return parseInt(match[0], 10); // Return the number as an integer
		} else {
			return 1; // If no match, default to 1 (assuming no conversion needed)
		}
	}
	/* Price Suggestion Calculator - Product page */
	function calculateSuggestedPrice() {
		let total = 0;
		let margin = $('.mg_pricing_margin').val();

		$('.mg-price-calculator-section').each(function () {
			if ($(this).find('select').length > 0) {
				let key = $(this).find('select option:selected').data('key') + '';
				let fullValue = $(this).find('select').val() || 0;
				let selectedValue = fullValue.slice(key.length);
				let quantity = parseFloat($(this).find('input[type="number"]').val()) || 0;

				total += selectedValue * quantity;
			}
		});
		let retailPrice = (total / (100 - margin)) * 100;
		$('#mg_price_calculator #mg_suggested_price').text(retailPrice.toFixed(2));
	}
	/* Modals with responses */



	$(".mg-masonry-section").each(function () {
		updateSectionRows($(this)); // Initial calculation
		resizeObserver.observe(this); // Observe changes
	});
});
