<?php

namespace App\Http\Requests\KategoriService;

use Illuminate\Foundation\Http\FormRequest;

class KategoriServiceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kode_service' => 'required|string|max:255|unique:kategori_services,kode_service,' . $this->id . ',id,deleted_at,NULL',
            'nama_service' => 'required|string|max:255',
        ];
    }
}
