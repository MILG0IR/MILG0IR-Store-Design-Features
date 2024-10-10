<header class="header">
	<div class="header-content responsive-wrapper">
		<div class="header-logo">
			<div>
				<img alt="" src="https://modilio.co.uk/wp-content/plugins/MILG0IR-Store-Design-Features/assets/images/logo_transparent.svg" width="32"/>
			</div>
		</div>
		<div class="header-navigation">
			<nav class="header-navigation-links">
				<a href="#summary"> Summary </a>
				<a href="#configuration"> Configuration </a>
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
			<div class="section" id="stampcard">
				<form method="post" action="options.php">
					<?php
						settings_fields('stampcard_settings_group');	// Register settings group
						do_settings_sections('stampcard-settings');	// Display settings sections
						submit_button();								// Display the save button
					?>
				</form>
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
