<?php

namespace App\Http\Requests\GroupKategoriAsset;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GroupKategoriAssetStoreRequest extends FormRequest
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
            'kode_group' => ['required', Rule::unique('group_kategori_assets', 'kode_group')->where(function ($query) {
                return $query->where('kode_group', $this->kode_group)->whereNull('deleted_at');
            })],
            'nama_group' => 'required|string|max:255',
        ];
    }
}
