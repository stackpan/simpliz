@props(['iteration' => true, 'headers', 'values', 'action'])

<table {!! $attributes->merge(['class' => '']) !!}>
    <thead class="text-lg">
        <tr class="bg-gray-400 border-b-2 border-b-white [&>th]:px-2 [&>th]:py-1 [&>th]:border-r-4 [&>th]:border-r-gray-200 [&>th:last-child]:border-none">
            @if($iteration)
            <th>{{ __('No.') }}</th>
            @endif
            @foreach($headers as $header)
            <th>{{ __($header) }}</th>
            @endforeach
            @isset($action)
            <th>{{ __('Actions') }}</th>
            @endisset
        </tr>
    </thead>
    <tbody>
        @for($i = 0; $i < count($values); $i++)
        <tr class="bg-gray-200 border-b-2 border-b-white last-of-type:border-none [&>td]:px-4 [&>td]:py-1 [&>td]:border-r-4 [&>td]:border-r-gray-400 [&>td:last-child]:border-none">
            @if($iteration)
            <td>{{ $i + 1 }}.</td>
            @endif
            @foreach($values[$i] as $key => $value)
                @if ($key === 'id')
                    @continue
                @endif
            <td>{{ $value }}</td>
            @endforeach
            @isset($action)
            <td class="flex justify-center gap-4">
                <form action="{{ route($action . '.edit', $values[$i]['id']) }}" method="GET">
                    @csrf
                    <button type="submit" class="text-purple-500 active:text-purple-300 uppercase">{{ __('Edit') }}</button>
                </form>
                <form action="{{ route($action . '.destroy', $values[$i]['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 active:text-red-400 uppercase">{{ __('Delete') }}</button>
                </form>
            </td>
            @endisset
        </tr>
        @endfor
    </tbody>
</table>