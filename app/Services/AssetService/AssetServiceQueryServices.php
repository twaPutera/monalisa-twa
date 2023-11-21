<?php

namespace App\Services\AssetService;

use App\Models\Service;
use App\Helpers\SsoHelpers;
use Illuminate\Http\Request;
use App\Helpers\DateIndoHelpers;
use App\Models\PerencanaanServices;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class AssetServiceQueryServices
{
    protected $userSsoQueryServices;
    protected $userQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function findAll()
    {
        return Service::all();
    }

    public function findById(string $id)
    {
        $data = Service::query()
            ->with(['detail_service', 'kategori_service', 'image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.service.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }

    public function findLastestLogByAssetId(string $id)
    {
        $services = Service::query()->with(['detail_service'])
            ->whereHas('detail_service', function ($query) use ($id) {
                $query->where('id_asset_data', $id);
            })
            ->where('status_service', 'selesai')
            ->orderby('created_at', 'desc')
            ->first();

        if (isset($services)) {
            if (config('app.sso_siska')) {
                $user = $this->userSsoQueryServices->getUserByGuid($services->guid_pembuat);
                $user = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($services->guid_pembuat);
            }
            $services->dibuat_oleh = $user->name;
        }

        return $services;
    }

    public function findPerencanaanServicesById(string $id, $request = null)
    {
        $data = PerencanaanServices::query();

        if (isset($request->relations)) {
            $data->with($request->relations);
        }

        $data = $data->where('id', $id)
            ->firstOrFail();
        return $data;
    }

    public function getDataAssetPerencanaanServiceSelect2(Request $request)
    {
        $data = PerencanaanServices::query();

        if (isset($request->keyword)) {
            $data->where('keterangan', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->id_asset)) {
            $data->where('id_asset_data', $request->id_asset);
        }

        $data->where('status', 'pending'); //To get all data asset is pending
        $data = $data->orderby('keterangan', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => 'Tanggal ' . DateIndoHelpers::formatDateToIndo($item->tanggal_perencanaan) . ' (' . $item->keterangan . ')',
            ];
        }
        return $results;
    }
    public function getDataChartServices(Request $request)
    {
        $status_backlog = Service::query()
            ->join('detail_services', 'services.id', 'detail_services.id_service')
            ->join('asset_data', 'asset_data.id', 'detail_services.id_asset_data')
            ->where('status_service', 'backlog');

        $status_selesai = Service::query()
            ->join('detail_services', 'services.id', 'detail_services.id_service')
            ->join('asset_data', 'asset_data.id', 'detail_services.id_asset_data')
            ->where('status_service', 'selesai');

        $status_on_progress = Service::query()
            ->join('detail_services', 'services.id', 'detail_services.id_service')
            ->join('asset_data', 'asset_data.id', 'detail_services.id_asset_data')
            ->where('status_service', 'on progress');

        if (isset($request->year)) {
            $status_backlog->whereYear('services.created_at', $request->year);
            $status_selesai->whereYear('services.created_at', $request->year);
            $status_on_progress->whereYear('services.created_at', $request->year);
        }

        if (isset($request->month)) {
            $status_backlog->whereMonth('services.created_at', $request->month);
            $status_selesai->whereMonth('services.created_at', $request->month);
            $status_on_progress->whereMonth('services.created_at', $request->month);
        }

        $user = SsoHelpers::getUserLogin();
        if (! isset($request->global)) {
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                    $status_backlog->where('asset_data.is_it', '1');
                    $status_selesai->where('asset_data.is_it', '1');
                    $status_on_progress->where('asset_data.is_it', '1');
                } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                    $status_backlog->where('asset_data.is_it', '0');
                    $status_selesai->where('asset_data.is_it', '0');
                    $status_on_progress->where('asset_data.is_it', '0');
                }
            }
        }

        $on_progress = $status_on_progress->count();
        $selesai = $status_selesai->count();
        $backlog = $status_backlog->count();

        $all = $on_progress + $selesai + $backlog;

        $all = $all == 0 ? 1 : $all;

        $data = [
            ($on_progress / $all) * 100,
            ($selesai / $all) * 100,
            ($backlog / $all) * 100,
        ];

        return $data;
    }

    public function getDataChartByStatus(Request $request)
    {
        $status = ['selesai', 'on progress', 'backlog'];
        $status_service = ['Selesai', 'Diproses', 'Tertunda', 'Total Services'];
        $color = ['#469B54', '#F03E3E', '#FFC102', '#339AF0'];

        $user = SsoHelpers::getUserLogin();
        foreach ($status as $key => $value) {
            $query = Service::query();
            $query->join('detail_services', 'services.id', 'detail_services.id_service');
            $query->join('asset_data', 'asset_data.id', 'detail_services.id_asset_data');
            $query->where('status_service', $value);

            if (isset($request->year)) {
                $query->whereYear('services.created_at', $request->year);
            }

            if (isset($request->month)) {
                $query->whereMonth('services.created_at', $request->month);
            }

            if (! isset($request->global)) {
                if ($user) {
                    if ($user->role == 'manager_it' || $user->role == 'staff_it') {
                        $query->where('asset_data.is_it', '1');
                    } elseif ($user->role == 'manager_asset' || $user->role == 'staff_asset') {
                        $query->where('asset_data.is_it', '0');
                    }
                }
            }

            if (isset($request->awal)) {
                $query->where('services.tanggal_mulai', '>=', $request->awal);
            }

            if (isset($request->akhir)) {
                $query->where('services.tanggal_selesai', '<=', $request->akhir);
            }

            $count = $query->get()->count();

            $data['data'][] = [
                'value' => $count,
                'itemStyle' => [
                    'color' => $color[$key],
                ],
            ];
            $data['name'][] = $status_service[$key];
        }

        $data['name'][] = 'Total Services';
        $data['data'][] = [
            'value' => array_sum(array_column($data['data'], 'value')),
            'itemStyle' => [
                'color' => $color[3],
            ],
        ];

        return $data;
    }
}
