<?php

namespace App\Http\Requests\PemutihanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemutihanAssetStoreRequest extends FormRequest
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
            'id_checkbox.*' => 'required',
            'id_checkbox' => 'required|min:1',
            'tanggal' => 'required|date',
            'nama_pemutihan' => 'required|string|max:255',
            'no_berita_acara' => 'nullable|string|max:50',
            'keterangan_pemutihan' => 'required|string|max:255',
            'file_berita_acara' => 'required|mimes:pdf,docx,doc|max:4048',
        ];
    }

    public function attributes()
    {
        return [
            'nama_pemutihan' => 'Nama penghapusan asset',
            'keterangan_pemutihan' => 'Keterangan penghapusan asset',
        ];
    }
}
