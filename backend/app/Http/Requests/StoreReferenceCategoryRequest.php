<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReferenceCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'unique:reference_categories,name'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'fields_config' => [
                'required',
                'array',
                'min:1'
            ],
            'fields_config.*.name' => [
                'required',
                'string',
                'min:1',
                'max:50'
            ],
            'fields_config.*.label' => [
                'required',
                'string',
                'min:1',
                'max:100'
            ],
            'fields_config.*.type' => [
                'required',
                'string',
                'in:text,number,email,date,select,textarea'
            ],
            'fields_config.*.required' => [
                'boolean'
            ],
            'fields_config.*.options' => [
                'nullable',
                'array'
            ],
            'is_active' => [
                'boolean'
            ],
            'order' => [
                'nullable',
                'integer',
                'min:0'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.min' => 'Nama kategori minimal 2 karakter',
            'name.max' => 'Nama kategori maksimal 100 karakter',
            'name.unique' => 'Nama kategori sudah digunakan',
            
            'description.max' => 'Deskripsi maksimal 500 karakter',
            
            'fields_config.required' => 'Konfigurasi field wajib diisi',
            'fields_config.array' => 'Konfigurasi field harus berupa array',
            'fields_config.min' => 'Minimal 1 field harus dikonfigurasi',
            
            'fields_config.*.name.required' => 'Nama field wajib diisi',
            'fields_config.*.name.min' => 'Nama field minimal 1 karakter',
            'fields_config.*.name.max' => 'Nama field maksimal 50 karakter',
            
            'fields_config.*.label.required' => 'Label field wajib diisi',
            'fields_config.*.label.min' => 'Label field minimal 1 karakter',
            'fields_config.*.label.max' => 'Label field maksimal 100 karakter',
            
            'fields_config.*.type.required' => 'Tipe field wajib dipilih',
            'fields_config.*.type.in' => 'Tipe field tidak valid',
            
            'fields_config.*.required.boolean' => 'Status required harus boolean',
            
            'fields_config.*.options.array' => 'Opsi field harus berupa array',
            
            'is_active.boolean' => 'Status aktif harus boolean',
            
            'order.integer' => 'Urutan harus berupa angka',
            'order.min' => 'Urutan minimal 0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'description' => 'deskripsi',
            'fields_config' => 'konfigurasi field',
            'fields_config.*.name' => 'nama field',
            'fields_config.*.label' => 'label field',
            'fields_config.*.type' => 'tipe field',
            'fields_config.*.required' => 'status required',
            'fields_config.*.options' => 'opsi field',
            'is_active' => 'status aktif',
            'order' => 'urutan',
        ];
    }
}