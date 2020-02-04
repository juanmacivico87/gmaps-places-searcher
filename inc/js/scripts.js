const centers = [
    {
        "ID": 1,
        "name": "Madrid",
        "location": [40.4168107, -3.7036033],
        "url": "http://www.madrid.com"
    },
    {
        "ID": 2,
        "name": "Barcelona",
        "location": [41.3948976, 2.0787282],
        "url": "http://www.barcelona.com"
    },
    {
        "ID": 3,
        "name": "A Coruña",
        "location": [43.361904, -8.4301933],
        "url": "http://www.coruna.com"
    },
    {
        "ID": 4,
        "name": "Valencia",
        "location": [39.4078969, -0.431551],
        "url": "http://www.valencia.com"
    },
    {
        "ID": 5,
        "name": "Granada",
        "location": [37.1810095, -3.6262911],
        "url": "http://www.granada.com"
    },
    {
        "ID": 6,
        "name": "Las Palmas de Gran Canaria",
        "location": [28.117432, -15.4746365],
        "url": "http://www.laspalmasdegrancanaria.com"
    },
    {
        "ID": 7,
        "name": "Alcalá de Henares",
        "location": [40.4948212, -3.3841855],
        "url": "http://www.alcaladehenares.com"
    }
];

var map     = null;
var latLng  = { lat: 40.4168107, lng: -3.7036033 };
var results = [];

var loadMapsAPI = () => {
    let script  = document.createElement( 'script' );
    script.type = 'text/javascript';
    script.src  = 'https://maps.googleapis.com/maps/api/js?key=[YOUR-API-KEY]&callback=initMap&libraries=geometry';
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
            content: '<div class="info-window"><h1>' + center.name + '</h1><p><a href="' + center.url + '">Ver centro</a></p></div>',
        } );

        markerPlace[center.ID] = new google.maps.Marker( {
            map: map,
            position: new google.maps.LatLng( center.location[0], center.location[1] ),
            icon: 'inc/images/pin.png',
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
        let listItem = '<li>El centro más cercano al código postal ' + zipCode + ' es el de ' + sortCenters[0][3] + ' <a href="' + sortCenters[0][4] + '" target="_blank">[Ver centro]</a>...</li>'
        
        if ( sortCenters[1][0] <= 50000 )
            listItem += '<li>Y el segundo más cercano es el de ' + sortCenters[1][3] + ' <a href="' + sortCenters[1][4] + '" target="_blank">[Ver centro]</a></li>'

        results.push( listItem );
        map.setCenter( { lat: sortCenters[0][1], lng: sortCenters[0][2] } );
    } else
        results.push( '<li>No se ha encontrado ningún centro en un radio de 50km para el código postal ' + zipCode + ' <a href="http://www.todosloscentros.com" target="_blank">[Ver todos]</a></li>' );

    let code = '<ol>';
    for( result of results ) {
        code += result;
    }
    code += '</ol>';
    $( '#results' ).html( code );
}

$( '#search' ).on( 'click', () => {
    let address  = $( 'input[name="address"]' ).val().replace( ' ', '+' );
    let geocoder = new google.maps.Geocoder;
    
    geocoder.geocode( { 'address': address, region: 'es' }, ( results, status ) => {
        if ( status === 'OK' )
            renderNearestCenter( address, results[0].geometry.location );
        else
            alert( status );
    } );
} );
