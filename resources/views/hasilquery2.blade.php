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
                <p>Tidak ada kejadian bencana bersangkutan.</p>
            @else
                <div id="mapid"></div>

                <script type="text/javascript">
                    var iter_koleksi = 1;
                    var koleksi = [];
                    var idx_koleksi = 0;
                    var ukuran_data_awal = {!!json_encode(sizeof($result))!!};
                    var component_prop;
                    var mapid;
                    var layar;

                    @foreach ($result as $key=>$res)
                        L.mapbox.accessToken = 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw';

                        function onEachFeature(feature, layer) {
                             if (feature.properties && feature.properties.popupContent) {
                                layer.bindPopup(feature.properties.popupContent);
                            }
                        }

                        function nguji(){
                            if (iter_koleksi >= idx_koleksi){
                                clearInterval(myInterval);
                                console.log(idx_koleksi);
                            }
                            else{
                                window.mapid.removeLayer(layar);
                                console.log(iter_koleksi);
                                console.log(koleksi[iter_koleksi]);

                                var region = koleksi[iter_koleksi]['region'];
                                var region_prop = region['0'];
                                var disaster_id = region_prop['disaster_id'];
                                var geom = JSON.parse(region_prop['st_asgeojson']);
                                var coord = geom['coordinates'];

                                component_prop = {
                                    "type": "Feature",
                                    "geometry": geom
                                };

                                layar = L.geoJson(component_prop).addTo(mapid);

                                iter_koleksi += 1;
                            }

                        }

                        var key = {!!json_encode($key)!!};
                        var raw_data = {!!json_encode($res)!!};

                        var start_time = raw_data['start_time'];
                        var end_time = raw_data['end_time'];
                        var region = raw_data['region'];
                        var region_prop = region['0'];
                        
                        if (region_prop != null){
                            koleksi[idx_koleksi] = raw_data;
                            idx_koleksi += 1;
                            var disaster_id = region_prop['disaster_id'];
                            var geom = JSON.parse(region_prop['st_asgeojson']);
                            var coord = geom['coordinates'];

                            component_prop = {
                                "type": "Feature",
                                "geometry": geom
                            };

                            if (key == 0){
                                mapid = L.mapbox.map('mapid', 'mapbox.streets').setView([coord[0][0][0][1], coord[0][0][0][0]], 12);
                                layar = L.geoJson(component_prop).addTo(mapid)
                            }
                        }

                        if (key == ukuran_data_awal-1){
                            console.log('finish');
                            var myInterval = setInterval(nguji,3000);
                        }

                    @endforeach
                </script>
            @endif
    </body>
</html>