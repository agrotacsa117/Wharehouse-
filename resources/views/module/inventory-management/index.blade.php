@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
    <main id="main" class="main bg-light py-4">
        <div class="pagetitle mb-4">
            <h1 class="fw-bold text-primary" style="letter-spacing:1px;">
                <i class="fa-solid fa-boxes-stacked me-2"></i>{{ $titulo }}
            </h1>
            <nav>
                <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Gestión de Inventario</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Bodega</label>
                            <select class="form-select form-select-sm" id="filterWarehouse">
                                <option value="">Todos las bodegas</option>
                                @foreach ($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Producto</label>
                            <input type="text" class="form-control form-control-sm" id="filterProduct"
                                placeholder="Buscar producto...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Estado</label>
                            <select class="form-select form-select-sm" id="filterState">
                                <option value="">Todos</option>
                                <option value="3">Crítico (< 90 días)</option>
                                <option value="2">Atención (90-120 días)</option>
                                <option value="1">OK (> 120 días)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary btn-sm w-100" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="inventoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th style="min-width: 120px;">Código</th>
                                    <th style="min-width: 120px;">Producto</th>
                                    <th style="min-width: 120px;">Bodega</th>
                                    <th style="min-width: 80px;">Rack</th>
                                    <th class="text-center" style="width: 60px;">Nivel</th>
                                    <th style="min-width: 100px;">Lote</th>
                                    <th class="text-center" style="width: 90px;">Cantidad</th>
                                    <th class="text-center" style="min-width: 100px;">Fecha Caducidad</th>
                                    <th class="text-center" style="width: 85px;">Días</th>
                                    <th class="text-center" style="width: 80px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modal de Edición --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="editModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Inventario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Código</label>
                                <input type="text" class="form-control" id="editProductId" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Producto</label>
                                <input type="text" class="form-control" id="editProductName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Almacén</label>
                                <input type="text" class="form-control" id="editWarehouse" readonly>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Rack <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editRack" name="rack" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nivel <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editLevel" name="level" min="1"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Lote <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editLot" name="lot_number" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editQuantity" name="quantity"
                                    min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha Caducidad <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editExpiration" name="expiration_date"
                                    required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Razón del ajuste <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="editReason" name="reason" rows="2" required
                                placeholder="Ej: Corrección de cantidad, Cambio de ubicación, etc."></textarea>
                        </div>

                        <input type="hidden" id="originalExpiration" value="">
                        <input type="hidden" id="originalQuantity" value="">
                        <input type="hidden" id="originalRack" value="">
                        <input type="hidden" id="originalLevel" value="">
                        <input type="hidden" id="originalLot" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de Transferencia --}}
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="transferModalLabel">
                        <i class="bi bi-arrow-left-right me-2"></i>Transferir Producto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form id="transferForm">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Código:</strong> <span id="transferProductCode"></span>
                            <br><strong>Producto:</strong> <span id="transferProductName"></span>
                            <br><strong>Stock disponible:</strong> <span id="transferAvailableStock"></span>
                            <br><strong>Almacén actual:</strong> <span id="transferFromWarehouse"></span>
                        </div>

                        <input type="hidden" id="transferInventoryId" name="inventory_id">
                        <input type="hidden" id="transferFromWarehouseId" name="from_warehouse_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Almacén Destino <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="transferToWarehouse" name="to_warehouse_id" required>
                                    <option value="">Seleccione almacén destino</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->warehouses_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cantidad a Transferir <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="transferQuantity" name="quantity"
                                    min="1" required>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Rack <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="transferRack" name="rack" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nivel <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="transferLevel" name="level"
                                    min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Lote <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="transferLot" name="lot_number" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Razón de la transferencia <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="transferReason" name="reason" rows="2" required
                                placeholder="Ej: Reubicación de inventario, reorganización, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Transferir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let inventoryData = @json($inventory);
            let filteredData = [...inventoryData];

            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('es-MX', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function formatDateForInput(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function formatNumber(num) {
                return new Intl.NumberFormat('es-MX').format(num);
            }

            function getDaysRemaining(expirationDate) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const expDate = new Date(expirationDate);
                const diffTime = expDate - today;
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }

            function getBadgeClass(days) {
                if (days < 0) return 'bg-danger';
                if (days <= 30) return 'bg-danger';
                if (days <= 60) return 'bg-warning text-dark';
                return 'bg-success';
            }

            function getStateFromDays(days) {
                if (days < 90) return 3;
                if (days <= 120) return 2;
                return 1;
            }

            function renderTable(data) {
                const tbody = document.getElementById('inventoryTableBody');

                if (!data || data.length === 0) {
                    tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="bi bi-inbox-fill me-2"></i>No hay registros de inventario
                    </td>
                </tr>
            `;
                    return;
                }

                tbody.innerHTML = data.map((item, index) => {
                    const days = getDaysRemaining(item.expirationDate);
                    const state = getStateFromDays(days);

                    return `
                <tr data-warehouse-id="${item.warehouseId}" data-product="${item.productId}" data-state="${state}">
                    <td class="text-center text-muted">${index + 1}</td>
                    <td class="fw-medium">${item.productId || 'N/A'}</td>
                    <td class="fw-medium">${item.productName || 'N/A'}</td>
                    <td><span class="badge bg-secondary">${item.warehouseName || 'N/A'}</span></td>
                    <td><span class="badge bg-dark">${item.rack || '-'}</span></td>
                    <td class="text-center"><strong>${item.level || '-'}</strong></td>
                    <td><small>${item.lotNumber || '-'}</small></td>
                    <td class="text-center fw-bold">${formatNumber(item.quantity)}</td>
                    <td class="text-center">${formatDate(item.expirationDate)}</td>
                    <td class="text-center">
                        <span class="badge ${getBadgeClass(days)}">
                            ${days < 0 ? 'Vencido' : days + ' días'}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${item.id})" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="openTransferModal(${item.id})" title="Transferir">
                            <i class="bi bi-arrow-left-right"></i>
                        </button>
                    </td>
                </tr>
            `;
                }).join('');
            }

            function filterTable() {
                const warehouseFilter = document.getElementById('filterWarehouse').value;
                const productFilter = document.getElementById('filterProduct').value.toLowerCase();
                const stateFilter = document.getElementById('filterState').value;

                filteredData = inventoryData.filter(item => {
                    const days = getDaysRemaining(item.expiration_date);
                    const itemState = getStateFromDays(days);

                    if (warehouseFilter && item.warehouseId != warehouseFilter) return false;
                    if (
                        productFilter 
                        && !(item.productId || '').toLowerCase().includes(productFilter)
                        && !(item.productName || '').toLowerCase().includes(productFilter)) return false;
                    if (stateFilter && itemState != parseInt(stateFilter)) return false;

                    return true;
                });

                renderTable(filteredData);
            }

            function resetFilters() {
                document.getElementById('filterWarehouse').value = '';
                document.getElementById('filterProduct').value = '';
                document.getElementById('filterState').value = '';
                filteredData = [...inventoryData];
                renderTable(filteredData);
            }

            function openEditModal(id) {
                const item = inventoryData.find(i => i.id === id);
                if (!item) return;

                document.getElementById('editId').value = item.id; //editProductName
                document.getElementById('editProductId').value = item.productId || 'N/A';
                document.getElementById('editProductName').value = item.productName || 'N/A';
                document.getElementById('editWarehouse').value = item.warehouseName || item.warehouse?.warehouses_name ||
                    'N/A';
                document.getElementById('editRack').value = item.rack || '';
                document.getElementById('editLevel').value = item.level || 1;
                document.getElementById('editLot').value = item.lotNumber || '';
                document.getElementById('editQuantity').value = item.quantity;
                document.getElementById('editExpiration').value = formatDateForInput(item.expirationDate);
                document.getElementById('editReason').value = '';

                document.getElementById('originalExpiration').value = item.expirationDate;
                document.getElementById('originalQuantity').value = item.quantity;
                document.getElementById('originalRack').value = item.rack || '';
                document.getElementById('originalLevel').value = item.level || 1;
                document.getElementById('originalLot').value = item.lotNumber || '';

                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            }

            function openTransferModal(id) {

                const item = inventoryData.find(i => i.id === id);
                if (!item) return;

                document.getElementById('transferInventoryId').value = item.id;
                document.getElementById('transferProductCode').textContent = item.productId || 'N/A';
                document.getElementById('transferProductName').textContent = item.productName || 'N/A';
                document.getElementById('transferAvailableStock').textContent = formatNumber(item.quantity);
                document.getElementById('transferFromWarehouse').textContent = item.warehouseName || item.warehouse
                    ?.warehouses_name || 'N/A';
                document.getElementById('transferFromWarehouseId').value = item.warehouseId;
                document.getElementById('transferQuantity').max = item.quantity;
                document.getElementById('transferQuantity').value = item.quantity;
                document.getElementById('transferRack').value = item.rack || '';
                document.getElementById('transferLevel').value = item.level || 1;
                document.getElementById('transferLot').value = item.lotNumber || '';
                document.getElementById('transferReason').value = '';

                const toWarehouseSelect = document.getElementById('transferToWarehouse');
                for (let option of toWarehouseSelect.options) {
                    if (option.value == item.warehouse_id) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                }
                toWarehouseSelect.value = '';

                const modal = new bootstrap.Modal(document.getElementById('transferModal'));
                modal.show();
            }

            document.getElementById('filterWarehouse').addEventListener('change', filterTable);
            document.getElementById('filterProduct').addEventListener('keyup', filterTable);
            document.getElementById('filterState').addEventListener('change', filterTable);

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                fetch('{{ route('inventory.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        alert('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud.' + error,
                            confirmButtonText: 'Aceptar'
                        });
                    });
            });

            document.getElementById('transferForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                
                const quantity = parseInt(data.quantity);
                const maxQty = parseInt(document.getElementById('transferQuantity').max);
                if (quantity > maxQty) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La cantidad no puede ser mayor al stock disponible (' + maxQty + ')',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                fetch('{{ route('inventory.transfer') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Transferencia exitosa!',
                                text: JSON.stringify(result.message),
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la transferencia.',
                            confirmButtonText: 'Aceptar'
                        });
                    });
            });

            renderTable(inventoryData);
        </script>
    @endpush
@endsection
