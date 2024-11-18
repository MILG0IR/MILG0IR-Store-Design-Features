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

		//$res .= '<a class="button">Edit</a>';
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
				<a href="#management"> Management </a>
			</nav>
		</div>
		<button href="#" class="header-nav-btn">
			<i class="dashicons dashicons-menu"></i>
			<span>Menu</span>
		</button>
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
	<?php
		foreach (glob(plugin_dir_path(__FILE__) . "pages/*.php") as $filename) {
			include $filename;
		}
	?>
</main>