<?php

namespace App\Services\User;

use App\Models\User;
use App\Helpers\CutText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserQueryServices
{
    public function findUnitKerja(Request $request){
        $response=DB::table('unit_kerjas')
        ->select('*');
        if($request->has('keyword')){
            $response->where('nama_unit_kerja', 'like', '%' . $request->keyword . '%');
        }
        return $response->get();
    }

    public function findAll(Request $request)
    {
        $users = User::query();

        if ($request->has('name')) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('keyword')) {
            $users->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('email')) {
            $users->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('username_sso')) {
            $users->where('username_sso', 'like', '%' . $request->username_sso . '%');
        }

        if ($request->has('role')) {
            $users->where('role', $request->role);
        }

        if ($request->has('status')) {
            $users->where('is_active', $request->status);
        }

        return $users->get();
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function findUnitKerjaById($id){
        $response=DB::table('unit_kerjas')
        ->select('nama_unit_kerja')->where('id',$id);
       
        return $response->first()->nama_unit_kerja;
    }

    public function getDataUserSelect2(Request $request)
    {
        $data = User::query();
        if (isset($request->keyword)) {
            $data->where('users.name', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('created_at', 'asc')
            ->get();
        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->name . ' (' . ucWords(strtolower(CutText::cutUnderscore($item->role))) . ')',
            ];
        }

        return $results;
    }
}
