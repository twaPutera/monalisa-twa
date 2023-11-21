<?php

namespace App\Services\PeminjamanAsset;

use Exception;
use App\Models\User;
use App\Models\Approval;
use App\Models\AssetData;
use App\Helpers\SsoHelpers;
use App\Helpers\QrCodeHelpers;
use App\Models\PeminjamanAsset;
use App\Helpers\DateIndoHelpers;
use App\Jobs\PeminjamanDueDateJob;
use App\Models\LogPeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Models\RequestPeminjamanAsset;
use App\Notifications\UserNotification;
use App\Models\PerpanjanganPeminjamanAsset;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\AssetData\AssetDataCommandServices;
use App\Http\Requests\Approval\PeminjamanApprovalUpdate;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\DetailPeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetChangeStatusRequest;
use App\Http\Requests\PeminjamanAsset\PerpanjanganPeminjamanStoreRequest;

class PeminjamanAssetCommandServices
{
    // TODO: Implement Notifikasi
    protected $userSsoQueryServices;
    protected $assetDataCommandServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->assetDataCommandServices = new AssetDataCommandServices();
    }

    public function store(PeminjamanAssetStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        // $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 34);

        // if (!isset($approver[0])) {
        //     throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        // }

        $peminjaman = new PeminjamanAsset();
        $peminjaman->code = self::generateCode();
        $peminjaman->guid_peminjam_asset = config('app.sso_siska') ? $user->guid : $user->id;
        $peminjaman->json_peminjam_asset = json_encode($user);
        $peminjaman->tanggal_peminjaman = $request->tanggal_peminjaman;
        $peminjaman->jam_selesai = $request->jam_selesai;
        $peminjaman->jam_mulai = $request->jam_mulai;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
        $peminjaman->alasan_peminjaman = $request->alasan_peminjaman;
        $peminjaman->status = 'pending';
        $peminjaman->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $peminjaman->is_it = isset($request->is_it) ? $request->is_it : '0';
        $peminjaman->save();

        foreach ($request->id_jenis_asset as $id_jenis_asset) {
            $request_kategori_detail = $request->data_jenis_asset[$id_jenis_asset];
            $request_peminjaman = new RequestPeminjamanAsset();
            $request_peminjaman->id_peminjaman_asset = $peminjaman->id;
            $request_peminjaman->id_kategori_asset = $id_jenis_asset;
            $request_peminjaman->jumlah = $request_kategori_detail['jumlah'];
            $request_peminjaman->save();
        }

        $approval = new Approval();
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($peminjaman);
        $approval->approvable_id = $peminjaman->id;
        $approval->save();

        $log_message = 'Peminjaman Asset baru dibuat oleh ' . $user->name;
        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        $role = [];

        if ($peminjaman->is_it == '1') {
            $role = ['manager_it', 'staff_it', 'admin'];
        } else {
            $role = ['manager_asset', 'staff_asset', 'admin'];
        }

        $users = User::query()->whereIn('role', $role)->get();

        $notifikasi = [
            'title' => 'Peminjaman Asset',
            'message' => 'Peminjaman Asset dengan kode ' . $peminjaman->code . ' telah diajukan oleh ' . $user->name,
            'url' => route('admin.approval.peminjaman.index', ['peminjaman_id' => $peminjaman->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($users as $user) {
            $user->notify(new UserNotification($notifikasi));
        }

        return $peminjaman;
    }

    private static function generateCode()
    {
        $code = 'PMA-' . date('Ymd') . '-' . rand(1000, 9999);
        $check_code = PeminjamanAsset::where('code', $code)->first();

        if ($check_code) {
            return self::generateCode();
        }

        return $code;
    }

    public function changeApprovalStatus(PeminjamanApprovalUpdate $request, $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $peminjaman = PeminjamanAsset::findOrFail($id);
        $peminjaman->status = $request->status;
        $peminjaman->save();

        $peminjam = User::find($peminjaman->guid_peminjam_asset);

        $approval = $peminjaman->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval->keterangan = $request->keterangan;

        $log_message = 'Approval peminjaman asset dengan kode ' . $peminjaman->code . ' telah ditolak oleh ' . $user->name;
        $notifikasi = [
            'title' => 'Peminjaman Asset',
            'message' => 'Peminjaman Asset dengan kode ' . $peminjaman->code . ' telah ditolak oleh ' . $user->name,
            'url' => route('user.asset-data.peminjaman.index'),
            'date' => date('d/m/Y H:i'),
        ];

        if ($request->status == 'disetujui') {
            $log_message = 'Approval peminjaman asset dengan kode ' . $peminjaman->code . ' telah disetujui oleh ' . $user->name;
            $notifikasi = [
                'title' => 'Peminjaman Asset',
                'message' => 'Peminjaman Asset dengan kode ' . $peminjaman->code . ' telah disetujui oleh ' . $user->name,
                'url' => route('user.asset-data.peminjaman.index'),
                'date' => date('d/m/Y H:i'),
            ];

            $qr_name = 'qr-approval-pemindahan-' . time() . '.png';
            $path = storage_path('app/images/qr-code/peminjaman/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($approval->id, $path);
            $approval->qr_path = $qr_name;
        }

        $peminjam->notify(new UserNotification($notifikasi));

        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        $approval->save();

        return $peminjaman;
    }

    public function storeManyDetailPeminjaman(DetailPeminjamanAssetStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $peminjaman = PeminjamanAsset::findOrFail($request->id_peminjaman_asset);

        $log_message = 'Peminjaman dengan kode ' . $peminjaman->code . ' telah ditambahkan detail dengan rincian ';

        foreach ($request->id_asset as $id_asset) {
            $asset_data = AssetData::where('is_pemutihan', 0)
                ->where('is_draft', '0')
                ->where('id', $id_asset)->first();
            $detail_peminjaman = new DetailPeminjamanAsset();
            $detail_peminjaman->id_peminjaman_asset = $peminjaman->id;
            $detail_peminjaman->json_asset_data = json_encode($asset_data);
            $detail_peminjaman->id_asset = $id_asset;
            $detail_peminjaman->save();

            $log_message .= ', ' . $asset_data->deskripsi;
        }

        $log_message .= ' oleh ' . $user->name;

        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        $request = self::getRequestQuota($peminjaman->id);

        return $request;
    }

    public function deleteDetailPeminjaman(string $id)
    {
        $user = SsoHelpers::getUserLogin();

        $detail_peminjaman = DetailPeminjamanAsset::findOrFail($id);
        $asset = json_decode($detail_peminjaman->json_asset_data);

        $log_message = 'Detail peminjaman dengan kode ' . $detail_peminjaman->peminjaman_asset->code . ' telah dihapus detail dengan rincian ' . $asset->deskripsi . ' oleh ' . $user->name;

        $this->storeLogPeminjamanAsset($detail_peminjaman->peminjaman_asset->id, $log_message);

        $detail_peminjaman->delete();

        $request = self::getRequestQuota($detail_peminjaman->id_peminjaman_asset);

        return $request;
    }

    public function changeStatus(PeminjamanAssetChangeStatusRequest $request, $id)
    {
        $peminjaman = PeminjamanAsset::findOrFail($id);
        $peminjaman->status = $request->status;

        if ($request->status == 'selesai') {
            $peminjaman->rating = $request->rating;
            $peminjaman->keterangan_pengembalian = $request->keterangan_pengembalian;
        }

        $peminjaman->save();

        $peminjam = json_decode($peminjaman->json_peminjam_asset);

        if ($request->status == 'dipinjam') {
            $detail_peminjaman = DetailPeminjamanAsset::where('id_peminjaman_asset', $peminjaman->id)->get();
            foreach ($detail_peminjaman as $detail) {
                $message_log = 'Asset Dipinjam pada tanggal ' . date('d/m/Y', strtotime($peminjaman->tanggal_peminjaman)) . ' oleh ' . $peminjam->name;
                $this->assetDataCommandServices->insertLogAsset($detail->id_asset, $message_log);
            }

            $tanggal_pengembalian = $peminjaman->tanggal_pengembalian . ' ' . $peminjaman->jam_selesai;
            $minutes = DateIndoHelpers::getDiffMinutesFromTwoDates($tanggal_pengembalian, date('Y-m-d H:i:s'));

            logger('Peminjaman', [
                'id' => $peminjaman->id,
                'tanggal_pengembalian' => $tanggal_pengembalian,
                'minutes' => $minutes,
                'now' => now()->addMinutes($minutes)->format('Y-m-d H:i:s'),
            ]);

            PeminjamanDueDateJob::dispatch($peminjaman->id, $tanggal_pengembalian)->delay(now()->addMinutes($minutes));
        }

        $log_message = '';

        if ($request->status == 'dipinjam') {
            $log_message = 'Peminjaman dengan kode ' . $peminjaman->code . ' telah dipinjamkan ke ' . $peminjam->name;
        } elseif ($request->status == 'selesai') {
            $log_message = 'Peminjaman dengan kode ' . $peminjaman->code . ' telah selesai dipinjamkan ke ' . $peminjam->name . ' dengan rating ' . $request->rating;
        }

        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        return $peminjaman;
    }

    public function storeRequestPerpanjangan(PerpanjanganPeminjamanStoreRequest $request, $id_peminjaman)
    {
        $peminjaman = PeminjamanAsset::findOrFail($id_peminjaman);

        $user = SsoHelpers::getUserLogin();

        if ($peminjaman->tanggal_pengembalian > $request->tanggal_expired_perpanjangan) {
            throw new Exception('Tanggal perpanjangan tidak boleh kurang dari tanggal pengembalian');
        }

        $perpanjangan = new PerpanjanganPeminjamanAsset();
        $perpanjangan->id_peminjaman_asset = $peminjaman->id;
        $perpanjangan->tanggal_expired_sebelumnya = $request->tanggal_pengembalian;
        $perpanjangan->tanggal_expired_perpanjangan = $request->tanggal_expired_perpanjangan;
        $perpanjangan->alasan_perpanjangan = $request->alasan_perpanjangan;
        $perpanjangan->status = 'pending';
        $perpanjangan->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $perpanjangan->is_it = $peminjaman->is_it;
        $perpanjangan->save();

        $approval = new Approval();
        $approval->approvable_type = get_class($perpanjangan);
        $approval->approvable_id = $perpanjangan->id;
        $peminjaman->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->save();

        $log_message = 'Peminjaman dengan kode ' . $peminjaman->code . ' telah diajukan perpanjangan dengan tanggal ' . date('d/m/Y', strtotime($request->tanggal_expired_perpanjangan)) . ' oleh ' . $user->name;

        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        return $perpanjangan;
    }

    public function changeApprovalStatusPerpanjangan(PeminjamanApprovalUpdate $request, $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $perpanjangan = PerpanjanganPeminjamanAsset::findOrFail($id);
        $perpanjangan->status = $request->status;
        $perpanjangan->save();

        $peminjaman = $perpanjangan->peminjaman_asset;

        $log_message = 'Peminjaman dengan kode ' . $perpanjangan->peminjaman_asset->code . ' telah ditolak perpanjangannya oleh ' . $user->name;
        if ($request->status == 'disetujui') {
            $log_message = 'Peminjaman dengan kode ' . $perpanjangan->peminjaman_asset->code . ' telah disetujui perpanjangannya oleh ' . $user->name;
            $peminjaman->tanggal_pengembalian = $perpanjangan->tanggal_expired_perpanjangan;
            $peminjaman->save();

            $tanggal_pengembalian = $peminjaman->tanggal_pengembalian . ' ' . $peminjaman->jam_selesai;
            $minutes = DateIndoHelpers::getDiffMinutesFromTwoDates($tanggal_pengembalian, date('Y-m-d H:i:s'));

            logger('Peminjaman', [
                'id' => $peminjaman->id,
                'tanggal_pengembalian' => $tanggal_pengembalian,
                'minutes' => $minutes,
                'now' => now()->addMinutes($minutes)->format('Y-m-d H:i:s'),
            ]);

            PeminjamanDueDateJob::dispatch($peminjaman->id, $tanggal_pengembalian)->delay(now()->addMinutes($minutes));
        }

        $this->storeLogPeminjamanAsset($peminjaman->id, $log_message);

        $approval = $perpanjangan->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval->keterangan = $request->keterangan;
        $approval->save();

        return $perpanjangan;
    }

    private static function getRequestQuota(string $id)
    {
        $request = RequestPeminjamanAsset::where('id_peminjaman_asset', $id)
            ->join('kategori_assets', 'kategori_assets.id', '=', 'request_peminjaman_assets.id_kategori_asset')
            ->select(
                'id_kategori_asset as id',
                'nama_kategori',
                'jumlah'
            )
            ->get();
        return $request;
    }

    public function storeLogPeminjamanAsset($peminjaman_asset_id, $message)
    {
        $user = SsoHelpers::getUserLogin();

        $log = new LogPeminjamanAsset();
        $log->peminjaman_asset_id = $peminjaman_asset_id;
        $log->log_message = $message;
        $log->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log->save();
    }
}
