<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="{{ URL::asset('leaflet/leaflet.css')}}" />

    </head>
    <body>
        <div id="mapid"></div>
        <script src="{{ URL::asset('leaflet/leaflet-src.js')}}"></script>
            @foreach ($result as $res)
                <script type="text/javascript">

                    var raw_data = {!!json_encode($res)!!};
                    var geom = {!!json_encode(json_decode($res->st_asgeojson))!!};
                    var init_coor = geom['coordinates'];
                    console.log(raw_data);
                    console.log(geom);
                    console.log(init_coor);
                    console.log(init_coor[0]);

                    var mapid = L.map('mapid').setView(init_coor[0], 13);

                    var freeJson = {
                        "type": "Feature",
                        "geometry": {
                            "type": "Point",
                            "coordinates": [init_coor[0][1], init_coor[0][0]]
                        },
                    };

                    // var freeJson2 = {
                    //     "type": "Feature",
                    //     "geometry": {
                    //         "type": "LineString",
                    //         "coordinates": [
                    //                     [-7.86201446, 112.9],
                    //                     [-7.9, 113.1]
                    //         ]
                    //     },
                    // };

                    // console.log(mapid);
                    console.log(freeJson);
                    // console.log(freeJson2);
                    console.log(freeJson['geometry']);
                    // console.log(freeJson2['geometry']);
                    console.log(freeJson['geometry']['coordinates'][0]);
                    console.log(freeJson['geometry']['coordinates'][1]);

                    // var asal = L.geoJson().addTo(mapid);
                    // asal.addData(freeJson);

                    var geojsonMarkerOptions = {
                        radius: 8,
                        fillColor: "#ff7800",
                        color: "#000",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    };

                    var coorsLayer = L.geoJson(freeJson, {
                        pointToLayer: function (feature, latlng) {
                            return L.circleMarker(latlng, geojsonMarkerOptions);
                        },

                        // onEachFeature: onEachFeature
                    }).addTo(mapid);


                </script>
            @endforeach
    </body>
</html>
