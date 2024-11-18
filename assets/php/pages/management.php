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
			<form method="post" action="options.php">
				<?php 
					settings_fields('mg_suppliers_options');			// Register settings group
				?>
				<textarea class="currentSupplierData" hidden>
					<?=json_encode(get_option('mg_suppliers_data', []))?>
				</textarea>
				<h2>Suppliers</h2>

				<div class="mg-masonry-container supplier-container"></div>

				<button type="button" class="mg-add-supplier-section button">Add Section</button>

				<?php submit_button()?>
			</form>
		</div>
	</div>
</div>