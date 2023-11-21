<?php

namespace App\Services\KategoriAsset;

use Illuminate\Http\Request;
use App\Models\KategoriAsset;
use Yajra\DataTables\DataTables;

class KategoriAssetDatatableServices
{
    public function datatable(Request $request)
    {
        $query = KategoriAsset::query();
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('group', function ($item) {
                return $item->group_kategori_asset->nama_group ?? 'Tidak Ada Group';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form action="' . route('admin.setting.kategori-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                $element .= csrf_field();
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.setting.kategori-asset.edit', $item->id) . '" data-url_update="' . route('admin.setting.kategori-asset.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>';
                $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                <i class="fa fa-trash"></i>
                            </button>';
                $element .= '</form>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
