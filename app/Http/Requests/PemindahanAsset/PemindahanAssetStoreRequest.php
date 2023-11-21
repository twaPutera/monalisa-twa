<?php

namespace App\Http\Requests\PemindahanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemindahanAssetStoreRequest extends FormRequest
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
            'no_bast' => 'required|unique:pemindahan_assets,no_surat',
            'penerima_asset' => 'required|uuid',
            'penyerah_asset' => 'nullable|uuid',
            'tanggal_pemindahan' => 'required|date',
            'jabatan_penerima' => 'required|string',
            'jabatan_penyerah' => 'nullable|string',
            'unit_kerja_penerima' => 'required|string',
            'unit_kerja_penyerah' => 'nullable|string',
            'asset_id' => 'required|uuid|exists:asset_data,id',
        ];
    }
}
