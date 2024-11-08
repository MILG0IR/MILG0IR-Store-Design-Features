
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
		<style>
			#mg-suppliers-list {
				margin-top: .8rem;
				display: flex;
				flex-wrap: wrap;
				gap: .8rem;
			}
			#mg-suppliers-list .mg-supplier-section {
				flex: 1 1 45%; /* Allows two sections per row with some spacing */
				max-width: 50%;
				box-sizing: border-box; /* Ensures padding doesn't increase width */
				padding: .1rem;
				background: #f7f7f7;
				border: 0.1rem solid #ccc;
				border-radius: 1rem;
			}
			#mg-suppliers-list .mg-supplier-section h3 {
				font-size: 1.2rem;
				margin-bottom: 0.5rem;
			}
			#mg-suppliers-list .mg-supplier-section th {
				max-width: 150px;
			}
			#mg-suppliers-list .mg-supplier-section textarea,
			#mg-suppliers-list .mg-supplier-section table input {
				width: 100%;
			}
			#mg-suppliers-list .mg-supplier-section {
				padding: 1.5rem;
				border: 0.1rem solid #ccc;
				background: #f9f9f9;
			}
			.mg-supplier-section .mg-remove-supplier {
				margin-left: .8rem;
			}
		</style>
		<div class="section default" id="suppliers">
			<div id="container">
				<form method="post" action="options.php">
					<h2>Custom Price Calculator Options</h2>
					<script type="text/javascript">
						document.addEventListener('DOMContentLoaded', function() {
							const suppliersList = document.getElementById('mg-suppliers-list');
							let supplierIndex = suppliersList.children.length;

							document.querySelector('.mg-add-supplier').addEventListener('click', function() {
								const newSupplier = document.createElement('div');
								newSupplier.classList.add('mg-supplier-section');
								newSupplier.dataset.index = supplierIndex;
								newSupplier.innerHTML = `
								<strong>
									<input type="text" name="mg_suppliers_data[${supplierIndex}][name]" placeholder="Supplier Name">
									<button type="button" class="button mg-remove-supplier">Remove Supplier</button>
								</strong>
								<div class="mg-supplier-fields">
									<table class="form-table"><tbody>
										<tr>
											<th>Phone Number:</th>
											<td>
												<input type="tel" name="mg_suppliers_data[${supplierIndex}][phone]" placeholder="Phone Number">
												<p class="description">Contact phone number</p>
											</td>
										</tr>
										<tr>
											<th>Email:</th>
											<td>
												<input type="email" name="mg_suppliers_data[${supplierIndex}][email]" placeholder="Email Address">
												<p class="description">Contact email address</p>
											</td>
										</tr>
										<tr>
											<th>Website:</th>
											<td>
												<input type="url" name="mg_suppliers_data[${supplierIndex}][url]" placeholder="Website address">
												<p class="description">Store website address</p>
											</td>
										</tr>
										<tr>
											<th>Description:</th>
											<td>
												<textarea name="mg_suppliers_data[${supplierIndex}][description]" placeholder="Brief Description"></textarea>
												<p class="description"> A breif description of the items/services they provide </p>
											</td>
										</tr>
									</tbody></table>
								</div>`;
								suppliersList.appendChild(newSupplier);
								supplierIndex++;

								// Add remove functionality to the new remove button
								newSupplier.querySelector('.mg-remove-supplier').addEventListener('click', function() {
									this.closest('.mg-supplier-section').remove();
								});
							});

							// Enable remove button functionality for existing suppliers
							document.querySelectorAll('.mg-remove-supplier').forEach(button => {
								button.addEventListener('click', function() {
									this.closest('.mg-supplier-section').remove();
								});
							});
						});
					</script>
					<?php
						settings_fields('mg_suppliers_options');
						$suppliers_data = get_option('mg_suppliers_data', []);
					?>
					<button type="button" class="button mg-add-supplier">Add Supplier</button>

					<div id="mg-suppliers-list">
						<?php
							if (!empty($suppliers_data)) {
								foreach ($suppliers_data as $index => $supplier) {
						?>
						<div class="mg-supplier-section" data-index="<?= $index ?>">
							<strong>
								<input type="text" name="mg_suppliers_data[<?= $index ?>][name]" value="<?= esc_attr($supplier['name']) ?>" placeholder="Supplier Name">
								<button type="button" class="button mg-remove-supplier">Remove Supplier</button>
							</strong>
							<div class="mg-supplier-fields">
								<table class="form-table"><tbody>
									<tr>
										<th>Phone Number:</th>
										<td>
											<input type="tel" name="mg_suppliers_data[<?= $index ?>][phone]" value="<?= esc_attr($supplier['phone']) ?>" placeholder="Phone Number">
											<p class="description">Contact phone number</p>
										</td>
									</tr>
									<tr>
										<th>Email:</th>
										<td>
											<input type="email" name="mg_suppliers_data[<?= $index ?>][email]" value="<?= esc_attr($supplier['email']) ?>" placeholder="Email Address">
											<p class="description">Contact email address</p>
										</td>
									</tr>
										<tr>
											<th>Website:</th>
											<td>
												<input type="url" name="mg_suppliers_data[<?= $index ?>][url]" value="<?= esc_attr($supplier['email']) ?>" placeholder="Website address">
												<p class="description">Store website address</p>
											</td>
										</tr>
									<tr>
										<th>Description:</th>
										<td>
											<textarea name="mg_suppliers_data[<?= $index ?>][description]" placeholder="Brief Description"><?= esc_textarea($supplier['description']) ?></textarea>
											<p class="description"> A breif description of the items/services they provide </p>
										</td>
									</tr>
								</tbody></table>
							</div>
						</div>
						<?php
								}
							}
						?>
					</div>

					<?php submit_button(); ?>
				</form>
			</div>
		</div>
	</div>
</div>