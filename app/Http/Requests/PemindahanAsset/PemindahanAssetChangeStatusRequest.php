<?php

namespace App\Http\Requests\PemindahanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemindahanAssetChangeStatusRequest extends FormRequest
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
            'status' => 'required|in:disetujui,ditolak',
            'keterangan' => 'required_if:status,ditolak',
        ];
    }
}
