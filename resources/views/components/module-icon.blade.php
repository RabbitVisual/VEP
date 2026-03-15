@props([
    'alias',
    'style' => null,
    'class' => '',
])

@php
    $icon = module_icon($alias);
    $iconStyle = $style ?? module_config($alias, 'icon_style', 'duotone');
@endphp

<x-icon
    :name="$icon"
    :style="$iconStyle"
    :class="$class"
    {{ $attributes }}
/>
