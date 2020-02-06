const centers = JSON.parse( js_vars.centers );

var map     = null;
var latLng  = { lat: 40.4168107, lng: -3.7036033 };
var results = [];

var loadMapsAPI = () => {
    let script  = document.createElement( 'script' );
    script.type = 'text/javascript';
    script.src  = 'https://maps.googleapis.com/maps/api/js?key=' + js_vars.api_key + '&callback=initMap&libraries=geometry';
    document.body.appendChild( script );
}
loadMapsAPI();

var initMap = () => {
    const mapOptions = {
        zoom: 15,
        center: latLng
    }

    map = new google.maps.Map( document.getElementById( 'map' ), mapOptions );
    renderCentersOnMap();
}

const renderCentersOnMap = () => {
    let infoWindow  = [];
    let openWindow  = null;
    let markerPlace = [];

    for( center of centers ) {
        infoWindow[center.ID] = new google.maps.InfoWindow( {
            content: '<div class="info-window"><h3>' + center.name + '</h3><p>' + center.description + '</p><p><a href="' + center.url + '">Ver centro</a></p></div>',
        } );

        markerPlace[center.ID] = new google.maps.Marker( {
            map: map,
            position: new google.maps.LatLng( center.location[0], center.location[1] ),
            icon: js_vars.plugin_url + 'src/customBlocks/googleMaps/images/pin.png',
            title: center.name
        } );

        let centerID = center.ID;
        markerPlace[center.ID].addListener( 'click', () => {
            if ( openWindow )
                eval( openWindow ).close();

            openWindow = 'infoWindow[' + centerID + ']';
            eval( openWindow ).open( map, markerPlace[centerID] );
        } );
    }
}

const renderNearestCenter = ( zipCode, pointA ) => {
    let sortCenters = [];
    
    for( center of centers ) {
        let pointB = new google.maps.LatLng( center.location[0], center.location[1] );
        let currentCenter = [
            google.maps.geometry.spherical.computeDistanceBetween( pointA, pointB ),
            center.location[0],
            center.location[1],
            center.name,
            center.url
        ];

        sortCenters.push( currentCenter );
    }

    sortCenters.sort( ( centerA, centerB ) => {
        return centerA[0] - centerB[0];
    } );

    if ( sortCenters[0][0] <= 50000 ) {
        results = 'El centro más cercano al código postal ' + zipCode + ' es el de ' + sortCenters[0][3] + ' <a href="' + sortCenters[0][4] + '" target="_blank">[Ver centro]</a>';
        map.setCenter( { lat: parseFloat( sortCenters[0][1] ), lng: parseFloat( sortCenters[0][2] ) } );
    } else {
        results = 'No se ha encontrado ningún centro en un radio de 50km para el código postal ' + zipCode + ' <a href="' + js_vars.all_places_link + '" target="_blank">[Ver todos]</a>';
    }
    jQuery( '#results' ).html( results );
}

const setAddress = ( newAddress ) => {
    newAddress   = newAddress.replace( ' ', '+' );
    let geocoder = new google.maps.Geocoder;
    
    geocoder.geocode( { 'address': newAddress, region: 'es' }, ( results, status ) => {
        if ( status === 'OK' ) {
            renderNearestCenter( newAddress, results[0].geometry.location );
        } else {
            alert( status );
        }
    } );
}

jQuery( '#search' ).on( 'click', () => {
    let address  = jQuery( 'input[name="address"]' ).val().replace( ' ', '+' );
    let geocoder = new google.maps.Geocoder;

    geocoder.geocode( { 'address': address, region: 'es' }, ( results, status ) => {
        if ( status === 'OK' ) {
            renderNearestCenter( address, results[0].geometry.location );
        } else {
            alert( status );
        }
    } );
} );
