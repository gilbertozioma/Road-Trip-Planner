@extends('layouts.app')

@section('title', __('destination.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('destination.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('destination.name') }}</td><td>{{ $destination->name }}</td></tr>
                        <tr><td>{{ __('destination.address') }}</td><td>{{ $destination->address }}</td></tr>
                        <tr><td>{{ __('destination.latitude') }}</td><td>{{ $destination->latitude }}</td></tr>
                        <tr><td>{{ __('destination.longitude') }}</td><td>{{ $destination->longitude }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $destination)
                    <a href="{{ route('destinations.edit', $destination) }}" id="edit-destination-{{ $destination->id }}" class="btn btn-success">{{ __('destination.edit') }}</a>
                @endcan
                <a href="{{ route('destinations.create') }}" class="btn btn-success">{{ __('destination.create') }}</a>
                @if(auth()->check())
                    <a href="{{ route('destinations.index') }}" class="btn btn-primary">{{ __('destination.back_to_index') }}</a>
                @else
                    <a href="{{ route('destination_map.index') }}" class="btn btn-link">{{ __('destination.back_to_index') }}</a>
                @endif
            </div>
        </div>
        @if ($destination->count() < 0)
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
                    <p class="text-center">Please add more than 1 destination to calculate summary.</p>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ trans('destination.location') }}</div>
            @if ($destination->coordinate)
            <div class="card-body" id="mapid"></div>
            @else
            <div class="card-body">{{ __('destination.no_coordinate') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

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
        name: '{{ $destination->name }}',
        address: '{{ $destination->address }}',
        latitude: '{{ $destination->latitude }}',
        longitude: '{{ $destination->longitude }}'
    }));

    var map = L.map('mapid').setView([{{ $destination->latitude }}, {{ $destination->longitude }}], {{ config('destination.detail_zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}]).addTo(map)
        .bindPopup('{!! $destination->map_popup_content !!}');
</script>
@endpush
