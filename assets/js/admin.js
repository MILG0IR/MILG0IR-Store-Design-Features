jQuery(document).ready(function() {
	/*
	 * Defines constants
	 */
	const sections = document.querySelectorAll('.milg0ir > .responsive-wrapper');
	const links = document.querySelectorAll('.milg0ir > .responsive-wrapper a[href^="#"]');

	const modeDropdown = document.getElementById('mg_stamp_card_mode');
	const orderBasedFields = document.querySelectorAll('.order_based');
	const priceBasedFields = document.querySelectorAll('.price_based');
	const hybridFields = document.querySelectorAll('.hybrid_based');


	// Show the correct section when the page loads
	showSection();
	toggleFields();

	/*
	 * Hooks
	 */
	window.addEventListener('hashchange', showSection);
	modeDropdown.addEventListener('change', toggleFields);

	/*
	 * Functions
	 */
	function showSection() {
		// Hide all sections and sub-sections initially
		sections.forEach(section => {
			section.classList.remove('active');
			// Also remove 'active' from any sub-sections within each main section
			const subSections = section.querySelectorAll('.sub-section');
			subSections.forEach(subSection => subSection.classList.remove('active'));
		});
		// Remove 'active' class from all links
		links.forEach(link => link.classList.remove('active'));
		// Get the relevant sections from the URL hash
		let hash = window.location.hash.substring(1); // Remove the "#" character
		let hashParts = hash.split('/'); // Split the hash into sections (e.g., ["configuration", "stampcards"])
		// Check if there's at least one main section to show
		if (hashParts.length > 0 && hashParts[0]) {
			const mainSection = document.getElementById(hashParts[0]);
			if (mainSection) {
				mainSection.classList.add('active');
				// Handle any sub-sections if they exist
				let currentSection = mainSection;
				for (let i = 1; i < hashParts.length; i++) {
					const subSection = currentSection.querySelector(`#${hashParts[i]}`);
					if (subSection) {
						subSection.classList.add('active');
						currentSection = subSection; // Move to the next level down
					}
				}
			}
			// Mark the link that matches the current hash as active
			links.forEach(link => {
				if (link.getAttribute('href').substring(1) === hash) {
					link.classList.add('active');
				} else if (link.getAttribute('href').substring(1) === hashParts[0]) {
					// Also mark the main section link as active if no sub-section is specified
					link.classList.add('active');
				}
			});
		}
	};
	function toggleFields() {
		const selectedMode = modeDropdown.value;

		// Hide or show fields based on the selected mode
		orderBasedFields.forEach(field => field.closest('tr').style.display = (selectedMode === 'order_based') ? 'table-row' : 'none');
		priceBasedFields.forEach(field => field.closest('tr').style.display = (selectedMode === 'price_based') ? 'table-row' : 'none');
		hybridFields.forEach(field => field.closest('tr').style.display = (selectedMode === 'hybrid') ? 'table-row' : 'none');
	}

	const blockListContainer = document.getElementById('block-list-container');
	if (blockListContainer) {
		// Retrieve all registered blocks using wp.blocks.getBlockTypes()
		const allBlocks = wp.blocks.getBlockTypes();

		// Create a list element to display the blocks
		const blockList = document.createElement('ul');

		allBlocks.forEach(block => {
			const listItem = document.createElement('li');
			listItem.textContent = `Name: ${block.title} | Category: ${block.category} | Description: ${block.description}`;
			blockList.appendChild(listItem);
		});

		// Append the list to the container
		blockListContainer.appendChild(blockList);
	}
});
