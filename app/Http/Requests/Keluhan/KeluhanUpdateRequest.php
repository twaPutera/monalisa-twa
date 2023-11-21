<?php

namespace App\Http\Requests\Keluhan;

use Illuminate\Foundation\Http\FormRequest;

class KeluhanUpdateRequest extends FormRequest
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
            // 'file_pendukung' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_pendukung' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'status_pengaduan' => 'required|in:diproses,selesai',
            'catatan_admin' => 'required|string|max:255',
        ];
    }
}
