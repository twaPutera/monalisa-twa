<?php

namespace App\Http\Controllers\TestFront;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterDataAssetExport;
use Yajra\DataTables\Facades\DataTables;

class TestingController extends Controller
{
    public function index()
    {
        return view('test-front.index');
    }

    public function tree()
    {
        return view('test-front.tree');
    }

    public function form()
    {
        return view('test-front.form');
    }

    public function formPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female',
            'description' => 'required',
        ]);

        return response()->json([
            'success' => true,
            'data' => $request->all(),
        ]);
    }

    public function select2AjaxData(Request $request)
    {
        $data = [
            ['id' => 1, 'text' => 'Item 1'],
            ['id' => 2, 'text' => 'Item 2'],
            ['id' => 3, 'text' => 'Item 3'],
            ['id' => 4, 'text' => 'Item 4'],
            ['id' => 5, 'text' => 'Item 5'],
            ['id' => 6, 'text' => 'Item 6'],
            ['id' => 7, 'text' => 'Item 7'],
            ['id' => 8, 'text' => 'Item 8'],
            ['id' => 9, 'text' => 'Item 9'],
            ['id' => 10, 'text' => 'Item 10'],
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function table()
    {
        return view('test-front.table');
    }

    public function datatable(Request $request)
    {
        $data = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
            ['id' => 3, 'name' => 'Item 3'],
            ['id' => 4, 'name' => 'Item 4'],
            ['id' => 5, 'name' => 'Item 5'],
            ['id' => 6, 'name' => 'Item 6'],
            ['id' => 7, 'name' => 'Item 7'],
            ['id' => 8, 'name' => 'Item 8'],
            ['id' => 9, 'name' => 'Item 9'],
            ['id' => 10, 'name' => 'Item 10'],
        ];

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function print()
    {
        return Excel::download(new MasterDataAssetExport, 'export_master_data.xlsx');
    }

    public function user()
    {
        return view('layouts.user.master');
    }

  
}
