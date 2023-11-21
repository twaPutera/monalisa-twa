<?php

namespace App\Http\Requests\PemutihanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemutihanAssetUpdateRequest extends FormRequest
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
            'id_asset.*' => 'required',
            'id_asset' => 'required|min:1',
            'keterangan_pemutihan_asset.*' => 'required',
            'keterangan_pemutihan_asset' => 'required|min:1',
            'gambar_asset.*' => 'nullable',
            'gambar_asset' => 'nullable|min:1',
            'tanggal' => 'required|date',
            'nama_pemutihan' => 'required|string|max:255',
            'no_berita_acara' => 'nullable|string|max:50',
            'keterangan_pemutihan' => 'required|string|max:255',
            'file_berita_acara' => 'nullable|mimes:pdf,docx,doc|max:4048',
        ];
    }

    public function attributes()
    {
        return [
            'nama_pemutihan' => 'Nama penghapusan asset',
            'keterangan_pemutihan' => 'Keterangan penghapusan asset',
            'keterangan_pemutihan_asset' => 'Keterangan penghapusan asset',
        ];
    }
}
