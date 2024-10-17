<?php

namespace MILG0IR_Store;

class Blocks {
    /**
     * Constructor to initialize the custom taxonomy registration process.
     */
    public static function init() {
        self::register_hooks();
    }

    /**
     * Register the necessary hooks for the custom taxonomy.
     */
    private static function register_hooks() {
        // Register the block and its script when WordPress initializes.
        add_action('init', [self::class, 'register_block_script']);

        // Add a custom block category for the MILG0IR blocks.
        add_filter('block_categories_all', [self::class, 'add_custom_block_category'], 10, 2);
    }

    /**
     * Registers the custom block script with WordPress.
     *
     * @since 0.0.5
     */
    public static function register_block_script() {
        wp_register_script(
            'stamp-card-preview-block',
            plugin_dir_url(__FILE__) . 'blocks/stamp-card.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'),
            filemtime(plugin_dir_path(__FILE__) . 'blocks/stamp-card.js'),
            true
        );

        register_block_type('milg0ir/stamp-card-preview-block', array(
            'editor_script' => 'stamp-card-preview-block',
        ));
    }

    /**
     * Adds a custom block category for MILG0IR blocks.
     *
     * @param array $categories Existing block categories.
     * @return array Updated block categories including the MILG0IR custom category.
     *
     * @since 0.0.5
     */
    private static function add_custom_block_category($categories) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug'  => 'milg0ir-blocks',
                    'title' => 'MILG0IR Blocks',
                    'icon'  => plugin_dir_url(__FILE__) . '../assets/images/logo/transparent/x24.webp',
                ),
            )
        );
    }
}