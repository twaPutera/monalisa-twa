<?php

namespace App\Services\Lokasi;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LokasiDatatableServices
{
    public function datatable(Request $request)
    {
        $query = Lokasi::query();

        if (isset($request->id_parent_lokasi)) {
            $id_parent_lokasi = $request->id_parent_lokasi === 'root' ? null : $request->id_parent_lokasi;
            $query->where('id_parent_lokasi', $id_parent_lokasi);
        }

        if (! isset($request->id_parent_lokasi)) {
            $query->where('id_parent_lokasi', null);
        }

        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form action="' . route('admin.setting.lokasi.delete', $item->id) . '" class="form-confirm" method="POST">';
                $element .= csrf_field();
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.setting.lokasi.edit', $item->id) . '" data-url_update="' . route('admin.setting.lokasi.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>';
                $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                <i class="fa fa-trash"></i>
                            </button>';
                $element .= '</form>';
                return $element;
            })
            ->addColumn('parent', function ($item) {
                $parent = '-';
                if (isset($item->id_parent_lokasi)) {
                    $parent = Lokasi::find($item->id_parent_lokasi)->nama_lokasi ?? '-';
                }
                return $parent;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
