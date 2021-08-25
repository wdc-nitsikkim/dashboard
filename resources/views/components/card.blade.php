{{--
    $image -> string (public storage path)
    $name -> string
    $primaryText -> string (optional)
    $secondaryText -> string (optional)
    $type -> string
    $mobile -> string
    $email -> string
--}}

<div class="card border-0 shadow h-100">
    <div class="card-body">
        <div class="row d-block d-xl-flex align-items-center">
            <div class="col-12 col-xl-4 text-xl-center mb-3 mb-xl-0 d-flex
                align-items-center justify-content-xl-center">
                <div class="icon-shape rounded me-4 me-sm-0">

                    @component('components.image', [
                        'image' => $image
                    ])
                    @endcomponent

                </div>
                <div class="d-sm-none">
                    <h5 class="fw-bold mb-2">{{ $name }}</h5>
                    <h6 class="text-gray-500 mb-0">{{ $primaryText ?? '' }}</h6>
                </div>
            </div>

            <div class="col-12 col-xl-8 px-xl-0">
                <div class="d-none d-sm-block">
                    <h5 class="fw-bold text-truncate mb-2" data-bs-toggle="tooltip"
                        title="{{ $name }}">{{ $name }}</h5>
                    <h6 class="text-gray-500 text-truncate mb-0">
                        {!! $primaryText ?? '' !!}</h6>
                </div>
                <small class="d-flex align-items-center text-gray-500">
                    <span class="text-truncate">{!! $secondaryText ?? '' !!}</span>
                </small>
                <div class="small d-flex-inline text-truncate mt-1">
                    <a href="tel:{{ $mobile }}" class="text-info">
                        <span class="material-icons fs-6">call</span>
                        {{ $mobile }}
                    </a>, &nbsp;
                    <a href="mailto:{{ $email }}" class="text-info">
                        <span class="material-icons fs-6">mail_outline</span>
                        {{ $email }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{ $slot ?? '' }}
</div>
