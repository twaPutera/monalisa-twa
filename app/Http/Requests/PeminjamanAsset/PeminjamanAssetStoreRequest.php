<?php

namespace App\Http\Requests\PeminjamanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanAssetStoreRequest extends FormRequest
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
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
            'jam_selesai' => 'nullable|date_format:H:i',
            'jam_mulai' => 'nullable|date_format:H:i',
            'alasan_peminjaman' => 'required|string',
            'id_jenis_asset' => 'required|array',
            'id_jenis_asset.*' => 'required|uuid',
            'data_jenis_asset' => 'required|array',
            'data_jenis_asset.*.jumlah' => 'required|numeric|min:1',
            'is_it' => 'nullable|in:1,0',
        ];
    }

    public function messages()
    {
        return [
            'data_jenis_asset.*.jumlah.required' => 'This value must not be empty',
            'data_jenis_asset.*.jumlah.numeric' => 'This value must be numeric',
            'data_jenis_asset.*.jumlah.min' => 'This value must be greater than 0',
        ];
    }
}
