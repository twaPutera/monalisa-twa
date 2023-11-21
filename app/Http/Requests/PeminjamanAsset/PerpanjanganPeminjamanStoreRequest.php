<?php

namespace App\Http\Requests\PeminjamanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PerpanjanganPeminjamanStoreRequest extends FormRequest
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
            'tanggal_pengembalian' => 'required|date',
            'tanggal_expired_perpanjangan' => 'required|date|after:today',
            'alasan_perpanjangan' => 'required|string',
        ];
    }
}
