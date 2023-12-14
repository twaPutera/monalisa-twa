<?php

namespace App\Http\Requests\AssetData;

use App\Models\SistemConfig;
use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
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
        $min_no_urut = SistemConfig::where('config', 'min_no_urut')->first();
        $min_no_urut = $min_no_urut->value ?? '5';

        return [
            'kode_asset' => 'required|string|unique:asset_data,kode_asset|max:255',
            'id_vendor' => 'nullable|uuid|exists:vendors,id',
            'id_lokasi' => 'nullable|uuid|exists:lokasis,id',
            'id_kelas_asset' => 'nullable|uuid|exists:kelas_assets,id',
            'id_group_asset' => 'required',
            'id_kategori_asset' => 'required|uuid|exists:kategori_assets,id',
            'id_satuan_asset' => 'required|uuid|exists:satuan_assets,id',
            'deskripsi' => 'required|string|max:255',
            'tanggal_perolehan' => 'required|date',
            //perubahan oleh wahyu
            'unit_kerja' => 'required',
            'tanggal_pelunasan' => 'nullable|date',
            //'nilai_perolehan' => 'required|numeric',
            'nilai_perolehan' => 'nullable|numeric', //tambahan wahyu
            // 'nilai_buku_asset' => 'required|numeric|lte:nilai_perolehan',
            'jenis_penerimaan' => 'required|string|max:255|in:PO,Hibah Eksternal,Hibah Penelitian,Hibah Perorangan,UMK,CC,Reimburse',
            //'ownership' => 'nullable|uuid',
            'ownership' => 'nullable', //tambahan dari wahyu
            // 'tgl_register' => 'required|date|date_format:Y-m-d',
            // 'register_oleh' => 'required|uuid',
            'no_memo_surat' => 'nullable|string|max:50',
            'no_memo_surat_manual' => 'nullable|required_if:status_memorandum,manual|string|max:50',
            'id_surat_memo_andin' => 'nullable|required_if:status_memorandum,andin|uuid',
            'status_memorandum' => 'required|string|in:andin,manual,tidak-ada',
            'no_po' => 'nullable|string|max:50',
            'no_sp3' => 'nullable|string|max:50',
            'status_kondisi' => 'required|string|max:50',
            'status_akunting' => 'required|string|max:50',
            'no_seri' => 'nullable|string|max:50',
            'no_urut' => 'nullable|string|max:50|min:' . $min_no_urut,
            'cost_center' => 'nullable|string|max:255',
            'call_center' => 'nullable|string|max:50',
            'spesifikasi' => 'required|string|max:255',
            'status_kondisi' => 'required|string|max:50',
            // 'nilai_depresiasi' => 'required|numeric',
            // 'umur_manfaat_fisikal' => 'nullable|numeric',
            // 'umur_manfaat_komersial' => 'nullable|numeric',
            // 'gambar_asset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gambar_asset' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'is_sparepart' => 'nullable|in:0,1',
            'is_pinjam' => 'nullable|in:0,1',
            'is_it' => 'nullable|in:0,1',
            'asal_asset' => 'nullable|string|uuid',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'id_vendor.exists' => 'Vendor not found',
    //         'id_lokasi.exists' => 'Lokasi not found',
    //         'id_kelas_asset.exists' => 'Kelas Asset not found',
    //         'id_kelas_asset.required' => 'Kelas Asset must not be empty',
    //         'id_kategori_asset.exists' => 'Kategori Asset not found',
    //         'id_kategori_asset.required' => 'Kategori Asset must not be empty',
    //         'id_satuan_asset.exists' => 'Satuan Asset not found',
    //         'id_satuan_asset.required' => 'Satuan Asset must not be empty',
    //     ];
    // }
}
