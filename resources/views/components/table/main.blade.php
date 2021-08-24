{{--
    $head -> (any) html
    $body -> (any) html
--}}

<div class="table-responsive">
    <table class="table {{ $classes ?? '' }} table-centered table-nowrap mb-3 rounded" {!! $attr ?? '' !!}>
        {{ $head }}
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>
