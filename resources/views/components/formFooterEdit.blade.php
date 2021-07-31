<div class="row mb-3">
    <div class="col-sm-3 d-grid mx-auto mb-3">
        <a class="btn btn-primary" href="{{ $returnRoute }}">
            <span class="material-icons me-1">keyboard_arrow_left</span>
            Go back
        </a>
    </div>
    <div class="col-sm-3 d-grid mx-auto mb-3">
        <button class="btn btn-info" type="reset">
            <span class="material-icons me-1">undo</span>
            Reset
        </button>
    </div>
    <div class="col-sm-6 d-grid mx-auto mb-3">
        <button class="btn btn-success" type="submit">
            {{ $submitBtnTxt ?? 'Update' }}
            <span class="material-icons ms-1">update</span>
        </button>
    </div>
</div>
