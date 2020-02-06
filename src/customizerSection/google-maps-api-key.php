<?php
/**
 * A snippet to add a new panel in WordPress Customizer. For more info, view:
 *
 * @link https://codex.wordpress.org/Theme_Customization_API
 *
 * @package jmc87_plugin
 */

class JMC87_GMapsPlacesSearcherAPIKey
{
    public $section    = 'gmaps_api_key';

    public function __construct()
    {
        add_action( 'customize_register', array( $this, 'jmc87_add_new_customizer_panel' ) );
    }

    public function jmc87_add_new_customizer_panel()
    {
        global $wp_customize;

        $wp_customize -> add_section( 
            $this->section,
            array(
                'title'         => __( 'Google Maps API', 'jmc87_gmaps_places_searcher' ),
                'priority'      => 1,
                'description'   => '',
                'capability'    => 'edit_pages',
            )
        );

        $wp_customize -> add_setting(
            '_jmc87_google_maps_api_key',
            array(
                'default'       => '',
                'type'          => 'option',
                'capability'    => 'edit_pages',
                'transport'     => 'refresh',
            )
        );

        $wp_customize -> add_control(
            '_jmc87_google_maps_api_key',
            array(
                'label'       => __( 'API key', 'jmc87_gmaps_places_searcher' ),
                'description' => '',
                'section'     => $this->section,
                'priority'    => 1,
                'type'        => 'text',
            )
        );
    }
}
