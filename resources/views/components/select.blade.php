@props(['label', 'name'])
<div {{ $attributes->only('class') }}><label class="form-label" for="{{ $name }}">{{ $label }}</label><select id="{{ $name }}" name="{{ $name }}" {{ $attributes->except('class')->class(['form-select']) }}>{{ $slot }}</select></div>
