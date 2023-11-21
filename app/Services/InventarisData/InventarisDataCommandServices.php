<?php

namespace App\Services\InventarisData;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Approval;
use App\Helpers\SsoHelpers;
use App\Models\InventoriData;
use App\Models\RequestInventori;
use App\Models\LogRequestInventori;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailRequestInventori;
use App\Models\LogPenambahanInventori;
use App\Models\LogPenguranganInventori;
use App\Notifications\UserNotification;
use App\Http\Requests\Approval\RequestInventoriUpdate;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Http\Requests\InventarisData\InventarisDataRealisasiRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateStokRequest;
use App\Http\Requests\InventarisData\InventarisDataStoreUpdateRequest;
use App\Http\Requests\InventarisData\UserRequestInventoriStoreRequest;
use App\Http\Requests\InventarisData\UserRequestInventoriUpdateRequest;

class InventarisDataCommandServices
{
    public function store(InventarisDataStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = new InventoriData();
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_saat_ini = $request->stok;
        $inventori_data->jumlah_sebelumnya = $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

        return $inventori_data;
    }

    public function storeFromUser(UserRequestInventoriStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $request_inventaris = new RequestInventori();
        $request_inventaris->guid_pengaju = config('app.sso_siska') ? $user->guid : $user->id;
        $request_inventaris->tanggal_pengambilan = $request->tanggal_pengambilan;
        $request_inventaris->unit_kerja = $request->unit_kerja;
        $request_inventaris->no_memo = $request->no_memo;
        $request_inventaris->jabatan = $request->jabatan;
        $request_inventaris->status = 'pending';
        $request_inventaris->kode_request = self::generateCode();
        $request_inventaris->alasan = $request->alasan_permintaan;
        $request_inventaris->save();

        foreach ($request->id_bahan_habis_pakai as $id_bahan_habis_pakai) {
            $request_kategori_detail = $request->data_bahan_habis_pakai[$id_bahan_habis_pakai];
            $detail_request_inventaris = new DetailRequestInventori();
            $detail_request_inventaris->request_inventori_id = $request_inventaris->id;
            $detail_request_inventaris->inventori_id = $id_bahan_habis_pakai;
            $detail_request_inventaris->qty = $request_kategori_detail['jumlah'];
            $detail_request_inventaris->save();
        }
        $message = 'Permintaan bahan habis pakai baru dengan kode ' . $request_inventaris->kode_request . ' dibuat oleh ' . $user->name;
        $this->storeLogRequestInventori($request_inventaris->id, $message, 'pending');

        $approval = new Approval();
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($request_inventaris);
        $approval->approvable_id = $request_inventaris->id;
        $approval->save();

        $target_user = User::where('role', '!=', 'user')->get();
        $notifikasi = [
            'title' => 'Permintaan Bahan Habis Pakai',
            'message' => 'Permintaan Bahan Habis Pakai dengan kode ' . $request_inventaris->kode_request . ' telah ditambahkan oleh ' . $user->name,
            'url' => route('admin.approval.request-inventori.index', ['request_id' => $request_inventaris->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }
        return $request_inventaris;
    }

    public function updateFromUser(UserRequestInventoriUpdateRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $request_inventaris = RequestInventori::find($id);
        $request_inventaris->guid_pengaju = config('app.sso_siska') ? $user->guid : $user->id;
        $request_inventaris->tanggal_pengambilan = $request->tanggal_pengambilan;
        $request_inventaris->unit_kerja = $request->unit_kerja;
        $request_inventaris->jabatan = $request->jabatan;
        $request_inventaris->no_memo = $request->no_memo;
        $request_inventaris->status = 'pending';
        $request_inventaris->alasan = $request->alasan_permintaan;
        $request_inventaris->save();

        foreach ($request_inventaris->detail_request_inventori as $item) {
            $item->delete();
        }

        foreach ($request->id_bahan_habis_pakai as $id_bahan_habis_pakai) {
            $request_kategori_detail = $request->data_bahan_habis_pakai[$id_bahan_habis_pakai];
            $detail_request_inventaris = new DetailRequestInventori();
            $detail_request_inventaris->request_inventori_id = $request_inventaris->id;
            $detail_request_inventaris->inventori_id = $id_bahan_habis_pakai;
            $detail_request_inventaris->qty = $request_kategori_detail['jumlah'];
            $detail_request_inventaris->save();
        }
        $message = 'Permintaan bahan habis pakai dengan kode ' . $request_inventaris->kode_request . ' berhasil diperbaharui oleh ' . $user->name;
        $this->storeLogRequestInventori($request_inventaris->id, $message, 'pending');

        $approval = new Approval();
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($request_inventaris);
        $approval->approvable_id = $request_inventaris->id;
        $approval->save();

        $target_user = User::where('role', '!=', 'user')->get();
        $notifikasi = [
            'title' => 'Permintaan Bahan Habis Pakai',
            'message' => 'Permintaan Bahan Habis Pakai dengan kode ' . $request_inventaris->kode_request . ' telah ditambahkan oleh ' . $user->name,
            'url' => route('admin.approval.request-inventori.index', ['request_id' => $request_inventaris->id]),
            'date' => date('d/m/Y H:i'),
        ];

        foreach ($target_user as $target) {
            $target->notify(new UserNotification($notifikasi));
        }

        return $request_inventaris;
    }

    public function generateCode()
    {
        $code = 'RBHP-' . date('Ymd') . '-' . rand(1000, 9999);
        $check_code = RequestInventori::where('kode_request', $code)->first();

        if ($check_code) {
            return self::generateCode();
        }

        return $code;
    }

    public function storeLogRequestInventori($request_inventori_id, $message, $status)
    {
        $user = SsoHelpers::getUserLogin();

        $log = new LogRequestInventori();
        $log->request_inventori_id = $request_inventori_id;
        $log->message = $message;
        $log->status = $status;
        $log->created_by = $user->name;
        $log->save();
    }

    public function storeUpdate(InventarisDataStoreUpdateRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($request->id_inventaris);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
        $inventori_data->jumlah_saat_ini = $inventori_data->jumlah_saat_ini + $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

        return $inventori_data;
    }

    public function update(string $id, InventarisDataUpdateRequest $request)
    {
        $request->validated();

        $inventori_data = InventoriData::findOrFail($id);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function delete(string $id)
    {
        $inventori_data = InventoriData::findOrFail($id);

        $find_all_request_inventori = DetailRequestInventori::where('inventori_id', $inventori_data->id)->get();
        foreach ($find_all_request_inventori as $item) {
            $request_inventori = RequestInventori::where('id', $item->request_inventori_id)->first();

            $log_request_inventori = new LogRequestInventori();
            $log_request_inventori->request_inventori_id = $request_inventori->id;
            $log_request_inventori->message = 'Barang habis pakai ' . $item->inventori->nama_inventori . ' dengan kode inventori ' . $item->inventori->kode_inventori . ' telah dihapus dari daftar permintaan dikarenakan Barang Habis Pakai dihapus oleh Admin';
            $log_request_inventori->status = 'ditolak';
            $log_request_inventori->created_by = Auth::user()->id;
            $log_request_inventori->save();

            $item->delete();
        }

        $find_all_log_penambahan = LogPenambahanInventori::where('id_inventori', $inventori_data->id)->get();
        foreach ($find_all_log_penambahan as $item) {
            $item->delete();
        }

        $find_all_log_pengurangan = LogPenguranganInventori::where('id_inventori', $inventori_data->id)->get();
        foreach ($find_all_log_pengurangan as $item) {
            $item->delete();
        }
        return $inventori_data->delete();
    }

    public function storeRealisasi(InventarisDataRealisasiRequest $request, string $id)
    {
        $request->validated();
        $request_inventaris = RequestInventori::where('status', '!=', 'ditolak')->where('id', $id)->first();
        $request_inventaris->status = 'selesai';
        $request_inventaris->save();

        $user = SsoHelpers::getUserLogin();

        foreach ($request->id_inventaris as $id_inventaris) {
            $request_kategori_detail = $request->data_realisasi[$id_inventaris];
            $detail_request_inventaris = DetailRequestInventori::find($id_inventaris);

            $find_inventaris = InventoriData::find($detail_request_inventaris->inventori_id);
            $selisih = $find_inventaris->jumlah_saat_ini - $request_kategori_detail['jumlah'];
            if ($selisih < 0) {
                throw new Exception('Stok bahan habis pakai tidak mencukupi !');
                break;
            }

            $find_inventaris->jumlah_sebelumnya = $find_inventaris->jumlah_saat_ini;
            $find_inventaris->jumlah_saat_ini = $selisih;
            $find_inventaris->save();

            $detail_request_inventaris->realisasi = $request_kategori_detail['jumlah'];
            $detail_request_inventaris->save();

            $log_pengurangan = new LogPenguranganInventori();
            $log_pengurangan->id_inventori = $find_inventaris->id;
            $log_pengurangan->no_memo = $request_inventaris->no_memo;
            $log_pengurangan->jumlah = $request_kategori_detail['jumlah'];
            $log_pengurangan->tanggal = Carbon::now()->format('Y-m-d');
            $log_pengurangan->created_by = $user->name;
            $log_pengurangan->save();
        }
        $message = 'Permintaan bahan habis pakai dengan kode ' . $request_inventaris->kode_request . ' berhasil direalisasi oleh ' . $user->name;
        $this->storeLogRequestInventori($request_inventaris->id, $message, 'selesai');

        $target = User::find($request_inventaris->guid_pengaju);
        $notifikasi = [
            'title' => 'Permintaan Bahan Habis Pakai',
            'message' => 'Permintaan Bahan Habis Pakai dengan kode ' . $request_inventaris->kode_request . ' telah direalisasi oleh ' . $user->name,
            'url' => route('user.asset-data.bahan-habis-pakai.index'),
            'date' => date('d/m/Y H:i'),
        ];

        $target->notify(new UserNotification($notifikasi));
        return $request_inventaris;
    }

    public function updateStok(string $id, InventarisDataUpdateStokRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($id);
        $selisih = $inventori_data->jumlah_saat_ini - $request->jumlah_keluar;
        if ($selisih >= 0) {
            $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
            $inventori_data->jumlah_saat_ini = $selisih;
            $inventori_data->save();

            $detailInventori = new LogPenguranganInventori();
            $detailInventori->id_inventori = $id;
            $detailInventori->id_surat_memo_andin = $request->id_surat_memo_andin;
            $detailInventori->no_memo = $request->no_memo;
            $detailInventori->jumlah = $request->jumlah_keluar;
            $detailInventori->tanggal = $request->tanggal;
            $detailInventori->created_by = $user->name;
            $detailInventori->save();
            return $inventori_data;
        }
        return false;
    }

    public function changeApprovalStatus(RequestInventoriUpdate $request, $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $request_inventori = RequestInventori::findOrFail($id);
        $request_inventori->status = $request->status == 'disetujui' ? 'diproses' : 'ditolak';
        $request_inventori->save();

        $approval = $request_inventori->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval->keterangan = $request->keterangan;
        $approval->save();

        $log_message = 'Approval request bahan habis pakai dengan kode ' . $request_inventori->kode_request . ' telah ditolak oleh ' . $user->name;
        $target = User::find($request_inventori->guid_pengaju);
        $notifikasi = [
            'title' => 'Permintaan Bahan Habis Pakai',
            'message' => 'Permintaan Bahan Habis Pakai dengan kode ' . $request_inventori->kode_request . ' tidak ditolak oleh ' . $user->name,
            'url' => route('user.asset-data.bahan-habis-pakai.index'),
            'date' => date('d/m/Y H:i'),
        ];

        if ($request->status == 'disetujui') {
            $log_message = 'Approval request bahan habis pakai dengan kode ' . $request_inventori->kode_request . ' telah disetujui oleh ' . $user->name;
            $notifikasi = [
                'title' => 'Permintaan Bahan Habis Pakai',
                'message' => 'Permintaan Bahan Habis Pakai dengan kode ' . $request_inventori->kode_request . ' telah disetujui oleh ' . $user->name,
                'url' => route('user.asset-data.bahan-habis-pakai.index'),
                'date' => date('d/m/Y H:i'),
            ];
        }
        $target->notify(new UserNotification($notifikasi));
        $this->storeLogRequestInventori($request_inventori->id, $log_message, $request->status == 'disetujui' ? 'diproses' : 'ditolak');

        return $request_inventori;
    }
}
