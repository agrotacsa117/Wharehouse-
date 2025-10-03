@forelse ($items as $item)
<tr class="@if($loop->even) bg-light @endif align-middle">
  <td class="text-start">{{ $item->nombre_proveedores}}</td>
  <td class="text-start">{{ $item->clave_categorias }}</td>
  <td class="text-start">{{ $item->nombre_categorias }}</td>
  <td class="text-center fw-bold text-success">
    {{ $item->cantidad }}
  </td>
  <td class="text-center text-primary">$ {{ number_format($item->precio, 2) }}</td>
  <td class="text-center text-primary">
    $ <span class="precio-total" id="precio-total-{{ $item->id }}">{{ number_format($item->precio_total, 2) }}</span>
  </td>
  <td class="text-start">{{ $item->colocado }}</td>
  <td class="text-start">{{ ucfirst(str_replace('_', ' ', $item->rol)) }}</td>
  <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}</td>
  <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha_caducidad)->format('d/m/Y') }}</td>
  <td class="text-center">
      <?php
          $hoy = \Carbon\Carbon::today();
          $fechaCaducidad = \Carbon\Carbon::parse($item->fecha_caducidad);
          $dias = $hoy->diffInDays($fechaCaducidad, false);
          $mensajeCaducidad = '';
          $colorBadge = '';
          if ($dias < 0) {
              $colorBadge = 'secondary';
              $mensajeCaducidad = 'Vencido hace ' . abs($dias) . ' días';
          } elseif ($dias == 0) {
              $colorBadge = 'danger';
              $mensajeCaducidad = 'Vence hoy';
          } elseif ($dias <= 7) {
              $colorBadge = 'danger';
              $mensajeCaducidad = 'Vence en ' . $dias . ($dias == 1 ? ' día' : ' días');
          } elseif ($dias <= 30) {
              $colorBadge = 'warning';
              $mensajeCaducidad = 'Vence en ' . $dias . ' días';
          } else {
              $colorBadge = 'success';
              $mensajeCaducidad = 'Vence en ' . $dias . ' días';
          }
      ?>
      <span class="badge bg-{{ $colorBadge }} d-block py-2">
          {{ $mensajeCaducidad }}
      </span>
      <small class="text-muted mt-1 d-block">Ingresado por: {{ $item->nombre_usuario }}</small>
  </td>
  @if(auth()->user()->rol === 'admin')
    <td class="text-center">
      <div class="form-check form-switch d-inline-block">
        <input class="form-check-input product-active-toggle" type="checkbox" role="switch" 
               id="toggle-{{ $item->id }}" data-product-id="{{ $item->id }}" {{ $item->activo ? 'checked' : '' }}>
        <label class="form-check-label visually-hidden" for="toggle-{{ $item->id }}">Activo</label>
      </div>
    </td>
  @endif
  <td class="text-center">
    <div class="d-flex justify-content-center gap-2">
      @if(auth()->user()->rol === 'admin')
      <a href="{{route('productos.edit', $item->id)}}" class="btn btn-sm btn-outline-warning" title="Editar Producto">
          <i class="fa-solid fa-pencil-alt"></i>
      </a>
      <form action="{{ route('productos.destroy', $item->id) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger" 
                  onclick="return confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción es irreversible.')"
                  title="Eliminar Producto">
              <i class="fa-solid fa-trash-can"></i>
          </button>
      </form>
      @else
          <span class="badge bg-secondary text-white">Sin permisos</span>
      @endif
    </div>
  </td>
</tr>
@empty
<tr>
  <td colspan="@if(auth()->user()->rol === 'admin') 13 @else 12 @endif" class="text-center text-muted py-4">
    <i class="fa-solid fa-box-open me-2"></i> No se encontraron productos registrados.
  </td>
</tr>
@endforelse
