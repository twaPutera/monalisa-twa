<?php

namespace App\Http\Requests\InventarisData;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestInventoriUpdateRequest extends FormRequest
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
            'tanggal_pengambilan' => 'required|date',
            'alasan_permintaan' => 'required|string',
            'no_memo' => 'required|string',
            'unit_kerja' => 'required|string',
            'jabatan' => 'required|string',
            'id_bahan_habis_pakai' => 'required|array',
            'id_bahan_habis_pakai.*' => 'required|uuid',
            'data_bahan_habis_pakai' => 'required|array',
            'data_bahan_habis_pakai.*.jumlah' => 'required|numeric|min:1',
        ];
    }
}
