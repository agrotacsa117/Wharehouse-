@foreach ($items as $item)
    <tr class="text-center">
        <td>{{ $item->email }}</td>
        <td>{{ $item->name }}</td>
        <td>
            @php
             
                $rolLabels = [
                    'admin' => 'Administrador',
                    'tapachula' => 'Tapachula',
                    'bodega_dorado' => 'Bodega Dorado',
                    'usuario' => 'Usuario',
                ];
                $color = $rolColors[$item->rol] ?? 'secondary';
                $label = $rolLabels[$item->rol] ?? ucfirst($item->rol);
            @endphp
            <span class="badge bg-{{ $color }} px-3 py-2 fs-6">{{ $label }}</span>
        </td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input user-active-toggle" 
                       type="checkbox" 
                       data-user-id="{{ $item->id }}" 
                       {{ $item->activo ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <a href="#" onclick="agregar_id_usuario({{ $item->id }})" 
                   class="btn btn-secondary" 
                   data-bs-toggle="modal" 
                   data-bs-target="#cambiar_password">
                    <i class="fa-solid fa-user-lock"></i>
                </a>
                <a href="{{ route('usuarios.edit', $item->id) }}" 
                   class="btn btn-warning">
                    <i class="fa-solid fa-user-pen"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach