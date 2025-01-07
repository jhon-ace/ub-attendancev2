@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-black mb-2']) }}>
    {{ $value ?? $slot }}
</label>
