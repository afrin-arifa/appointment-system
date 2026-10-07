@props(['label', 'name'])
<div {{ $attributes->only('class') }}><label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="4" {{ $attributes->except('class')->class(['form-control']) }}>{{ $slot }}</textarea>
</div>
