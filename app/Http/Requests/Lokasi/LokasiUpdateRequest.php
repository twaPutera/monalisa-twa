<?php

namespace App\Http\Requests\Lokasi;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class LokasiUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if ($request->id_parent_lokasi == 'root') {
            $req_valid = 'nullable|max:255';
        } else {
            $req_valid = 'nullable|uuid|max:255';
        }
        return [
            'id_parent_lokasi' => $req_valid,
            'kode_lokasi' => 'required|max:255|unique:lokasis,kode_lokasi,' . $this->id . ',id,deleted_at,NULL',
            'nama_lokasi' => 'required|max:255',
            'keterangan' => 'nullable|string',
        ];
    }
}
