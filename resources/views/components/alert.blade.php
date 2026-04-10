@props([
  'type' => null,
  'message' => null,
])

@php($class = match ($type) {
  'success' => 'text-white bg-success',
  'caution' => 'text-white bg-warning',
  'warning' => 'text-white bg-error',
  default => 'text-white bg-primary',
})

<div {{ $attributes->merge(['class' => "px-2 py-1 {$class}"]) }}>
  {!! $message ?? $slot !!}
</div>
