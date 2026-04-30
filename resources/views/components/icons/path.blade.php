@props([
  'path' => '',
  'viewBox' => '0 0 24 24',
  'strokeWidth' => '1.5',
])

@if($path)
  <svg {{ $attributes->merge(['fill' => 'none', 'viewBox' => $viewBox, 'stroke' => 'currentColor', 'stroke-width' => $strokeWidth, 'aria-hidden' => 'true']) }}>
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
  </svg>
@endif
