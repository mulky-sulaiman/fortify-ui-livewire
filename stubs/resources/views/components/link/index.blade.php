@props([
    'spa' => true,
    'mode' => 'livewire', // livewire | htmx
    'styled' => false,
    'label' => null,
])
<a role="button" @if ($spa && $mode === 'livewire') wire:navigate data-hx-boost="false" data-hx-push-url="false" @endif
    @if ($spa && $mode === 'htmx') data-hx-boost="true" data-hx-push-url="true" @endif
    {{ $attributes->except(['wire:navigate', 'data-hx-boost', 'data-hx-push-url', 'role'])->merge(['class' => $styled ? 'font-medium underline underline-offset-4' : null]) }}>
    {{ $label ?? $slot }}
</a>
