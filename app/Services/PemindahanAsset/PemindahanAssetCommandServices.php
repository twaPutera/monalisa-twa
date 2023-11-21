<?php

namespace App\Services\PemindahanAsset;

use Exception;
use App\Models\User;
use App\Models\Approval;
use App\Models\AssetData;
use App\Helpers\SsoHelpers;
use App\Helpers\QrCodeHelpers;
use App\Models\PemindahanAsset;
use App\Models\DetailPemindahanAsset;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Session;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\AssetData\AssetDataCommandServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;
use App\Http\Requests\PemindahanAsset\PemindahanAssetChangeStatusRequest;

class PemindahanAssetCommandServices
{
    protected $userSsoQueryServices;
    protected $assetDataCommandServices;
    protected $userQueryServices;

    public function __construct(
        UserSsoQueryServices $userSsoQueryServices,
        AssetDataCommandServices $assetDataCommandServices,
        UserQueryServices $userQueryServices
    ) {
        $this->userSsoQueryServices = $userSsoQueryServices;
        $this->assetDataCommandServices = $assetDataCommandServices;
        $this->userQueryServices = $userQueryServices;
    }

    public function store(PemindahanAssetStoreRequest $request)
    {
        $request->validated();

        $check_pemindahan_asset = PemindahanAsset::query()->whereHas('detail_pemindahan_asset', function ($q) use ($request) {
            $q->where('id_asset', $request->asset_id);
        })->where('status', 'pending')->exists();
        if ($check_pemindahan_asset) {
            throw new Exception('Asset masih ada dalam pemindahan asset');
        }

        $user = SsoHelpers::getUserLogin();

        // $data_jabatan_penyerah = $this->userSsoQueryServices->getDataPositionById($request->jabatan_penyerah);
        // $data_jabatan_penerima = $this->userSsoQueryServices->getDataPositionById($request->jabatan_penerima);
        // $data_unit_kerja_penyerah = $this->userSsoQueryServices->getUnitById($request->unit_kerja_penyerah);
        // $data_unit_kerja_penerima = $this->userSsoQueryServices->getUnitById($request->unit_kerja_penerima);

        $data_penerima_array = [
            'jabatan' => $request->jabatan_penerima,
            'unit_kerja' => $request->unit_kerja_penerima,
            'guid' => null,
            'nama' => 'Tidak Ada',
            'email' => 'Tidak Ada',
            'no_hp' => 'Tidak Ada',
            'no_induk' => 'Tidak Ada',
        ];
        $data_penyerah_array = [
            'jabatan' => $request->jabatan_penyerah ?? '-',
            'unit_kerja' => $request->unit_kerja_penyerah ?? '-',
            'guid' => null,
            'nama' => 'Tidak Ada',
            'email' => 'Tidak Ada',
            'no_hp' => 'Tidak Ada',
            'no_induk' => 'Tidak Ada',
        ];
        $array_penyerah_array = [];
        $array_penerima_array = [];
        if (config('app.sso_siska')) {
            $data_penerima = $this->userSsoQueryServices->getUserByGuid($request->penerima_asset);
            $data_penyerah = $this->userSsoQueryServices->getUserByGuid($request->penyerah_asset);

            $array_penerima_array = [
                'guid' => $data_penerima[0]['token_user'],
                'nama' => $data_penerima[0]['nama'],
                'email' => $data_penerima[0]['email'],
                'no_hp' => $data_penerima[0]['no_hp'],
                'no_induk' => $data_penerima[0]['no_induk'],
            ];
            $array_penyerah_array = [
                'guid' => $data_penyerah[0]['token_user'],
                'nama' => $data_penyerah[0]['nama'],
                'email' => $data_penyerah[0]['email'],
                'no_hp' => $data_penyerah[0]['no_hp'],
                'no_induk' => $data_penyerah[0]['no_induk'],
            ];
        } else {
            $data_penerima = $this->userQueryServices->findById($request->penerima_asset);
            $data_penyerah = $this->userQueryServices->findById($request->penyerah_asset);

            if (isset($data_penerima)) {
                $array_penerima_array = [
                    'guid' => $data_penerima->guid,
                    'nama' => $data_penerima->name,
                    'email' => $data_penerima->email,
                    'no_hp' => $data_penerima->no_hp,
                    'no_induk' => $data_penerima->no_induk,
                ];
            }

            if (isset($data_penyerah)) {
                $array_penyerah_array = [
                    'guid' => $data_penyerah->guid,
                    'nama' => $data_penyerah->name,
                    'email' => $data_penyerah->email,
                    'no_hp' => $data_penyerah->no_hp,
                    'no_induk' => $data_penyerah->no_induk,
                ];
            }
        }

        $data_penerima_array = array_merge($data_penerima_array, $array_penerima_array);
        $data_penyerah_array = array_merge($data_penyerah_array, $array_penyerah_array);

        $asset = AssetData::where('is_pemutihan', 0)
            ->where('is_draft', '0')
            ->where('id', $request->asset_id)->first();
        $pemindahan_asset = new PemindahanAsset();
        $pemindahan_asset->no_surat = $request->no_bast;
        $pemindahan_asset->tanggal_pemindahan = $request->tanggal_pemindahan;
        $pemindahan_asset->guid_penerima_asset = $request->penerima_asset;
        $pemindahan_asset->guid_penyerah_asset = $request->penyerah_asset;

        $pemindahan_asset->json_penerima_asset = json_encode($data_penerima_array ?? []);
        $pemindahan_asset->json_penyerah_asset = json_encode($data_penyerah_array ?? []);
        $pemindahan_asset->status = 'pending';
        $pemindahan_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $pemindahan_asset->save();

        $pemindahan_asset_detail = new DetailPemindahanAsset();
        $pemindahan_asset_detail->id_pemindahan_asset = $pemindahan_asset->id;
        $pemindahan_asset_detail->id_asset = $asset->id;
        $pemindahan_asset_detail->json_asset_data = json_encode($asset);
        $pemindahan_asset_detail->save();

        // $approval_pemindahan_asset_penerima = new ApprovalPemindahanAsset();
        // $approval_pemindahan_asset_penerima->id_pemindahan_asset = $pemindahan_asset->id;
        // $approval_pemindahan_asset_penerima->guid_approver = $request->penerima_asset;
        // $approval_pemindahan_asset_penerima->save();

        $approval = new Approval();
        $approval->approvable_type = get_class($pemindahan_asset);
        $approval->approvable_id = $pemindahan_asset->id;
        $approval->guid_approver = $request->penerima_asset;
        $approval->save();

        $message_log = 'Pemindahan asset dengan nomor surat ' . $request->no_bast . ' berhasil dibuat pada asset ' . $asset->deskripsi;;
        $this->assetDataCommandServices->insertLogAsset($asset->id, $message_log);

        if (! config('app.sso_siska')) {
            $notifikasi = [
                'title' => 'Pemindahan Asset',
                'message' => 'Pemindahan asset dengan nomor surat ' . $request->no_bast . ' telah dibuat, silahkan melakukan approval',
                'url' => route('user.asset-data.pemindahan.detail', $pemindahan_asset->id),
                'date' => date('d/m/Y H:i'),
            ];
            $user = User::find($request->penerima_asset);
            $user->notify(new UserNotification($notifikasi));
        }

        return $pemindahan_asset;
    }

    public function changeStatus(PemindahanAssetChangeStatusRequest $request, string $id)
    {
        $request->validated();
        $user = Session::get('user');

        $pemindahan_asset = PemindahanAsset::find($id);

        if ($pemindahan_asset->status != 'pending') {
            throw new Exception('Pemindahan asset tidak dapat diubah statusnya');
        }

        // if ($user->guid != $pemindahan_asset->guid_penerima_asset) {
        //     throw new Exception('Anda tidak dapat mengubah status pemindahan asset ini');
        // }

        $approval_pemindahan_asset = $pemindahan_asset->approval->first();
        $approval_pemindahan_asset->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval_pemindahan_asset->tanggal_approval = date('Y-m-d');
        $approval_pemindahan_asset->keterangan = $request->keterangan;

        if ($request->status == 'disetujui') {
            $qr_name = 'qr-approval-pemindahan-' . time() . '.png';
            $path = storage_path('app/images/qr-code/pemindahan/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($approval_pemindahan_asset->id, $path);
            $approval_pemindahan_asset->qr_path = $qr_name;
        }

        $approval_pemindahan_asset->save();

        $pemindahan_asset->status = $request->status;
        $pemindahan_asset->save();

        $message_log = 'Pemindahan asset dengan nomor surat ' . $pemindahan_asset->no_surat . ' berhasil diubah statusnya menjadi ' . $request->status;
        $detail_pemindahan_asset = DetailPemindahanAsset::query()->where('id_pemindahan_asset', $pemindahan_asset->id)->first();
        $this->assetDataCommandServices->insertLogAsset($detail_pemindahan_asset->id_asset, $message_log);

        if ($request->status == 'disetujui') {
            $asset = AssetData::find($detail_pemindahan_asset->id_asset);
            $asset->ownership = $pemindahan_asset->guid_penerima_asset;
            $asset->save();

            $admin = User::where('id', $pemindahan_asset->created_by)->first();

            $notifikasi = [
                'title' => 'Pemindahan Asset',
                'message' => 'Pemindahan asset dengan nomor surat ' . $pemindahan_asset->no_surat . ' telah disetujui',
                'url' => route('admin.approval.pemindahan.index', ['id' => $pemindahan_asset->id]),
                'date' => date('d/m/Y H:i'),
            ];

            $admin->notify(new UserNotification($notifikasi));
        }

        return $pemindahan_asset;
    }
}
