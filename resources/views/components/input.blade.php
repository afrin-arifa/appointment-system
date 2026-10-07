@props(['label', 'name', 'type' => 'text', 'value' => ''])
<div {{ $attributes->only('class') }}><label class="form-label" for="{{ $name }}">{{ $label }}</label><input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" {{ $attributes->except('class')->class(['form-control']) }}></div>
