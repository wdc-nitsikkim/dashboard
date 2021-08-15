{{--
    $department -> single department model
    $profiles -> collection of profile model
--}}

@extends('layouts.admin', ['title' => 'Re-order People - ' . $department->name])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Re-order People
    @endslot

    @slot('subheading')
        Department of {{ $department->name }}
        <br>
        <span class="text-info"><span class="fw-bolder">NOTE:</span> The faculty & staff pages are different,
            therefore the order shown here will reflect just on the respective pages, irrespective of the
            combined order shown here.
        </span>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'backRedirect' => route('admin.department.home', $department->code)
        ])
    @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">

                @if ($profiles->count() == 0)
                    <p class="text-danger">No profiles to order in this department!</p>
                @else
                    <form method="POST" id="order-people"
                        action="{{ route('admin.department.saveOrder', $department->code) }}">

                        {{ csrf_field() }}

                        @component('components.table.main', [ 'attr' => 'orderable' ])
                            @slot('head')
                                @component('components.table.head', [
                                    'items' => [
                                        'Re-order', '#', 'Type', 'Name', 'Designation',
                                        'Joined On'
                                    ]
                                ])
                                @endcomponent
                            @endslot

                            @slot('body')
                                @foreach ($profiles as $profile)
                                    <tr order-id="{{ $profile->id }}" order-index="{{ $loop->iteration }}">
                                        <td>

                                            @component('components.inline.anchorLink', [
                                                'align' => '',
                                                'classes' => 'text-success',
                                                'icon' => 'arrow_upward',
                                                'scale' => true,
                                                'tooltip' => 'Move up',
                                                'attr' => 'order-up'
                                            ])
                                            @endcomponent

                                            @component('components.inline.anchorLink', [
                                                'align' => 'ms-1',
                                                'classes' => 'text-danger',
                                                'icon' => 'arrow_downward',
                                                'scale' => true,
                                                'tooltip' => 'Move down',
                                                'attr' => 'order-down'
                                            ])
                                            @endcomponent

                                        </td>
                                        <td class="fw-bolder">{{ $loop->iteration }}</td>
                                        <td class="fw-bolder">{{ ucfirst($profile->type) }}</td>
                                        <td>{{ $profile->name }}</td>
                                        <td class="fw-bolder">
                                            {{ $profile->designation }}
                                            @if ($profile->hod != null)
                                                <span class="badge bg-success ms-1">
                                                    HoD
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $profile->created_at }}</td>
                                    </tr>
                                @endforeach
                            @endslot
                        @endcomponent

                        <button class="btn btn-primary animate-up-2 mt-2" type="submit" confirm
                            alert-title="Save this order?" alert-text="-" alert-timer="5000">
                            Save</button>
                    </form>

                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/table-reorder.js') }}"></script>
    <script src="{{ asset('static/js/reorder-people.js') }}"></script>
@endpush
