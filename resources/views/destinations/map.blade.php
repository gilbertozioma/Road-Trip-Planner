@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="">
            <div class="float-right">
                @auth
                <a href="{{ route('destinations.create') }}" class="btn btn-primary">Create a Destination</a>
                @endauth
            </div>
        </div>
        <br><br>
        <div class="card">
            <div class="card-body" id="mapid"></div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
    <style>
        #mapid { min-height: 400px; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>

    <script>
        var map = L.map('mapid').setView([{{ config('destination.map_center_latitude') }}, {{ config('destination.map_center_longitude') }}], {{ config('destination.zoom_level') }});
        var baseUrl = "{{ url('/') }}";

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        axios.get('{{ route('api.destinations.index') }}')
        .then(function (response) {
            console.log(response.data);
            L.geoJSON(response.data, {
                pointToLayer: function(geoJsonPoint, latlng) {
                    return L.marker(latlng);
                }
            })
            .bindPopup(function (layer) {
                return layer.feature.properties.map_popup_content;
            }).addTo(map);
        })
        .catch(function (error) {
            console.log(error);
        });

        @can('create', new App\Models\Destination)
            var theMarker;

            map.on('click', function(e) {
                let latitude = e.latlng.lat.toString().substring(0, 15);
                let longitude = e.latlng.lng.toString().substring(0, 15);

                if (theMarker != undefined) {
                    map.removeLayer(theMarker);
                };

                var popupContent = "Your location : " + latitude + ", " + longitude + ".";
                popupContent += '<br><a href="{{ route('destinations.create') }}?latitude=' + latitude + '&longitude=' + longitude + '">Add new destination here</a>';

                theMarker = L.marker([latitude, longitude]).addTo(map);
                theMarker.bindPopup(popupContent)
                .openPopup();
            });
        @endcan
    </script>
@endpush
