<?php

namespace App\Http\Requests\AssetService;

use Illuminate\Foundation\Http\FormRequest;

class AssetServiceStoreRequest extends FormRequest
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
            // 'id_lokasi' => 'required|uuid|exists:lokasis,id',
            'tanggal_mulai_service' => 'required_if:select_service_date,baru|date|date_format:Y-m-d',
            'select_service_date' => 'required|in:baru,perencanaan',
            'tanggal_mulai_perencanaan' => 'required_if:select_service_date,perencanaan|exists:perencanaan_services,id',
            'tanggal_selesai_service' => 'required_if:status_service,selesai',
            'permasalahan' => 'required|string|max:255',
            'id_kategori_service' => 'required|uuid|exists:kategori_services,id',
            'tindakan' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'status_service' => 'required|in:onprogress,backlog,selesai',
            'keterangan_service' => 'required|string|max:255',
            'status_kondisi' => 'required|in:baik,rusak',
            // 'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_asset_service' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ];
    }
}
