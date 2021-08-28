{{--
    $class -> string
    $info -> (inherited)
    $errors -> (inherited)
    $selectMenu -> (inherited)
    $requiredField -> (inherited)
--}}

<div class="col-6 col-sm-3 col-lg-2 mb-2">
    <div class="form-floating">
        <input type="number"
            class="form-control {{ $errors->has($class . '_score') ? 'is-invalid' : '' }}"
            id="{{ $class }}_score" placeholder="Score" name="{{ $class }}_score"
            value="{{ old($class . '_score') ?? $info[$class . '_score'] }}" min="0" step="0.01" required>
        <label for="{{ $class }}_score">Score {!! $requiredField !!}</label>

        @if ($errors->has($class . '_score'))
            <div class="invalid-feedback">
                {{ $errors->first($class . '_score') }}
            </div>
        @endif

    </div>
</div>
<div class="col-6 col-sm-3 col-lg-2 mb-2">
    <div class="form-floating">
        <select class="form-select {{ $errors->has($class . '_marking_scheme') ? 'is-invalid' : '' }}"
            id="{{ $class }}_marking_scheme" name="{{ $class }}_marking_scheme" required>
            <option value="" selected disabled>Select</option>

            @foreach ($selectMenu['marking_schemes'] as $scheme)
                <option value="{{ $scheme }}"
                    {{ (old($class . '_marking_scheme') ?? $info[$class . '_marking_scheme']) == $scheme ? 'selected' : '' }}>
                    {{ strtoupper($scheme) }}
                </option>
            @endforeach

        </select>
        <label for="department">Marking Scheme {!! $requiredField !!}</label>

        @if ($errors->has($class . '_marking_scheme'))
            <div class="invalid-feedback">
                {{ $errors->first($class . '_marking_scheme') }}
            </div>
        @endif

    </div>
</div>
<div class="col-6 col-sm-3 col-lg-2 mb-2">
    <div class="form-floating">
        <select class="form-select {{ $errors->has($class . '_board') ? 'is-invalid' : '' }}"
            id="{{ $class }}_board" name="{{ $class }}_board" required>
            <option value="" selected disabled>Select</option>

            @foreach ($selectMenu['school_boards'] as $board)
                <option value="{{ $board }}"
                    {{ (old($class . '_board') ?? $info[$class . '_board']) == $board ? 'selected' : '' }}>
                    {{ strtoupper($board) }}
                </option>
            @endforeach

        </select>
        <label for="department">Board {!! $requiredField !!}</label>

        @if ($errors->has($class . '_board'))
            <div class="invalid-feedback">
                {{ $errors->first($class . '_board') }}
            </div>
        @endif

    </div>
</div>
<div class="col-6 col-sm-3 col-lg-2 mb-2">
    <div class="form-floating">
        <input type="number"
            class="form-control {{ $errors->has($class . '_passing_year') ? 'is-invalid' : '' }}"
            id="{{ $class }}_passing_year" placeholder="Passing Year" name="{{ $class }}_passing_year"
            value="{{ old($class . '_passing_year') ?? $info[$class . '_passing_year']  }}"
            min="2010" max="{{ date('Y') }}" required>
        <label for="{{ $class }}_passing_year">Passing Year {!! $requiredField !!}</label>

        @if ($errors->has($class . '_passing_year'))
            <div class="invalid-feedback">
                {{ $errors->first($class . '_passing_year') }}
            </div>
        @endif

    </div>
</div>
<div class="col-12 col-sm-6 col-lg-4 mb-2">
    <div class="form-floating">
        <input type="text"
            class="form-control {{ $errors->has($class . '_board_other') ? 'is-invalid' : '' }}"
            id="{{ $class }}_board_other" placeholder="Board (Other)" name="{{ $class }}_board_other"
            value="{{ old($class . '_board_other') ?? $info[$class . '_board_other'] }}">
        <label for="{{ $class }}_board_other">Board (Other)</label>
        <small class="helper-text small ms-1">For <span class="fw-bolder text-info">other</span>
            boards only</small>

        @if ($errors->has($class . '_board_other'))
            <div class="invalid-feedback">
                {{ $errors->first($class . '_board_other') }}
            </div>
        @endif

    </div>
</div>
