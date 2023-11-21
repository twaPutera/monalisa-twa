<?php

namespace App\Http\Requests\InventarisData;

use Illuminate\Foundation\Http\FormRequest;

class InventarisDataRealisasiRequest extends FormRequest
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
            'id_inventaris' => 'required|array',
            'id_inventaris.*' => 'required|uuid',
            'data_realisasi' => 'required|array',
            'data_realisasi.*.jumlah' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'data_realisasi.required' => 'Data Realisasi Wajib Diisi',
            'data_realisasi.*.jumlah.min' => 'Data Realisasi Harus Berupa Angka Dengan Minimal 0',
            'data_realisasi.*.jumlah.required' => 'Data Realisasi Wajib Diisi',
        ];
    }
}
