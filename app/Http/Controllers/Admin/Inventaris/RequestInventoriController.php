<?php

namespace App\Http\Controllers\Admin\Inventaris;

use App\Http\Controllers\Controller;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\InventarisData\InventarisDataQueryServices;
use App\Services\InventarisData\InventarisDataCommandServices;

class RequestInventoriController extends Controller
{
    protected $inventarisDataCommandServices;
    protected $inventarisDataQueryServices;
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct(
        InventarisDataCommandServices $inventarisDataCommandServices,
        InventarisDataQueryServices $inventarisDataQueryServices,
        UserSsoQueryServices $userSsoQueryServices,
        UserQueryServices $userQueryServices
    ) {
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
        $this->inventarisDataQueryServices = $inventarisDataQueryServices;
        $this->userSsoQueryServices = $userSsoQueryServices;
        $this->userQueryServices = $userQueryServices;
    }

    public function show($id)
    {
        try {
            $data = $this->inventarisDataQueryServices->findRequestById($id);
            $pengaju = null;
            if (config('app.sso_siska')) {
                $user = $this->userSsoQueryServices->getUserByGuid($data->guid_pengaju);
                $pengaju = isset($user[0]) ? $user[0] : null;
            } else {
                $user = $this->userQueryServices->findById($data->guid_pengaju);
                $pengaju = isset($user) ? $user : null;
            }
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan data',
                'data' => [
                    'request' => $data,
                    'pengaju' => $pengaju,
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
