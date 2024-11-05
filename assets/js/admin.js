jQuery(document).ready(function($) {
	/*
	 * Defines constants
	 */
	const sections = $('.milg0ir > .responsive-wrapper');
    const links = $('.milg0ir > .responsive-wrapper a[href^="#"]');
    const modeDropdown = $('#mg_stamp_card_mode');
    const orderBasedFields = $('.order_based');
    const priceBasedFields = $('.price_based');
    const hybridFields = $('.hybrid_based');
	const blockListContainer = $('#block-list-container');

	// Show the correct section when the page loads
	showSection();
	toggleFields();

	/*
	 * Hooks
	 */
	$(window).on('hashchange', showSection);
    modeDropdown.on('change', toggleFields);
    $('.mg-price-calculator-section select, .mg-price-calculator-section input[type="number"]').on('change', calculateSuggestedPrice);
	$('.mg-add-section').on('click', addPriceCalculatorSection);
	$('.mg-remove-section').on('click', removePriceCalculatorSection);
	$('.mg-add-option').on('click', addPriceCalculatorOption);
	$('.mg-remove-option').on('click', removePriceCalculatorOption);

	if (blockListContainer.length) {
        const allBlocks = wp.blocks.getBlockTypes();
        const blockList = $('<ul></ul>');

        allBlocks.forEach(block => {
            blockList.append(`<li>Name: ${block.title} | Category: ${block.category} | Description: ${block.description}</li>`);
        });

        blockListContainer.append(blockList);
    }

	/*
	 * Functions
	 */
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
            links.each(function() {
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
	/* Price suggestor */
	function addPriceCalculatorSection() {
        const sectionIndex = $('#mg-accordion .mg-accordion-section').length;
        $('#mg-accordion').append(`
            <div class="mg-accordion-section" data-index="${sectionIndex}">
                <h3>Section Title: <input type="text" name="mg_dynamic_pricing_data[${sectionIndex}][title]" placeholder="Dropdown Title"></h3>
                <div class="mg-options-list"></div>
                <button type="button" class="button mg-add-option" data-section-index="${sectionIndex}">Add Option</button>
                <button type="button" class="button mg-remove-section">Remove Section</button>
            </div>
        `);
	}
	function removePriceCalculatorSection() {
        $(this).closest('.mg-accordion-section').remove();
        updateSectionIndices();
	}
	function addPriceCalculatorOption() {
        const sectionIndex = $(this).data('section-index');
        const optionsList = $(this).siblings('.mg-options-list');
        const optionCount = optionsList.children('.mg-option').length;

        optionsList.append(`
            <div class="mg-option">
                <input type="text" name="mg_dynamic_pricing_data[${sectionIndex}][options][${optionCount}][name]" placeholder="Option Name">
                <input type="number" step="0.01" name="mg_dynamic_pricing_data[${sectionIndex}][options][${optionCount}][value]" placeholder="Option Value">
                <button type="button" class="button mg-remove-option">Remove</button>
            </div>
        `);
	}
	function removePriceCalculatorOption() {
        $(this).closest('.mg-option').remove();
	}
	function updateSectionIndices() {
        $('#mg-accordion .mg-accordion-section').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('input[name^="mg_dynamic_pricing_data"]').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName);
            });
        });
    }
    function calculateSuggestedPrice() {
        let total = 0;

        $('.mg-price-calculator-section').each(function() {
            const selectedValue = parseFloat($(this).find('select').val()) || 0;
            const quantity = parseFloat($(this).find('input[type="number"]').val()) || 0;

            total += selectedValue * quantity;
        });

        $('#mg_price_calculator #mg_suggested_price').text(total.toFixed(2));
    }
});
