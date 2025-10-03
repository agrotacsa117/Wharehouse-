@props(['type' => 'text', 'name', 'id' => null, 'value' => '', 'required' => false, 'placeholder' => '', 'min' => null, 'step' => null])
<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ old($name, $value) }}"
    {{ $required ? 'required' : '' }}
    placeholder="{{ $placeholder }}"
    @if($min !== null) min="{{ $min }}" @endif
    @if($step !== null) step="{{ $step }}" @endif
    {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
>
@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
