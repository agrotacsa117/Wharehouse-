<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Almacenes - TACSA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --tacsa-red: #D32F2F;
            --tacsa-red-dark: #B71C1C;
            --tacsa-red-light: #EF5350;
            --text-primary: #212121;
            --text-secondary: #757575;
            --border-color: #E0E0E0;
            --bg-light: #FAFAFA;
            --white: #FFFFFF;
            --success: #4CAF50;
            --error: #F44336;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: var(--white);
            padding: 30px;
            border-radius: 12px 12px 0 0;
            box-shadow: var(--shadow);
            text-align: center;
            border-bottom: 4px solid var(--tacsa-red);
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header h1 {
            color: var(--tacsa-red);
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .form-container {
            background: var(--white);
            padding: 40px;
            border-radius: 0 0 12px 12px;
            box-shadow: var(--shadow-lg);
        }

        .form-section {
            margin-bottom: 35px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--bg-light);
            animation: slideUp 0.6s ease-out;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            color: var(--tacsa-red);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--tacsa-red);
            border-radius: 2px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .required {
            color: var(--tacsa-red);
            font-weight: bold;
        }

        .optional {
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: normal;
        }

        input, select, textarea {
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--white);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--tacsa-red);
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
            transform: translateY(-1px);
        }

        input:hover, select:hover, textarea:hover {
            border-color: var(--tacsa-red-light);
        }

        input.error, select.error, textarea.error {
            border-color: var(--error);
            background: rgba(244, 67, 54, 0.05);
        }

        input.success, select.success, textarea.success {
            border-color: var(--success);
        }

        .error-message {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
            display: none;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-message.show {
            display: block;
        }

        .conditional-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .conditional-section.show {
            display: block;
        }

        .info-box {
            background: rgba(211, 47, 47, 0.05);
            border-left: 4px solid var(--tacsa-red);
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid var(--bg-light);
        }

        button {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--tacsa-red);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .btn-primary:hover {
            background: var(--tacsa-red-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(211, 47, 47, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-light);
            border-color: var(--text-secondary);
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .input-group .form-group {
            flex: 1;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .form-container {
                padding: 25px 20px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 22px;
            }

            .logo {
                width: 120px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column-reverse;
            }

            button {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 20px;
            }

            .form-container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-3pBfz8jhasVBCCRAl1NOQFuUdXmn8i.png" alt="TACSA Logo" class="logo">
            <h1>Registro de Almacenes</h1>
            <p>Complete el formulario con la información del almacén</p>
        </div>

        <div class="form-container">
            <form id="warehouseForm" novalidate>
                <!-- Información General -->
                <div class="form-section">
                    <h2 class="section-title">Información General</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="clave">
                                Clave del Almacén <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="clave" 
                                name="clave" 
                                placeholder="Ej: ALM-001"
                                required
                                pattern="[A-Z0-9-]+"
                                maxlength="20"
                            >
                            <span class="error-message">La clave es obligatoria (solo mayúsculas, números y guiones)</span>
                        </div>

                        <div class="form-group">
                            <label for="nombre">
                                Nombre del Almacén <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                placeholder="Ej: Almacén Central"
                                required
                                minlength="3"
                                maxlength="100"
                            >
                            <span class="error-message">El nombre es obligatorio (mínimo 3 caracteres)</span>
                        </div>
                    </div>
                </div>

                <!-- Contacto y Responsable -->
                <div class="form-section">
                    <h2 class="section-title">Contacto y Responsable</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="responsable">
                                Responsable <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="responsable" 
                                name="responsable" 
                                placeholder="Nombre completo del responsable"
                                required
                                minlength="3"
                                maxlength="100"
                            >
                            <span class="error-message">El nombre del responsable es obligatorio</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">
                                Teléfono <span class="required">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="telefono" 
                                name="telefono" 
                                placeholder="10 dígitos"
                                required
                                pattern="[0-9]{10}"
                                maxlength="10"
                            >
                            <span class="error-message">Ingrese un teléfono válido de 10 dígitos</span>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                Email <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="correo@ejemplo.com"
                                required
                            >
                            <span class="error-message">Ingrese un email válido</span>
                        </div>
                    </div>
                </div>

                <!-- Configuración del Almacén -->
                <div class="form-section">
                    <h2 class="section-title">Configuración del Almacén</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo_almacen">
                                Tipo de Almacén <span class="required">*</span>
                            </label>
                             <input 
                                type="text" 
                                id="tipo_almacen" 
                                name="tipo_almacen" 
                                placeholder="Tipo de almacén"
                                required
                                minlength="3"
                                maxlength="100"
                            >
                        </div>
                </div>

                <!-- Condiciones de Almacenamiento (Condicional) -->
                <div class="form-section conditional-section" id="condiciones-section">
                    <h2 class="section-title">Condiciones de Almacenamiento</h2>
                    <div class="info-box">
                        Los campos de temperatura son obligatorios para almacenes refrigerados o congelados. Los campos de humedad son opcionales.
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="temperatura_min">
                                Temperatura Mínima (°C) <span class="required" id="temp-required">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="temperatura_min" 
                                name="temperatura_min" 
                                placeholder="Ej: -18"
                                step="0.1"
                            >
                            <span class="error-message">Ingrese una temperatura mínima válida</span>
                        </div>

                        <div class="form-group">
                            <label for="temperatura_max">
                                Temperatura Máxima (°C) <span class="required" id="temp-max-required">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="temperatura_max" 
                                name="temperatura_max" 
                                placeholder="Ej: -15"
                                step="0.1"
                            >
                            <span class="error-message">Ingrese una temperatura máxima válida</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="humedad_min">
                                Humedad Mínima (%) <span class="optional">(Opcional)</span>
                            </label>
                            <input 
                                type="number" 
                                id="humedad_min" 
                                name="humedad_min" 
                                placeholder="Ej: 40"
                                min="0"
                                max="100"
                                step="0.1"
                            >
                            <span class="error-message">Ingrese un valor entre 0 y 100</span>
                        </div>

                        <div class="form-group">
                            <label for="humedad_max">
                                Humedad Máxima (%) <span class="optional">(Opcional)</span>
                            </label>
                            <input 
                                type="number" 
                                id="humedad_max" 
                                name="humedad_max" 
                                placeholder="Ej: 60"
                                min="0"
                                max="100"
                                step="0.1"
                            >
                            <span class="error-message">Ingrese un valor entre 0 y 100</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="button-group">
                    <button type="button" class="btn-secondary" id="cancelBtn">
                        ✕ Cancelar
                    </button>
                    <button type="submit" class="btn-primary">
                        ✓ Guardar Almacén
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Referencias a elementos del DOM
        const form = document.getElementById('warehouseForm');
        const tipoAlmacen = document.getElementById('tipo_almacen');
        const condicionesSection = document.getElementById('condiciones-section');
        const temperaturaMin = document.getElementById('temperatura_min');
        const temperaturaMax = document.getElementById('temperatura_max');
        const humedadMin = document.getElementById('humedad_min');
        const humedadMax = document.getElementById('humedad_max');
        const claveInput = document.getElementById('clave');
        const cancelBtn = document.getElementById('cancelBtn');

        // Convertir clave a mayúsculas automáticamente
        claveInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Mostrar/ocultar sección de condiciones según tipo de almacén
        tipoAlmacen.addEventListener('change', function() {
            const tipo = this.value;
            
            if (tipo === 'refrigerado' || tipo === 'congelado') {
                condicionesSection.classList.add('show');
                temperaturaMin.required = true;
                temperaturaMax.required = true;
            } else {
                condicionesSection.classList.remove('show');
                temperaturaMin.required = false;
                temperaturaMax.required = false;
                // Limpiar valores cuando se oculta
                temperaturaMin.value = '';
                temperaturaMax.value = '';
                humedadMin.value = '';
                humedadMax.value = '';
                // Limpiar errores
                clearFieldError(temperaturaMin);
                clearFieldError(temperaturaMax);
                clearFieldError(humedadMin);
                clearFieldError(humedadMax);
            }
        });

        // Validación en tiempo real
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });

        // Función de validación de campo individual
        function validateField(field) {
            const errorMessage = field.nextElementSibling;
            let isValid = true;

            // Limpiar estado previo
            field.classList.remove('error', 'success');
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.classList.remove('show');
            }

            // Validar campo requerido
            if (field.required && !field.value.trim()) {
                isValid = false;
                showError(field, errorMessage);
                return false;
            }

            // Validaciones específicas por tipo
            if (field.value.trim()) {
                switch (field.type) {
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            isValid = false;
                            showError(field, errorMessage);
                        }
                        break;

                    case 'tel':
                        const telRegex = /^[0-9]{10}$/;
                        if (!telRegex.test(field.value)) {
                            isValid = false;
                            showError(field, errorMessage);
                        }
                        break;

                    case 'number':
                        const numValue = parseFloat(field.value);
                        if (isNaN(numValue)) {
                            isValid = false;
                            showError(field, errorMessage);
                        } else if (field.min && numValue < parseFloat(field.min)) {
                            isValid = false;
                            showError(field, errorMessage);
                        } else if (field.max && numValue > parseFloat(field.max)) {
                            isValid = false;
                            showError(field, errorMessage);
                        }
                        break;

                    case 'text':
                        if (field.pattern) {
                            const regex = new RegExp(field.pattern);
                            if (!regex.test(field.value)) {
                                isValid = false;
                                showError(field, errorMessage);
                            }
                        }
                        if (field.minLength && field.value.length < field.minLength) {
                            isValid = false;
                            showError(field, errorMessage);
                        }
                        break;
                }
            }

            // Validación especial para temperaturas
            if (field.id === 'temperatura_max' && temperaturaMin.value && field.value) {
                if (parseFloat(field.value) <= parseFloat(temperaturaMin.value)) {
                    isValid = false;
                    if (errorMessage) {
                        errorMessage.textContent = 'La temperatura máxima debe ser mayor que la mínima';
                        showError(field, errorMessage);
                    }
                }
            }

            // Validación especial para humedad
            if (field.id === 'humedad_max' && humedadMin.value && field.value) {
                if (parseFloat(field.value) <= parseFloat(humedadMin.value)) {
                    isValid = false;
                    if (errorMessage) {
                        errorMessage.textContent = 'La humedad máxima debe ser mayor que la mínima';
                        showError(field, errorMessage);
                    }
                }
            }

            if (isValid && field.value.trim()) {
                field.classList.add('success');
            }

            return isValid;
        }

        function showError(field, errorMessage) {
            field.classList.add('error');
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.classList.add('show');
            }
        }

        function clearFieldError(field) {
            field.classList.remove('error', 'success');
            const errorMessage = field.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.classList.remove('show');
            }
        }

        // Manejo del envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isFormValid = true;

            // Validar todos los campos
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                alert('Por favor, corrija los errores en el formulario antes de continuar.');
                // Hacer scroll al primer error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }

            // Recopilar datos del formulario
            const formData = {
                clave: document.getElementById('clave').value.trim(),
                nombre: document.getElementById('nombre').value.trim(),
                responsable: document.getElementById('responsable').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                email: document.getElementById('email').value.trim(),
                tipo_almacen: document.getElementById('tipo_almacen').value,
                capacidad_maxima: parseFloat(document.getElementById('capacidad_maxima').value)
            };

            // Agregar datos condicionales si aplica
            if (tipoAlmacen.value === 'refrigerado' || tipoAlmacen.value === 'congelado') {
                formData.temperatura_min = parseFloat(temperaturaMin.value);
                formData.temperatura_max = parseFloat(temperaturaMax.value);
                
                if (humedadMin.value) {
                    formData.humedad_min = parseFloat(humedadMin.value);
                }
                if (humedadMax.value) {
                    formData.humedad_max = parseFloat(humedadMax.value);
                }
            }

            // Aquí iría la lógica para enviar los datos al backend
            console.log('Datos del formulario:', formData);
            
            // Simulación de envío exitoso
            alert('¡Almacén registrado exitosamente!\n\nClave: ' + formData.clave + '\nNombre: ' + formData.nombre);
            
            // Opcional: Limpiar formulario después del envío
            // form.reset();
            // condicionesSection.classList.remove('show');
        });

        // Manejo del botón cancelar
        cancelBtn.addEventListener('click', function() {
            if (confirm('¿Está seguro de que desea cancelar? Se perderán todos los datos ingresados.')) {
                form.reset();
                condicionesSection.classList.remove('show');
                
                // Limpiar todos los estados de error/éxito
                inputs.forEach(input => {
                    clearFieldError(input);
                });
            }
        });
    </script>
</body>
</html>
