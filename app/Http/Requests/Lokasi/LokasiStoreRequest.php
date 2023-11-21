<?php

namespace App\Http\Requests\Lokasi;

use Illuminate\Foundation\Http\FormRequest;

class LokasiStoreRequest extends FormRequest
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
            'id_parent_lokasi' => 'nullable|string|max:255',
            'kode_lokasi' => 'required|max:255|unique:lokasis,kode_lokasi,NULL,id,deleted_at,NULL',
            'nama_lokasi' => 'required|max:255',
            'keterangan' => 'nullable|string',
        ];
    }
}
