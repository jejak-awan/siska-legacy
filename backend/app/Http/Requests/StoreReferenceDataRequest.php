<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReferenceDataRequest extends FormRequest
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
            'category_id' => [
                'required',
                'integer',
                'exists:reference_categories,id'
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('reference_data', 'code')->where('category_id', $this->category_id)
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'data' => [
                'nullable',
                'array'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.integer' => 'Kategori tidak valid',
            'category_id.exists' => 'Kategori tidak ditemukan',
            
            'code.max' => 'Kode maksimal 50 karakter',
            'code.unique' => 'Kode sudah digunakan dalam kategori ini',
            
            'name.required' => 'Nama data wajib diisi',
            'name.min' => 'Nama data minimal 2 karakter',
            'name.max' => 'Nama data maksimal 100 karakter',
            
            'description.max' => 'Deskripsi maksimal 500 karakter',
            
            'data.array' => 'Data harus berupa array',
            
            'is_active.boolean' => 'Status aktif harus boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'code' => 'kode',
            'name' => 'nama data',
            'description' => 'deskripsi',
            'data' => 'data',
            'is_active' => 'status aktif',
        ];
    }
}