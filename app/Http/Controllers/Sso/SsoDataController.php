<?php

namespace App\Http\Controllers\Sso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserSso\UserSsoQueryServices;

class SsoDataController extends Controller
{
    protected $userSsoQueryServices;

    public function __construct(UserSsoQueryServices $userSsoQueryServices)
    {
        $this->userSsoQueryServices = $userSsoQueryServices;
    }

    public function getDataUnit()
    {
        try {
            $response = $this->userSsoQueryServices->getDataUnit();
            $data = collect($response)->map(function ($item) {
                return [
                    'id' => $item['cn'][0],
                    'text' => $item['cn'][0],
                ];
            });
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getDataPosition()
    {
        try {
            $response = $this->userSsoQueryServices->getDataPosition();
            $data = collect($response)->map(function ($item) {
                return [
                    'id' => $item['cn'][0],
                    'text' => $item['cn'][0],
                ];
            });
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getDataPositionByGuid(Request $request)
    {
        try {
            $response = $this->userSsoQueryServices->getPositionByGuid($request->guid);
            $data = collect($response)->map(function ($item) {
                return [
                    'id' => $item['entryuuid'][0],
                    'text' => $item['cn'][0],
                ];
            });
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getDataUnitByGuid(Request $request)
    {
        try {
            $response = $this->userSsoQueryServices->getUnitByGuid($request->guid_position);
            $data = collect($response)->map(function ($item) {
                return [
                    'id' => $item['entryuuid'][0],
                    'text' => $item['cn'][0],
                ];
            });
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
