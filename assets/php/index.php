<?php
	function display_settings_summary($prefix, $title) {
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like($prefix) . '%'),
			ARRAY_A
		);

		$res = '<div class="mg-settings-summary">';
		$res .= '<h2>' . esc_html($title) . ' Settings Summary</h2>';

		if ($results) {
			$res .= '<table>';
			$res .= '<thead><tr><th>Setting</th><th>Value</th></tr></thead>';
			$res .= '<tbody>';

			foreach ($results as $row) {
				$res .= '<tr>';
				$res .= '<td><strong>' . esc_html(str_replace($prefix, '', $row['option_name'])) . '</strong></td>';
				$res .= '<td>' . esc_html($row['option_value']) . '</td>';
				$res .= '</tr>';
			}

			$res .= '</tbody>';
			$res .= '</table>';
		} else {
			$res .= '<p>No settings found for this group.</p>';
		}

		$res .= '</div>';
		
		
		return $res;
	}
	function get_admin_colors() {
		// Get the current user's admin color scheme
		$admin_color = get_user_option('admin_color');
	
		// Define color schemes with actual color values
		$colors = [
			'fresh' => [
				'background' => '#1d2327',
				'primary' => '#2c3338',
				'secondary' => '#2271b1',
				'tertiary' => '#72aee6',
				'text' => '#333333',
			],
			'light' => [
				'background' => '#e5e5e5',
				'primary' => '#999',
				'secondary' => '#d64e07',
				'tertiary' => '#04a4cc',
				'text' => '#333333',
			],
			'modern' => [
				'background' => '#1e1e1e',
				'primary' => '#3858e9',
				'secondary' => '#33f078',
				'tertiary' => '',
				'text' => '#333333',
			],
			'blue' => [
				'background' => '#096484',
				'primary' => '#4796b3',
				'secondary' => '#52accc',
				'tertiary' => '#74B6CE',
				'text' => '#333333',
			],
			'coffee' => [
				'background' => '#46403c',
				'primary' => '#59524c',
				'secondary' => '#c7a589',
				'tertiary' => '#9ea476',
				'text' => '#333333',
			],
			'ectoplasm' => [
				'background' => '#413256',
				'primary' => '#523f6d',
				'secondary' => '#a3b745',
				'tertiary' => '#d46f15',
				'text' => '#333333',
			],
			'midnight' => [
				'background' => '#25282b',
				'primary' => '#363b3f',
				'secondary' => '#69a8bb',
				'tertiary' => '#e14d43',
				'text' => '#333333',
			],
			'ocean' => [
				'background' => '#627c83',
				'primary' => '#738e96',
				'secondary' => '#9ebaa0',
				'tertiary' => '#aa9d88',
				'text' => '#333333',
			],
			'sunrise' => [
				'background' => '#b43c38',
				'primary' => '#cf4944',
				'secondary' => '#dd823b',
				'tertiary' => '#ccaf0b',
				'text' => '#333333',
			],
		];
	
		// Return the color scheme based on user preference, defaulting to 'fresh'
		return isset($colors[$admin_color]) ? $colors[$admin_color] : $colors['fresh'];
	}
	
	$colors = get_admin_colors();
	print("
		<style>
			:root {
				--admin-colour-background: {$colors['background']};
				--admin-colour-primary: {$colors['primary']};
				--admin-colour-secondary: {$colors['secondary']};
				--admin-colour-tertiary: {$colors['tertiary']};
				--admin-colour-text: {$colors['text']};
			}
		</style>
	");
?>
<header class="header">
	<div class="header-content responsive-wrapper">
		<div class="header-logo">
			<div>
				<img alt="" src="/wp-content/plugins/MILG0IR-Store-Design-Features/assets/images/logo/transparent/x128.webp" width="32"/>
			</div>
		</div>
		<div class="header-navigation">
			<nav class="header-navigation-links">
				<a href="#summary"> Summary </a>
				<a href="#configuration"> Configuration </a>
				<a href="#price-management"> Price Management </a>
				<a href="#blocks"> Installed Blocks </a>
				<a href="#management"> Management </a>
			</nav>
		</div>
		<a href="#" class="btn">
			<i class="dashicons dashicons-menu"></i>
			<span>Menu</span>
		</a>
	</div>
</header>
<main class="main milg0ir">
	<div class="responsive-wrapper active">
		<div class="loader">
			<svg viewBox="0 0 80 80">
				<circle id="test" cx="40" cy="40" r="32"></circle>
			</svg>
		</div>
	</div>
	<div class="responsive-wrapper" id="summary">
		<div class="main-header">
			<span>
				<h1>MILG0IR Store Designs & Features</h1>
				<h2>Summary</h2>
			</span>
			<div class="search">
				<input type="text" placeholder="Search" />
				<button type="submit">
					<i class="dashicons dashicons-search"></i>
				</button>
			</div>
		</div>
		<div class="horizontal-tabs">
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
			<div class="section default" id="defaults">
				<div class="mg-settings-container">
					<style>
						/* Container Styling for Masonry Layout */
						.mg-settings-container {
							display: grid;
							grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
							gap: 20px;
							max-width: 1200px;
							margin: 0 auto;
							padding: 20px;
						}

						/* Individual Settings Card */
						.mg-settings-summary {
							background: #fff;
							border: 1px solid #ddd;
							border-radius: 8px;
							box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
							padding: 20px;
							transition: transform 0.2s;
						}

						.mg-settings-summary:hover {
							transform: translateY(-5px);
						}

						/* Title Styles */
						.mg-settings-summary h2 {
							font-size: 1.6em;
							color: var(--admin-colour-tertiary);
							margin-bottom: 15px;
							border-bottom: 2px solid var(--admin-colour-tertiary);
							padding-bottom: 5px;
						}

						.mg-settings-summary h4 {
							font-size: 1.2em;
							color: #333;
							margin: 15px 0 10px;
							border-left: 4px solid var(--admin-colour-tertiary);
							padding-left: 10px;
						}

						/* Table Styles */
						.mg-settings-summary table {
							width: 100%;
							border-collapse: collapse;
							margin-top: 10px;
						}

						.mg-settings-summary th,
						.mg-settings-summary td {
							padding: 10px 12px;
							text-align: left;
						}

						.mg-settings-summary thead th {
							background-color: var(--admin-colour-tertiary);
							color: #fff;
							font-weight: bold;
						}

						.mg-settings-summary tbody tr:nth-child(even) {
							background-color: #f9f9f9;
						}

						.mg-settings-summary tbody tr:nth-child(odd) {
							background-color: #ffffff;
						}

						/* Responsive Adjustments */
						@media (max-width: 768px) {
							.mg-settings-container {
								grid-template-columns: 1fr;
							}
						}
					</style>
					<?php
						print(display_settings_summary("mg_stamp_card_", "Stamp Card"));
						print(display_settings_summary("mg_wishlist_", "Wishlist"));
						print(display_settings_summary("mg_taxonomies_", "Taxonomies"));

						// Retrieve all stored settings for 'mg_pricing_settings'
						$pricing_settings = get_option('mg_dynamic_pricing_data');

						// Check if there are settings to display
						if ($pricing_settings && is_array($pricing_settings)) {
							echo '<div class="mg-settings-summary">';
							echo '<h2>Pricing Settings Summary</h2>';

							// Loop through each section and display its options
							foreach ($pricing_settings as $section) {
								if (!empty($section['title'])) {
									echo '<h4>' . esc_html($section['title']) . ': </h3>';
								}

								print('<table>');
								print('<thead><tr><th>Setting</th><th>Value</th></tr></thead>');
								print('<tbody>');
								// Display each option and its value within the section
								if (!empty($section['options']) && is_array($section['options'])) {
									foreach ($section['options'] as $option) {
										if (!empty($option['name']) && isset($option['value'])) {
											print('<tr>');
											print('<td><strong>' . esc_html($option['name']) . '</strong></td>');
											print('<td>' . esc_html($option['value']) . '</td>');
											print('</tr>');
										}
									}
								}
								print('</tbody>');
								print('</table>');
							}
							print('</div>');
							
						} else {
							echo '<p>No pricing settings have been configured yet.</p>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
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
	<div class="responsive-wrapper" id="blocks">
		<div class="main-header">
			<span>
				<h1>MILG0IR Store Designs & Features</h1>
				<h2>Provided Blocks</h2>
			</span>
			<div class="search">
				<input type="text" placeholder="Search" />
				<button type="submit">
					<i class="dashicons dashicons-search"></i>
				</button>
			</div>
		</div>
		<div class="horizontal-tabs">
			<a href="#blocks/installed">Installed</a>
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
			<div class="section default" id="installed">
				<div id="container">
				</div>
			</div>
		</div>
	</div>
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
				<div class="wrap">
					<form method="post" action="options.php">
					<h2>Custom Price Calculator Options</h2>
						<?php
							settings_fields('mg_dynamic_price_calculator_options');
							$pricing_data = get_option('mg_dynamic_pricing_data', []);
							?>
							<button type="button" class="mg-add-section button">Add Section</button>
				
							<div id="mg-accordion">
								<?php if (!empty($pricing_data)) { ?>
									<?php foreach ($pricing_data as $section_index => $section) { ?>
										<div class="mg-accordion-section" data-index="<?=$section_index?>">
											<strong><input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][title]" value="<?=esc_attr($section['title'])?>" placeholder="Title"><button type="button" class="button mg-remove-section">Remove Section</button></strong>
											<div class="mg-options-list">
												<div class="mg-option-header">
													<h4>Name</h4>
													<h4>Value</h4>
												</div>
												<?php foreach ($section['options'] as $option_index => $option) { ?>
													<div class="mg-option">
														<input type="text" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][name]" value="<?=esc_attr($option['name'])?>" placeholder="Option Name">
														<input type="number" step="0.00001" name="mg_dynamic_pricing_data[<?=$section_index?>][options][<?=$option_index?>][value]" value="<?=esc_attr($option['value'])?>" placeholder="Option Value">
														<button type="button" class="button mg-remove-option">Remove</button>
													</div>
												<?php }?>
											</div>
											<button type="button" class="button mg-add-option" data-section-index="<?=$section_index?>">Add Option</button>
										</div>
							<?php }
								} ?>
						</div>

						<?php submit_button()?>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="responsive-wrapper" id="management">
		<div class="main-header">
			<span>
				<h1>MILG0IR Store Designs & Features</h1>
				<h2>Management</h2>
			</span>
			<div class="search">
				<input type="text" placeholder="Search" />
				<button type="submit">
					<i class="dashicons dashicons-search"></i>
				</button>
			</div>
		</div>
		<div class="horizontal-tabs">
			<a href="#management/suppliers">Suppliers</a>
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
			<div class="section default" id="suppliers">
				<div id="container">
				</div>
			</div>
		</div>
	</div>

	<div class="responsive-wrapper">
		<div class="main-header">
			<h1>Configuration</h1>
			<div class="search">
				<input type="text" placeholder="Search" />
				<button type="submit">
					<i class="dashicons dashicons-search"></i>
				</button>
			</div>
		</div>
		<div class="horizontal-tabs">
			<a href="#">My details</a>
			<a href="#">Profile</a>
			<a href="#">Password</a>
			<a href="#">Team</a>
			<a href="#">Plan</a>
			<a href="#">Billing</a>
			<a href="#">Email</a>
			<a href="#">Notifications</a>
			<a href="#" class="active">Integrations</a>
			<a href="#">API</a>
		</div>
		<div class="content-header">
			<div class="content-header-intro">
				<h2>Intergrations and connected apps</h2>
				<p>Supercharge your workflow and connect the tool you use every day.</p>
			</div>
			<div class="content-header-actions">
				<a href="#" class="button">
					<i class="dashicons dashicons-admin-settings"></i>
					<span>Filters</span>
				</a>
				<a href="#" class="button">
					<i class="dashicons dashicons-plus-alt2"></i>
					<span>Request integration</span>
				</a>
			</div>
		</div>
		<div class="content">
			<div class="content-panel">
				<div class="vertical-tabs">
					<a href="#" class="active">View all</a>
					<a href="#">Developer tools</a>
					<a href="#">Communication</a>
					<a href="#">Productivity</a>
					<a href="#">Browser tools</a>
					<a href="#">Marketplace</a>
				</div>
			</div>
			<div class="content-main">
				<div class="card-grid">
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/zeplin.svg" /></span>
								<h3>Zeplin</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Collaboration between designers and developers.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/github.svg" /></span>
								<h3>GitHub</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Link pull requests and automate workflows.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/figma.svg" /></span>
								<h3>Figma</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Embed file previews in projects.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/zapier.svg" /></span>
								<h3>Zapier</h3>
							</div>
							<label class="toggle">
								<input type="checkbox">
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Build custom automations and integrations with apps.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/notion.svg" /></span>
								<h3>Notion</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Embed notion pages and notes in projects.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/slack.svg" /></span>
								<h3>Slack</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Send notifications to channels and create projects.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/zendesk.svg" /></span>
								<h3>Zendesk</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Link and automate Zendesk tickets.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/jira.svg" /></span>
								<h3>Atlassian JIRA</h3>
							</div>
							<label class="toggle">
								<input type="checkbox">
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Plan, track, and release great software.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/dropbox.svg" /></span>
								<h3>Dropbox</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Everything you need for work, all in one place.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/google-chrome.svg" /></span>
								<h3>Google Chrome</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Link your Google account to share bookmarks across your entire team.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/discord.svg" /></span>
								<h3>Discord</h3>
							</div>
							<label class="toggle">
								<input type="checkbox" checked>
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Keep in touch with your customers without leaving the app.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
					<article class="card">
						<div class="card-header">
							<div>
								<span><img alt="" src="https://assets.codepen.io/285131/google-drive.svg" /></span>
								<h3>Google Drive</h3>
							</div>
							<label class="toggle">
								<input type="checkbox">
								<span></span>
							</label>
						</div>
						<div class="card-body">
							<p>Link your Google account to share files across your entire team.</p>
						</div>
						<div class="card-footer">
							<a href="#">View integration</a>
						</div>
					</article>
				</div>
			</div>
		</div>
	</div>
</main>