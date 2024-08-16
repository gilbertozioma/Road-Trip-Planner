@extends('layouts.app')

@section('title', __('destination.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\Destination)
            <a href="{{ route('destinations.create') }}" class="btn btn-success">{{ __('destination.create') }}</a>
        @endcan
    </div>
    <h1 class="page-title">{{ __('destination.list') }} <small>{{ __('app.total') }} : {{ $destinations->total() }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('destination.search') }}</label>
                        <input placeholder="{{ __('destination.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('destination.search') }}" class="btn btn-secondary">
                    <a href="{{ route('destinations.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('destination.name') }}</th>
                        <th>{{ __('destination.address') }}</th>
                        <th>{{ __('destination.latitude') }}</th>
                        <th>{{ __('destination.longitude') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody id="destination-table-body">
                    @forelse($destinations as $key => $destination)
                    <tr data-destination-id="{{ $destination->id }}">
                        <td class="text-center">{{ $destinations->firstItem() + $key }}</td>
                        <td>{!! $destination->name_link !!}</td>
                        <td>{{ $destination->address }}</td>
                        <td>{{ $destination->latitude }}</td>
                        <td>{{ $destination->longitude }}</td>
                        <td class="text-center">
                            <a href="{{ route('destinations.show', $destination) }}" id="show-destination-{{ $destination->id }}">{{ __('app.show') }}</a>
                        </td>
                    </tr>
                    @empty
                    <p class="text-center p-5">No destinatin available.</p>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $destinations->appends(Request::except('page'))->render() }}</div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("#destination-table-body").sortable({
            items: "tr",
            cursor: "move",
            update: function(event, ui) {
                // Get the updated order of the rows
                var newOrder = $(this).sortable('toArray', { attribute: 'data-destination-id' });

                // Send the new order to the server using AJAX
                $.ajax({
                    url: "{{ route('destinations.updateOrder') }}",
                    type: "POST",
                    data: {
                        order: newOrder,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                    },
                    error: function(error) {
                        // Handle error response
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
@endpush
