<?php
/**
 * A snippet to create a new custom post type in WordPress. For more info, view:
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 *
 * @package jmc87_plugin
 */

class JMC87_PlacesPostType
{
    public $post_type  = 'place';
    public $taxononies = array();
    public $support    = array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' );
    public $rewrite    = array( 
        'slug'       => 'places',
        'with_front' => false,
        'feeds'      => false,
        'pages'      => true,
    );

    public function __construct()
    {
        add_action( 'init', array( $this, 'jmc87_add_places_post_type' ) );
        add_action( 'init', array( $this, 'jmc87_add_places_custom_fields' ) );
    }

    public function jmc87_add_places_post_type()
    {
        $args = array(
            'labels' => array(
                'name'                     => __( 'Places', 'jmc87_gmaps_places_searcher' ),
                'singular_name'            => __( 'Place', 'jmc87_gmaps_places_searcher' ),
                'add_new'                  => __( 'Add New', 'jmc87_gmaps_places_searcher' ),
                'add_new_item'             => __( 'Add New Place', 'jmc87_gmaps_places_searcher' ),
                'edit_item'                => __( 'Edit Place', 'jmc87_gmaps_places_searcher' ),
                'new_item'                 => __( 'New Place', 'jmc87_gmaps_places_searcher' ),
                'view_item'                => __( 'View Place', 'jmc87_gmaps_places_searcher' ),
                'view_items'               => __( 'View Places', 'jmc87_gmaps_places_searcher' ),
                'search_items'             => __( 'Search Places', 'jmc87_gmaps_places_searcher' ),
                'not_found'                => __( 'Place not Found', 'jmc87_gmaps_places_searcher' ),
                'not_found_in_trash'       => __( 'Place not Found in Trash', 'jmc87_gmaps_places_searcher' ),
                'parent_item_colon'        => __( 'Parent Place:', 'jmc87_gmaps_places_searcher' ),
                'all_items'                => __( 'All Places', 'jmc87_gmaps_places_searcher' ),
                'archives'                 => __( 'Place Archives', 'jmc87_gmaps_places_searcher' ),
                'attributes'               => __( 'Place Attributes', 'jmc87_gmaps_places_searcher' ),
                'insert_into_item'         => __( 'Insert into Place', 'jmc87_gmaps_places_searcher' ),
                'uploaded_to_this_item'    => __( 'Uploaded to this Place', 'jmc87_gmaps_places_searcher' ),
                'featured_image'           => __( 'Featured Image', 'jmc87_gmaps_places_searcher' ),
                'set_featured_image'       => __( 'Set Featured Image', 'jmc87_gmaps_places_searcher' ),
                'remove_featured_image'    => __( 'Remove Featured Image', 'jmc87_gmaps_places_searcher' ),
                'use_featured_image'       => __( 'Use Featured Image', 'jmc87_gmaps_places_searcher' ),
                'menu_name'                => __( 'Places', 'jmc87_gmaps_places_searcher' ),
                'filter_items_list'        => __( 'Filter Places List', 'jmc87_gmaps_places_searcher' ),
                'items_list_navigation'    => __( 'Places List Navigation', 'jmc87_gmaps_places_searcher' ),
                'items_list'               => __( 'Places List', 'jmc87_gmaps_places_searcher' ),
                'name_admin_bar'           => __( 'Places', 'jmc87_gmaps_places_searcher' ),
                'item_published'           => __( 'Place Published', 'jmc87_gmaps_places_searcher' ),
                'item_published_privately' => __( 'Place Published Privately', 'jmc87_gmaps_places_searcher' ),
                'item_reverted_to_draft'   => __( 'Place Reverte to Draft', 'jmc87_gmaps_places_searcher' ),
                'item_scheduled'           => __( 'Place Scheduled', 'jmc87_gmaps_places_searcher' ),
                'item_updated'             => __( 'Place Updated', 'jmc87_gmaps_places_searcher' ),
            ),
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_nav_menus'   => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-admin-multisite',
            'hierarchical'        => true,
            'supports'            => $this->support,
            'taxonomies'          => array(),
            'has_archive'         => true,
            'rewrite'             => $this->rewrite,
            'query_var'           => true,
            'can_export'          => true,
            'delete_with_user'    => false,
            'show_in_rest'        => true,
        );

        register_post_type( $this->post_type, $args );
        flush_rewrite_rules();
    }

    public function jmc87_add_places_custom_fields()
    {
        if ( function_exists( 'acf_add_local_field_group' ) ) :
            acf_add_local_field_group(array(
                'key' => 'group_5e3ab378a7066',
                'title' => __( 'Place Settings', 'jmc87_gmaps_places_searcher' ),
                'fields' => array(
                    array(
                        'key' => 'field_5e3ab3849ae3c',
                        'label' => __( 'Latitude', 'jmc87_gmaps_places_searcher' ),
                        'name' => '_jmc87_place_latitude',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => -90,
                        'max' => 90,
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5e3ab4249ae3d',
                        'label' => __( 'Longitude', 'jmc87_gmaps_places_searcher' ),
                        'name' => '_jmc87_place_longitude',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => -180,
                        'max' => 180,
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5e3ab48d9ae3f',
                        'label' => __( 'Show in map?', 'jmc87_gmaps_places_searcher' ),
                        'name' => '_jmc87_is_place_show_in_map',
                        'type' => 'button_group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            1 => __( 'Yes', 'jmc87_gmaps_places_searcher' ),
                            0 => __( 'No', 'jmc87_gmaps_places_searcher' ),
                        ),
                        'allow_null' => 0,
                        'default_value' => '',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'place',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'side',
                'style' => 'seamless',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ) );
        endif;
    }
}
