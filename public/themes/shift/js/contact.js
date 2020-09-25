var contact = new contact();

function contact()
{
    init();

    function init()
    {
        google.maps.event.addDomListener(window, 'load', initMap);
    }

    function initMap() 
    {
        var location   = new google.maps.LatLng(50.0875726, 14.4189987);
        var mapCanvas  = document.getElementById('map');
        var mapOptions = 
        {
            center: location,
            zoom: 16,
            panControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        var map = new google.maps.Map(mapCanvas, mapOptions);

        var marker = new google.maps.Marker({
            position: location,
            map: map,
            icon: markerImage
        });

        var infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 400
        });

        marker.addListener('click', function () {
            infowindow.open(map, marker);
        });
    }
}