<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServicesUpdateStatusRequest extends FormRequest
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
            'tanggal_selesai_service' => 'required_if:status_service,selesai',
            'status_service' => 'required|in:onprogress,backlog,selesai',
            'keterangan_service' => 'required|string|max:255',
            'status_kondisi' => 'required|in:baik,rusak',
            // 'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ];
    }
}
