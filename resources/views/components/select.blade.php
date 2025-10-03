@props(['name', 'id' => null, 'options' => [], 'selected' => null, 'required' => false])
<select name="{{ $name }}" id="{{ $id ?? $name }}" {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    <option value="">Seleccione una opción</option>
    @foreach($options as $key => $option)
        <option value="{{ $key }}" {{ (string) old($name, $selected) === (string) $key ? 'selected' : '' }}>{{ $option }}</option>
    @endforeach
</select>
@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
