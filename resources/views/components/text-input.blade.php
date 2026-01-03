@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-orange-red focus:ring-orange-red rounded-md shadow-sm']) !!}>
