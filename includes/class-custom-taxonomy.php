<?php

namespace MILG0IR_Store;

class Custom_Taxonomy {
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
        add_action('init', [self::class, 'register_collection_taxonomy'], 0);
    }

    /**
     * Register the custom taxonomy for collections.
     *
     * This method is hooked to the 'init' action and will register the taxonomy
     * for the specified post types.
     */
    public static function register_collection_taxonomy() {
        register_taxonomy('collection', array('product', 'post'), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'                       => 'Collections',
                'singular_name'              => 'Collection',
                'search_items'               => 'Search Collections',
                'all_items'                  => 'All Collections',
                'parent_item'                => 'Parent Collection',
                'parent_item_colon'          => 'Parent Collection:',
                'edit_item'                  => 'Edit Collection',
                'update_item'                => 'Update Collection',
                'add_new_item'               => 'Add New Collection',
                'new_item_name'              => 'New Collection Name',
                'menu_name'                  => 'Collections',
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'collection'),
        ));
    }
}