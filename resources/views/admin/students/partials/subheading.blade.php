@if ($batch['type'] == 'b')
    B.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 4)  }}),
@else
    M.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 2) }}),
@endif
