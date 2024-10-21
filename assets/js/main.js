jQuery(document).ready(function() {
	/*
	* Defines constants
	*/

	/*
	* Hooks
	*/
	if (jQuery('.mg-account-nav').length > 0) {
		if(!mg_localization.stampCardEnabled) {
			jQuery('.mg-account-nav .mg-navigation-link[name=stamp-card]').hide();
		}
		if(!mg_localization.wishlistEnabled) {
			jQuery('.mg-account-nav .mg-navigation-link[name=wishlist]').hide();
		}
	}


	/*
	* Functions
	*/

});
