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
                <p>Tidak ada daerah yang terkena bencana ini</p>
            @else
                <div id="mapid"></div>
                @foreach ($result as $res)
                    <script type="text/javascript">
                        var raw_data = {!!json_encode($res)!!};
                        // console.log($res);
                        console.log(raw_data);

                        L.mapbox.accessToken = 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw';

                    </script>
                @endforeach
            @endif
    </body>
</html>
