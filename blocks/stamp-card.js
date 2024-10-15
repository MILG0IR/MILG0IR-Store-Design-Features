/*
 * A simple banner block for checkouts to show the value of the stamp or how many stamps the customer may get.
 * 
 * @since 0.0.5
 */

wp.blocks.registerBlockType('milg0ir/stamp-card-preview-block', {
	title: 'Stamp Card Preview',
	description: 'Displays the value of a stamp or how many stamps a customer may get. Automatically hides if the user has disabled stamp cards.',
	icon: 'visibility',
	category: 'milg0ir-blocks',
	attributes: {
		backgroundColor: { type: 'string', default: '#00aaff22' },
		borderColor: { type: 'string', default: '#00aaff' },
		borderWidth: { type: 'number', default: 1 },
		borderRadius: { type: 'number', default: 10 },
		borderStyle: { type: 'string', default: 'solid' },
		paddingT: { type: 'string', default: '10px' },
		paddingR: { type: 'string', default: '10px' },
		paddingB: { type: 'string', default: '10px' },
		paddingL: { type: 'string', default: '10px' },
		iconSize: { type: 'string', default: '32px' },
		iconSpacing: { type: 'string', default: '10px' },
	},
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const { backgroundColor, borderColor, textColor, fontSize, borderWidth, borderRadius, borderStyle, paddingT, paddingR, paddingB, paddingL, iconSpacing, iconSize } = attributes;

		return React.createElement(
			React.Fragment,
			null,
			React.createElement(
				wp.blockEditor.InspectorControls,
				null,
				React.createElement(
					wp.components.PanelBody,
					{
						title: 'Colours'
					},
					React.createElement(wp.components.ColorPalette, {
						label: 'Background Color',
						value: backgroundColor,
						onChange: (color) => setAttributes({ backgroundColor: color }),
					}),
					React.createElement(wp.components.ColorPalette, {
						label: 'Border Color',
						value: borderColor,
						onChange: (color) => setAttributes({ borderColor: color }),
					}),
				),
				React.createElement(
					wp.components.PanelBody,
					{
						title: 'Border'
					},
					React.createElement(wp.components.TextControl, {
						label: 'Border Width',
						value: borderWidth,
						onChange:  (value) => setAttributes({ borderWidth: value }),
					}),
					React.createElement(wp.components.FontSizePicker, {
						label: 'Border Radius',
						fallbackFontSize: '10px',
						value: borderRadius,
						onChange:  (value) => setAttributes({ borderRadius: value }),
						disableCustomFontSizes: false,
					}),
					React.createElement(wp.components.SelectControl, {
						label: 'Border Style',
						value: borderStyle,
						options: [
							{ label: 'Solid', value: 'solid' },
							{ label: 'Dashed', value: 'dashed' },
							{ label: 'Dotted', value: 'dotted' },
						],
						onChange:  (value) => setAttributes({ borderStyle: value }),
					}),
				),
				React.createElement(
					wp.components.PanelBody,
					{
						title: 'Padding'
					},
					React.createElement(wp.components.FontSizePicker, {
						fallbackFontSize: '10px',
						value: paddingT,
						onChange:  (value) => setAttributes({ paddingT: value }),
						disableCustomFontSizes: false,
					}),
					React.createElement(wp.components.FontSizePicker, {
						label: 'Padding Right',
						fallbackFontSize: '10px',
						value: paddingR,
						onChange:  (value) => setAttributes({ paddingR: value }),
						disableCustomFontSizes: false,
					}),
					React.createElement(wp.components.FontSizePicker, {
						label: 'Padding Bottom',
						fallbackFontSize: '10px',
						value: paddingB,
						onChange:  (value) => setAttributes({ paddingB: value }),
						disableCustomFontSizes: false,
					}),
					React.createElement(wp.components.FontSizePicker, {
						label: 'Padding Left',
						fallbackFontSize: '10px',
						value: paddingL,
						onChange:  (value) => setAttributes({ paddingL: value }),
						disableCustomFontSizes: false,
					}),
				),
				React.createElement(
					wp.components.PanelBody,
					{
						title: 'Icon'
					},
					React.createElement(wp.components.FontSizePicker, {
						label: 'Icon Size',
						fallbackFontSize: '32px',
						value: iconSize,
						onChange:  (value) => setAttributes({ iconSize: value }),
						disableCustomFontSizes: false,
					}),
					React.createElement(wp.components.FontSizePicker, {
						label: 'Icon Gap',
						fallbackFontSize: '10px',
						value: iconSpacing,
						onChange:  (value) => setAttributes({ iconSpacing: value }),
						disableCustomFontSizes: false,
					}),
				)
			),
			React.createElement(
				'div',
				{
					style: {
						display: 'flex',
						backgroundColor: backgroundColor,
						borderColor: borderColor,
						borderWidth: borderWidth + 'px',
						borderRadius: borderRadius,
						borderStyle: borderStyle,
						padding: `${paddingT} ${paddingR} ${paddingB} ${paddingL}`,
					},
				},
				React.createElement(
					'img',
					{
						src: 'https://modilio.co.uk/wp-content/plugins/MILG0IR-Store-Design-Features/assets/images/stampcard.svg',
						alt: 'Stamp Card',
						width: iconSize,
						height: iconSize,
						style: {
							paddingRight: iconSpacing,
						},
					},
				),
				React.createElement(
					'p',
					{
						style: {
							margin: 'unset',
						},
					},
		            'You could earn stamps with this order!'
				)
			)
		);
	},

	save: (props) => {
		const { attributes } = props;
		const { backgroundColor, borderColor, textColor, fontSize, borderWidth, borderRadius, borderStyle, paddingT, paddingR, paddingB, paddingL, iconSpacing, iconSize } = attributes;

		return React.createElement(
			'div',
			{
				style: {
					display: 'flex',
					backgroundColor: backgroundColor,
					borderColor: borderColor,
					borderWidth: borderWidth + 'px',
					borderRadius: borderRadius,
					borderStyle: borderStyle,
					padding: `${paddingT} ${paddingR} ${paddingB} ${paddingL}`,
				},
			},
			React.createElement(
				'img',
				{
					src: 'https://modilio.co.uk/wp-content/plugins/MILG0IR-Store-Design-Features/assets/images/stampcard.svg',
					alt: 'Stamp Card',
					width: iconSize,
					height: iconSize,
					style: {
						paddingRight: iconSpacing,
					},
				},
			),
			React.createElement(
				'p',
				{
					style: {
						margin: 'unset',
					},
				},
	            'You could earn stamps with this order!'
			)
		);
	},
});
