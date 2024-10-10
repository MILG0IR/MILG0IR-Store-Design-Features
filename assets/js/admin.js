// Example of a basic script to add interactivity


jQuery(document).ready(function() {
    console.log('MILG0IR Store Design & Features loaded');
	// Hide all sections
	sections.forEach(section => section.classList.remove('active'));

    const sections = document.querySelectorAll('.milg0ir > .responsive-wrapper');
    const showSection = () => {
        const hash = window.location.hash.substring(1); // Get the ID from the URL (e.g., "1", "2")

		// Show the section that matches the hash in the URL
        const targetSection = document.getElementById(hash);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    };

    // Show the correct section when the page loads
    showSection();

    // Show the correct section when the URL hash changes (e.g., when clicking the links)
    window.addEventListener('hashchange', showSection);
});
