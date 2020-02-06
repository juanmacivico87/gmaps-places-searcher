<?php
/*
Plugin Name: Google Maps - Places Searcher
Plugin URI: https://www.juanmacivico87.com
Description: This plugin allows you to add to your website a map that display the places you have defined in the custom post type «Places» and search them
Version: 1.0
Author: Juan Manuel Civico Cabrera
Author URI: https://www.juanmacivico87.com
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  jmc87_gmaps_places_searcher
Domain Path:  /languages

Google Maps - Places Searcher is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Google Maps - Places Searcher is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Google Maps - Places Searcher. If not, see https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined( 'ABSPATH' ) )
    exit;

if ( !defined( 'GMAPS_PLACES_SEARCHER_LANG_DIR' ) )
    define( 'GMAPS_PLACES_SEARCHER_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages' );

if ( !defined( 'GMAPS_PLACES_SEARCHER_PLUGIN_DIR' ) )
    define( 'GMAPS_PLACES_SEARCHER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( !defined( 'GMAPS_PLACES_SEARCHER_PLUGIN_URL' ) )
    define( 'GMAPS_PLACES_SEARCHER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function jmc87_gmaps_places_searcher_plugin_install()
{
    if ( !current_user_can( 'activate_plugins' ) )
        wp_die( __( 'Don\'t have enough permissions to install this plugin.', 'jmc87_gmaps_places_searcher' ) . '<br /><a href="' . admin_url( 'plugins.php' ) . '">&laquo; ' . __( 'Back to plugins page.', 'jmc87_gmaps_places_searcher' ) . '</a>' );

    if ( !is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) )
        wp_die( __( 'It is necessary for the <a href="https://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Field</a> plugin to be active for this plugin to work properly', 'jmc87_gmaps_places_searcher' ) . '<br /><a href="' . admin_url( 'plugins.php' ) . '">&laquo; ' . __( 'Back to plugins page.', 'jmc87_gmaps_places_searcher' ) . '</a>' );
}
register_activation_hook( __FILE__, 'jmc87_gmaps_places_searcher_plugin_install' );

function jmc87_gmaps_places_searcher_plugin_deactivation()
{
    if ( !current_user_can( 'activate_plugins' ) )
        wp_die( __( 'Don\'t have enough permissions to disable this plugin.', 'jmc87_gmaps_places_searcher' ) . '<br /><a href="' . admin_url( 'plugins.php' ) . '">&laquo; ' . __( 'Back to plugins page.', 'jmc87_gmaps_places_searcher' ) . '</a>' );
}
register_deactivation_hook( __FILE__, 'jmc87_gmaps_places_searcher_plugin_deactivation' );

function jmc87_gmaps_places_searcher_plugin_uninstall()
{
    if ( !current_user_can( 'activate_plugins' ) )
        wp_die( __( 'Don\'t have enough permissions to uninstall this plugin.', 'jmc87_gmaps_places_searcher' ) . '<br /><a href="' . admin_url( 'plugins.php' ) . '">&laquo; ' . __( 'Back to plugins page.', 'jmc87_gmaps_places_searcher' ) . '</a>' );
    else
    {
        delete_option( '_jmc87_google_maps_api_key' );
    }
}
register_uninstall_hook( __FILE__, 'jmc87_gmaps_places_searcher_plugin_uninstall' );

require 'config/config.php';
$config = new JMC87_GMapsPlacesSearcherConfig();

require 'src/customPostsTypes/places/places.php';
$centers = new JMC87_PlacesPostType();

require 'src/customizerSection/google-maps-api-key.php';
$google_maps_api_key = new JMC87_GMapsPlacesSearcherAPIKey();

require 'src/customBlocks/googleMaps/google-maps.php';
$google_maps_block = new JMC87_GoogleMapsBlock();
