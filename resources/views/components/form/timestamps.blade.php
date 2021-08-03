@php
    $currentTime = strtotime(CustomHelper::getCurrentDate());
    $lastUpdated = strtotime($updatedAt);

    if (session()->has('status') && session('status') == 'fail') {
        $classes = 'is-invalid';
        $smallText = 'Update failed!';
        $smallTextClass = 'text-danger';
    } else if ($currentTime - $lastUpdated <= 60) {
        $classes = 'is-valid';
        $smallText = 'Recently updated';
        $smallTextClass = 'text-success';
    }
@endphp

<div class="row g-2 mb-3">
    <div class="col-sm-4 mb-2">
        <div class="form-floating">
            <input type="text" class="form-control" id="created_at" placeholder="Created At"
                value="{{ $createdAt ?? '-' }}" disabled>
            <label for="created_at">Created At</label>
        </div>
    </div>
    <div class="col-sm-4 mb-2">
        <div class="form-floating">
            <input type="text" class="form-control {{ $classes ?? '' }}" id="updated_at" placeholder="Last Updated"
                value="{{ $updatedAt ?? '-' }}" disabled>
            <label for="updated_at">Last Updated</label>

            @isset($smallText)
                <small class="text-muted {{ $smallTextClass }}">{{ $smallText }}</small>
            @endisset
        </div>
    </div>

    {{ $slot ?? '' }}
</div>
