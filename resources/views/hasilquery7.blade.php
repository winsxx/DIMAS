<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="{{ URL::asset('leaflet/leaflet.css')}}" />

        <link href='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css' rel='stylesheet' />

    </head>
    <body>
        <script src="{{ URL::asset('leaflet/leaflet-src.js')}}"></script>
        <script src='https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js'></script>

            @if (sizeof($result) == 0)
                <p>Tidak ada fasilitas medikal di wilayah ini.</p>
            @else
                <div id="mapid"></div>

                @foreach ($result as $key=>$res)
                     <script type="text/javascript">
                        L.mapbox.accessToken = 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw';
                        var key = {!!json_encode($key)!!}
                        var raw_data = {!!json_encode($res)!!};
                        var facility_name = {!!json_encode($res->facility_name)!!};
                        var geom = {!!json_encode(json_decode($res->st_asgeojson))!!};
                        var coord = geom['coordinates'];

                        console.log(key);
                        console.log(raw_data);
                        console.log(geom);
                        console.log(coord);

                        var component_prop = {
                            "type": "Feature",
                            "geometry": geom,
                            "properties": {
                                "popupContent": facility_name
                            }
                        };

                        function onEachFeature(feature, layer) {
                             if (feature.properties && feature.properties.popupContent) {
                                layer.bindPopup(feature.properties.popupContent);
                            }
                        }

                        var mapid;

                        if (key == 0){
                            mapid = L.mapbox.map('mapid', 'mapbox.streets').setView([coord[1], coord[0]], 5);
                        }

                        L.geoJson(component_prop, {onEachFeature: onEachFeature }).addTo(mapid);

                    </script>
                @endforeach
            @endif
    </body>
</html>