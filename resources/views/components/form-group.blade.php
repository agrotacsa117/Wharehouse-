@props(['label', 'name', 'required' => false])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    {{ $slot }}
</div>
