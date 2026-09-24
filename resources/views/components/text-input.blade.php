@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md border-slate-300 text-slate-900 focus:border-brand-600 focus:ring-brand-600']) !!}>
