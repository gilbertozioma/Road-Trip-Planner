@extends('layouts.app')

@section('content')
@if (isset($lastDestination))
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('destination.detail') }}</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr><td>{{ __('destination.name') }}</td><td>{{ $lastDestination->name }}</td></tr>
                            <tr><td>{{ __('destination.address') }}</td><td>{{ $lastDestination->address }}</td></tr>
                            <tr><td>{{ __('destination.latitude') }}</td><td>{{ $lastDestination->latitude }}</td></tr>
                            <tr><td>{{ __('destination.longitude') }}</td><td>{{ $lastDestination->longitude }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @can('update', $lastDestination)
                        <a href="{{ route('destinations.edit', $lastDestination) }}" id="edit-destination-{{ $lastDestination->id }}" class="btn btn-success">{{ __('destination.edit') }}</a>
                    @endcan
                    <a href="{{ route('destinations.create') }}" class="btn btn-success">{{ __('destination.create') }}</a>
                    @if(auth()->check())
                        <a href="{{ route('destinations.index') }}" class="btn btn-primary">{{ __('destination.back_to_index') }}</a>
                    @else
                        <a href="{{ route('destination_map.index') }}" class="btn btn-link">{{ __('destination.back_to_index') }}</a>
                    @endif
                </div>
            </div>
            @if ($hasMultipleDestinations)
                <div class="card mt-3">
                    <div class="card-header">{{ __('Journey Summary') }}</div>
                    <div class="card-body">
                        <p>Total Distance: {{ $summary['totalDistance'] }} km</p>
                        <p>Total Time: {{ $summary['totalTime'] }} minutes</p>
                    </div>
                </div>
            @else
                <div class="card mt-3">
                    <div class="card-header">{{ __('Journey Summary') }}</div>
                    <div class="card-body">
                        <p class="text-center">Please add more than 1 destination to calculate the summary.</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ trans('destination.location') }}</div>
                @if ($lastDestination->coordinate)
                <div class="card-body" id="mapid"></div>
                @else
                <div class="card-body">{{ __('destination.no_coordinate') }}</div>
                @endif
            </div>
        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"/>

        <style>
            #mapid { height: 400px; }
        </style>
    @endpush

    @push('scripts')
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

        <script>
            // Store destination data in local storage
            localStorage.setItem('destinationData', JSON.stringify({
                name: '{{ $lastDestination->name }}',
                address: '{{ $lastDestination->address }}',
                latitude: '{{ $lastDestination->latitude }}',
                longitude: '{{ $lastDestination->longitude }}'
            }));

            var map = L.map('mapid').setView([{{ $lastDestination->latitude }}, {{ $lastDestination->longitude }}], {{ config('destination.detail_zoom_level') }});

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([{{ $lastDestination->latitude }}, {{ $lastDestination->longitude }}]).addTo(map)
                .bindPopup('{!! $lastDestination->map_popup_content !!}');
        </script>
    @endpush
@else
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <h4 class="card-body text-center">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </h4>

                    <p class="text-center">
                        <a href="{{ route('destinations.create') }}" class="text-center mt-2 btn btn-success">Create a destination</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
