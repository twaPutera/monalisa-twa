<?php

namespace App\Http\Requests\InventarisData;

use Illuminate\Foundation\Http\FormRequest;

class InventarisDataUpdateStokRequest extends FormRequest
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
            'jumlah_keluar' => 'required|integer|min:0',
            'no_memo' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'id_surat_memo_andin' => 'required|uuid',
        ];
    }
}
