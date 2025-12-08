@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-soft-lilac uppercase tracking-wider']) }}>
    {{ $value ?? $slot }}
</label>
