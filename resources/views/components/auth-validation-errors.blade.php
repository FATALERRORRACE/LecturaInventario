@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-1 p-2 border-1 border-rose-200 rounded']) }}>
        <div class="font-medium text-red-600 text-center">
            {{ __('Error.') }}
        </div>

        <ul class="mt-1 list-disc list-inside text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
