{{--
    $items -> array
--}}

<thead class="{{ $classes ?? 'thead-light' }}">
    <tr>
        @foreach ($items as $item)
            <th class="border-0 col {{ $loop->first ? 'rounded-start' : '' }}
                {{ $loop->last ? 'rounded-end' : '' }}">
                {{ $item }}
            </th>
        @endforeach
    </tr>
</thead>
