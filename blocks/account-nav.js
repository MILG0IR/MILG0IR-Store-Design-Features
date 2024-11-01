// Register the custom block
wp.blocks.registerBlockType('milg0ir/account-nav', {
	title: 'Account Navigation',
	description: 'Displays an adaptive easy-to-use navigation for the account page',
	icon: 'admin-links',
	category: 'milg0ir-blocks',
	attributes: {},

	edit: (props) => {
		const accountLinks = [
			{ title: 'Home', url: '/my-account/', name: 'home', icon: 'home'},
		];
		// Conditionally add Stamp Card and Wishlist links based on the settings
		if (mg_localization.wishlistEnabled) {
			accountLinks.push({ title: 'Wishlist', url: '/my-account/wishlist/', name: 'wishlist', icon: 'heart' });
		}
		if (mg_localization.stampCardEnabled) {
			accountLinks.push({ title: 'Stamp Card', url: '/my-account/stampcard/', name: 'stamp-card', icon: 'frame' });
		}
		accountLinks.push({ title: 'Orders', url: '/my-account/orders/', name: 'orders', icon: 'docs' });
		accountLinks.push({ title: 'Addresses', url: '/my-account/edit-address/', name: 'addresses', icon: 'location-pin' });
		accountLinks.push({ title: 'Cards', url: '/my-account/payment-methods/', name: 'cards', icon: 'credit-card' });
		accountLinks.push({ title: 'Account Details', url: '/my-account/edit-account/', name: 'details', icon: 'user' });
		accountLinks.push({ title: 'Log Out', url: '/my-account/logout/', name: 'logout', icon: 'logout' });

		// Create the navigation bar using React.createElement and WordPress theme classes
		return React.createElement(
			'div',
			{
				className: 'alignfull mg-account-nav',
			},
			React.createElement(
				'div',
				{
					className: 'alignfull',
					style: {
						textAlign: 'center',
					}
				},
				accountLinks.map((link, index) =>
					React.createElement(
						'a',
						{
							key: `${index}-${link.title}`,
							href: link.url,
							className: 'wp-block-navigation-link wp-block-navigation-item',
							style: {
								backgroundColor: 'var(--wp--preset--color--primary)',
								fontSize: 'var(--wp--preset--font-size--base)',
								color: 'var(--wp--preset--color--white)',
								textDecoration: 'none',
								padding: '0.5rem 1rem',
								margin: '4px',
								display: 'inline-block',
								borderRadius: '4px',
								transition: '0.4s ease-in-out',
							},
						},
						React.createElement(
							'span',
							{
								className: 'icon icon-'+link.icon,
								style: {
									paddingRight: '.5rem',
								}
							}
						),
						React.createElement(
							'span',
							{
								className: 'title',
							},
							link.title
						)
					)
				)
			)
		);
	},

	save: () => {
		const accountLinks = [
			{ title: 'Home', url: '/my-account/', name: 'home', icon: 'home'},
			{ title: 'Wishlist', url: '/my-account/wishlist/', name: 'wishlist', icon: 'heart' },
			{ title: 'Stamp Card', url: '/my-account/stampcard/', name: 'stamp-card', icon: 'frame' },
			{ title: 'Orders', url: '/my-account/orders/', name: 'orders', icon: 'docs' },
			{ title: 'Addresses', url: '/my-account/edit-address/', name: 'addresses', icon: 'location-pin' },
			{ title: 'Cards', url: '/my-account/payment-methods/', name: 'cards', icon: 'credit-card' },
			{ title: 'Account Details', url: '/my-account/edit-account/', name: 'details', icon: 'user' },
			{ title: 'Log Out', url: '/my-account/logout/', name: 'logout', icon: 'logout' },
		];

		// Create the navigation bar using React.createElement and WordPress theme classes
		return React.createElement(
			'div',
			{
				className: 'alignfull mg-account-nav',
				style: {
					display: 'none',
				},
			},
			React.createElement(
				'div',
				{
					className: ' alignfull',
					style: {
						textAlign: 'center',
					}
				},
				accountLinks.map((link, index) =>
					React.createElement(
						'a',
						{
							key: `${index}-${link.title}`,
							href: link.url,
							name: link.name,
							className: 'mg-navigation-link',
							style: {
								backgroundColor: 'var(--wp--preset--color--primary)',
								fontSize: 'var(--wp--preset--font-size--base)',
								color: 'var(--wp--preset--color--white)',
								textDecoration: 'none',
								padding: '0.5rem 1rem',
								margin: '4px',
								display: 'inline-block',
								borderRadius: '4px',
								transition: '0.4s ease-in-out',
							},
						},
						React.createElement(
							'span',
							{
								className: 'icon icon-'+link.icon,
								style: {
								}
							}
						),
						React.createElement(
							'span',
							{
								className: 'title',
							},
							link.title
						)
					)
				)
			)
		);
	},
});
