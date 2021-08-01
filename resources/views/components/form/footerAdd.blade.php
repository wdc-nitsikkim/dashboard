<div class="row mb-3">
    <div class="col-sm-4 d-grid gap-1 mx-auto mb-3">
        <a class="btn btn-primary" href="{{ $returnRoute }}">
            Cancel
            <span class="material-icons ms-1">cancel</span>
        </a>
    </div>
    <div class="col-sm-8 d-grid gap-1 mx-auto mb-3">
        <button class="btn btn-success" type="submit">
            {{ $submitBtnTxt ?? 'Submit' }}
            <span class="material-icons ms-1">keyboard_arrow_right</span>
        </button>
    </div>
</div>
