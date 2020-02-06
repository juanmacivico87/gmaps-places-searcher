<?php
/**
 * A snippet to create a new Gutenberg block. For more info, view:
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package jmc87_plugin
 */

class JMC87_GoogleMapsBlock
{
    public function __construct()
    {
        add_action( 'acf/init', array( $this, 'jmc87_add_google_maps_block' ) );
        add_filter( 'script_loader_tag', array( $this, 'jmc87_load_async_scripts' ), 10, 2 );
    }

    public function jmc87_load_async_scripts( $tag, $handle )
    {
        if ( $handle === 'google-maps' )
        {
            $tag = str_replace( ' src', ' async="async" src', $tag );
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }
        return $tag;
    }

    public function jmc87_get_places()
    {
        $places = array();
        $args = array(
            'post_type'      => 'place',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            'meta_key'       => '_jmc87_is_place_allow_new_subscriber',
            'meta_value' => 1,
        );
        $wp_query = new WP_Query( $args );

        if ( !empty( $wp_query->posts ) )
        {
            foreach( $wp_query->posts as $place )
            {
                $latitude  = ( $value = get_field( '_jmc87_place_latitude', $place->ID ) ) ? $value : 0;
                $longitude = ( $value = get_field( '_jmc87_place_longitude', $place->ID ) ) ? $value : 0;

                $places[] = array(
                    'ID'          => $place->ID,
                    'name'        => $place->post_title,
                    'description' => $place->post_excerpt,
                    'location'    => array( $latitude, $longitude ),
                    'url'         => get_permalink( $place->ID )
                );
            }
        }
        wp_reset_query();
        return $places;
    }
    
    public function jmc87_add_google_maps_block()
    {
        if ( function_exists( 'acf_register_block_type' ) ) {
            acf_register_block_type(
                array(
                    'name'				=> 'google-maps',
                    'title'				=> __( 'Google Maps Block', 'jmc87_gmaps_places_searcher' ),
                    'description'		=> '',
                    'category'			=> 'formatting',
                    'icon'				=> 'admin-site-alt',
                    'keywords'			=> array( 'maps', 'google maps' ),
                    'post_types'        => array( 'page' ),
                    'mode'              => 'edit',
                    'render_template'   => GMAPS_PLACES_SEARCHER_PLUGIN_DIR . 'src/customBlocks/googleMaps/views/template-google-maps.php',
                    'enqueue_assets'    => function() {
                        if ( !is_admin() )
                        {
                            wp_enqueue_style( 'google-maps', GMAPS_PLACES_SEARCHER_PLUGIN_URL . 'src/customBlocks/googleMaps/css/styles.css' );

                            if ( !wp_script_is( 'jquery' ) )
                                wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.4.1.min.js', array(), '', true );
                                
                            wp_enqueue_script( 'google-maps', GMAPS_PLACES_SEARCHER_PLUGIN_URL . 'src/customBlocks/googleMaps/js/scripts.js', array( 'jquery' ), '', true );

                            $args = array(
                                'ajax_url'        => admin_url( 'admin-ajax.php' ),
                                'plugin_url'      => GMAPS_PLACES_SEARCHER_PLUGIN_URL,
                                'centers'         => json_encode( $this->jmc87_get_places(), JSON_PRETTY_PRINT ),
                                'api_key'         => get_option( '_jmc87_google_maps_api_key' ),
                                'all_places_link' => get_post_type_archive_link( 'place' ),
                            );
                            wp_localize_script( 'google-maps', 'js_vars', $args );
                        }
                    },
                    'supports' => array( 'mode' => false ),
                )
            );
        }
    }
}
