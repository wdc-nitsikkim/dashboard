<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">{{ $title }}</h5>

                    @isset($subheading)
                        <p>{{ $subheading }}</p>
                    @endisset

                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ $formAction }}" method="{{ $formMethod ?? 'POST' }}">
                {{ csrf_field() }}

                <div class="modal-body">
                    {{ $slot }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $submitBtnText ?? 'Submit' }}</button>
                </div>
            </form>

        </div>
    </div>
</div>
