<div class="responsive-wrapper" id="configuration">
	<div class="main-header">
		<span>
			<h1>MILG0IR Store Designs & Features</h1>
			<h2>Configuration</h2>
		</span>
		<div class="search">
			<input type="text" placeholder="Search" />
			<button type="submit">
				<i class="dashicons dashicons-search"></i>
			</button>
		</div>
	</div>
	<div class="horizontal-tabs">
		<a href="#configuration/stampcard">Stamp Card</a>
		<a href="#configuration/wishlist">Wishlist</a>
		<a href="#configuration/taxonomies">Taxonomies</a>
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
		<div class="section default" id="stampcard">
			<form method="post" action="options.php">
				<?php
				settings_fields('mg_stamp_card_settings_group');	// Register settings group
				do_settings_sections('mg_stamp_card_settings');		// Display settings sections
				submit_button();									// Display the save button
				?>
			</form>
		</div>
		<div class="section" id="wishlist">
			<form method="post" action="options.php">
				<?php
				settings_fields('mg_wishlist_settings_group');	// Register settings group
				do_settings_sections('mg_wishlist_settings');	// Display settings sections
				submit_button();								// Display the save button
				?>
			</form>
		</div>
		<div class="section" id="taxonomies">
			<form method="post" action="options.php">
				<?php
				settings_fields('mg_taxonomies_settings_group');	// Register settings group
				do_settings_sections('mg_taxonomies_settings');	// Display settings sections
				submit_button();								// Display the save button
				?>
			</form>
		</div>
	</div>
</div>