jQuery(document).ready(function ($) {
	/**
	 * Localization
	 */
	// Ensure the mg_analytics object exists
	if (typeof mg_analytics === 'undefined') {
		console.log("Script localization failled.");
	} else {
		console.log(mg_analytics);
	}

	/**
	 * Defines constants
	 */
	const originalPushState = history.pushState;
	const pageLoadTime = performance.now();

	/**
	 * Define the variables
	 */
	let startTime = Date.now();
	let mouseMoveTimeout;

	/**
	 * On page load
	 */
	// Send page load analytics after the page is loaded
	sendAnalytics({
		action_type:'page_load',
		viewport_width: window.innerWidth,
		viewport_height: window.innerHeight,
		page_url: window.location.href,
		load_time: pageLoadTime,
	});





// Send time spent analytics before the page unloads
window.addEventListener('beforeunload', (e) => {
    const timeSpent = (Date.now() - startTime) / 1000; // seconds
    const newPageUrl = document.activeElement?.href || ''; // Detect new page URL if applicable

    const eventData = {
        action_type: 'page_leave',
        page_url: window.location.href,
        next_page_url: newPageUrl,
        time_spent: timeSpent
    };

    // Pause navigation
    e.preventDefault();
    e.returnValue = ''; // Necessary for Chrome to pause unload

    // Send analytics and allow navigation after completion
    sendAnalytics(eventData, () => {
        // After the AJAX call completes, resume navigation
        window.location.href = newPageUrl;
    });

    // If the user isn't navigating to another page, we don't need to force reload
    if (!newPageUrl) return;

    // Timeout as a fallback to avoid blocking the user indefinitely
    setTimeout(() => {
        window.location.href = newPageUrl;
    }, 200); // Adjust timeout if necessary
});

// Track viewport size when it changes
window.addEventListener('resize', () => {
	sendAnalytics({
		action_type:'viewport_resized',
		viewport_width: window.innerWidth,
		viewport_height: window.innerHeight,

	});
});

// Track mouse movements with throttling
document.addEventListener('mousemove', (e) => {
    clearTimeout(mouseMoveTimeout);
    mouseMoveTimeout = setTimeout(() => {
        sendAnalytics({
			action_type: 'mouse_move',
			x: e.pageX,
			y: e.pageY
		});
    }, 500); // Throttle the event to avoid overwhelming the server
});

// Track mouse clicks
document.addEventListener('click', (e) => {
    sendAnalytics({
		action_type: 'mouse_click',
        x: e.pageX,
        y: e.pageY,
        element: e.target.tagName,
    });
});




	// Utility to send analytics data
	function sendAnalytics(data) {
		const payload = $.extend({
			action: 'log_analytics_data', // WordPress AJAX action name
			session_id: mg_analytics.session_id || null, // Include session ID from PHP
			user_id: mg_analytics.user_id || null, // Include user ID if logged in
			_ajax_nonce: mg_analytics.nonce || null, // WordPress nonce for security
		}, data);

		// Send the data via AJAX
		jQuery.ajax({
			url: mg_analytics.ajax_url || '/wp-admin/admin-ajax.php', // WordPress AJAX URL
			method: 'POST',
			data: payload,
			success: function (response) {
				console.log('Analytics logged:', response);
			},
			error: function (xhr, status, response) {
				console.error('Error logging analytics:', status, JSON.parse(xhr.responseText).data.message);
			}
		});
	}
})