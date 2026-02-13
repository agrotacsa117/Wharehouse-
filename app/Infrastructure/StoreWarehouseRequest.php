<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouses_name' => [
                'required',
                'string',
                'max:60',
                'regex:/^[A-Za-zÁÉÍÓÚÑáéíóúñ0-9\s._\-&,()\/#]+$/u'
            ],

            'warehouses_key' => [
                'required',
                'string',
                'max:6',
                'regex:/^[A-Z0-9]{6}$/'
            ],

            'warehouse_manager' => [
                'required',
                'string',
                'max:35',
                'regex: /^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/u'
            ],

            'phone_number' => [
                'required',
                'string',
                'max:10',
                'regex: /^[0-9]{10}$/'
            ],

            'email' => [
                'required',
                'string',
                'min:10',
                'regex: ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
            ],
            'warehouse_type_id' => 'required|integer|min:1',
            'location_id' => 'required|integer|min:1'
        ];
    }


    public function messages(): array
    {
        return [
        'warehouses_name.required'
        => 'El nombre del almacén es obligatorio.',
        'warehouses_name.max'
        => 'El nombre del almacén no debe exceder 60 caracteres.',
        'warehouses_name.regex'
        => 'El nombre del almacén contiene caracteres no permitidos.',

        'warehouses_key.required'
         => 'La clave del almacén es obligatoria.',
        'warehouses_key.max'
        => 'La clave del almacén debe tener 6 caracteres.',
        'warehouses_key.regex'
         => 'La clave del almacén solo puede contener letras mayúsculas y números.',

        'warehouse_manager.required'
         => 'El nombre del encargado es obligatorio.',
        'warehouse_manager.regex'
         => 'El nombre del encargado solo puede contener letras y espacios.',

        'phone_number.required'
        => 'El número telefónico es obligatorio.',
        'phone_number.regex'
        => 'El número telefónico debe contener exactamente 10 dígitos.',
        'email.required'
        => 'El correo electrónico es obligatorio.',
        'email.regex'
        => 'El correo electrónico no tiene un formato válido.',

        'warehouse_type_id.required'
        => 'El tipo de almacén es obligatorio.',
        'warehouse_type_id.min'
         => 'El tipo de almacén no es válido.',
        'location_id.required' =>
        'La ubicación es obligatoria.',
        'location_id.min'
        => 'La ubicación no es válida.',
    ];
    }
}
