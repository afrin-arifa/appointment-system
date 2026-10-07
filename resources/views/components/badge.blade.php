@props(['type' => 'success'])
<span {{ $attributes->class(['badge', 'text-bg-success' => $type === 'success', 'text-bg-danger' => $type === 'danger', 'text-bg-warning' => $type === 'warning', 'text-bg-info' => $type === 'info', 'text-bg-secondary' => ! in_array($type, ['success', 'danger', 'warning', 'info'])]) }}>{{ $slot }}</span>
