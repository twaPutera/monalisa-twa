<?php

namespace App\Http\Requests\Pengaduan;

use Illuminate\Foundation\Http\FormRequest;

class PengaduanUpdateRequest extends FormRequest
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
            'id_lokasi' => 'required|exists:lokasis,id',
            'id_asset' => 'nullable|exists:asset_data,id',
            'tanggal_pengaduan' => 'required|date',
            'prioritas' => 'required|in:10,5,1',
            'alasan_pengaduan' => 'required|max:255|string',
            // 'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ];
    }
}
