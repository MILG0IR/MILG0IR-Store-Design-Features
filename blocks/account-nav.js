const { registerBlockType } = wp.blocks;
const { useSelect } = wp.data;
const { InnerBlocks } = wp.blockEditor;

registerBlockType('milg0ir/my-account-nav', {
    title: 'Account Navigation Header',
    icon: 'admin-links',
    category: 'widgets',
    edit: () => {
        const accountLinks = [
            { title: 'Dashboard', url: '/my-account/' },
            { title: 'Orders', url: '/my-account/orders/' },
            { title: 'Downloads', url: '/my-account/downloads/' },
            { title: 'Addresses', url: '/my-account/edit-address/' },
            { title: 'Account Details', url: '/my-account/edit-account/' },
            { title: 'Logout', url: '/my-account/logout/' }
        ];

        // Fetch the values for stamp card and wishlist using WordPress data store
        const isStampCardEnabled = useSelect((select) =>
            select('core').getEntityRecord('root', 'option', 'milg0ir_stampcard_enabled')
        );

        const isWishlistEnabled = useSelect((select) =>
            select('core').getEntityRecord('root', 'option', 'milg0ir_wishlist_enabled')
        );

        if (isStampCardEnabled) {
            accountLinks.push({ title: 'Stamp Card', url: '/my-account/stamp-card/' });
        }

        if (isWishlistEnabled) {
            accountLinks.push({ title: 'Wishlist', url: '/my-account/wishlist/' });
        }

        return (
            <nav className="milg0ir-account-nav">
                <ul>
                    {accountLinks.map((link, index) => (
                        <li key={index}>
                            <a href={link.url}>{link.title}</a>
                        </li>
                    ))}
                </ul>
                <InnerBlocks />

            </nav>
        );
    },
    save: () => {
        return <InnerBlocks.Content />;
    }
});
