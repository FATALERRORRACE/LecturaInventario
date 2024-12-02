@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-gray-700 w-100']) }}>
    {{ $value ?? $slot }}
</label>
