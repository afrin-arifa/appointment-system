@props(['type' => 'button', 'variant' => 'primary'])
<button type="{{ $type }}" {{ $attributes->class(['btn', 'btn-primary' => $variant === 'primary', 'btn-success' => $variant === 'success', 'btn-danger' => $variant === 'danger', 'btn-secondary' => $variant === 'secondary']) }}>{{ $slot }}</button>
