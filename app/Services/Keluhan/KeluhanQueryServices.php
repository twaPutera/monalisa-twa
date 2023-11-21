<?php

namespace App\Services\Keluhan;

use App\Models\Pengaduan;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class KeluhanQueryServices
{
    protected $userSsoQueryServices;
    protected $userQueryServices;
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function findById(string $id)
    {
        $data = Pengaduan::query()
            ->with(['image' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }, 'asset_data', 'lokasi', 'asset_data.lokasi', 'asset_data.kategori_asset', 'asset_data.kategori_asset.group_kategori_asset'])
            ->where('id', $id)
            ->firstOrFail();
        $name = 'Not Found';
        if (isset($data->created_by)) {
            if (config('app.sso_siska')) {
                $user = $this->userSsoQueryServices->getUserByGuid($data->created_by);
                $name = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($data->created_by);
                $name = isset($user) ? $user->name : 'Not Found';
            }
        }
        $data->created_by_name = $name;

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.keluhan.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }
}
