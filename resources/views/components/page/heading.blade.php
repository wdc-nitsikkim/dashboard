<div class="my-3">
    <div class="d-flex justify-content-between">
        <div class="mb-3 mb-lg-0">
            <h1 class="h3">
                {{ $heading ?? 'Dashboard' }}
            </h1>

            @isset($subheading)
                <p class="mb-0">{{ $subheading }}</p>
            @endisset

        </div>
        <div class="text-nowrap">
            {{ $sideButtons ?? '' }}
        </div>
    </div>
</div>
