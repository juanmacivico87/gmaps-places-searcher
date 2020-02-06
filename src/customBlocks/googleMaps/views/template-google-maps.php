<?php 
if ( is_admin() ) : 
    include GMAPS_PLACES_SEARCHER_PLUGIN_DIR . 'src/customBlocks/googleMaps/views/sides/back-side.php';
else :
    include GMAPS_PLACES_SEARCHER_PLUGIN_DIR . 'src/customBlocks/googleMaps/views/sides/front-side.php';
endif;