<?php
if ( !defined( 'ABSPATH' ) )
    exit;

class JMC87_GMapsPlacesSearcherConfig
{
    public function __construct()
    {
        add_action( 'plugins_loaded', array( $this, 'jmc87_load_gmaps_places_searcher_textdomain' ) );
    }

    public function jmc87_load_gmaps_places_searcher_textdomain()
    {
        load_plugin_textdomain( 'jmc87_gmaps_places_searcher', false, GMAPS_PLACES_SEARCHER_LANG_DIR );
    }
}
