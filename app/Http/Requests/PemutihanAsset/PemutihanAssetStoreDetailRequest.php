<?php

namespace App\Http\Requests\PemutihanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemutihanAssetStoreDetailRequest extends FormRequest
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
            'status_pemutihan' => 'required|in:Draft,Publish',
            'id_asset.*' => 'required',
            'id_asset' => 'required|min:1',
            'keterangan_pemutihan_asset.*' => 'required',
            'keterangan_pemutihan_asset' => 'required|min:1',
            'gambar_asset.*' => 'required',
            'gambar_asset' => 'required|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'status_pemutihan' => 'Status penghapusan asset',
            'keterangan_pemutihan_asset' => 'Keterangan penghapusan asset',
        ];
    }
}
