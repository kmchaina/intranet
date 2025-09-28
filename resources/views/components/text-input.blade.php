@props([
	'disabled' => false,
	'variant' => null,
])

@php
	$base = 'nimr-input';
	if ($variant === 'glass') {
		$base .= ' glass-input';
	}
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $base]) }}> 
