<?php

namespace App\Services\AssetData;

use App\Models\Service;
use App\Models\LogAsset;
use App\Models\AssetData;
use App\Models\Pengaduan;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use App\Models\DetailService;
use App\Models\KategoriAsset;
use App\Helpers\QrCodeHelpers;
use App\Models\LogAssetOpname;
use App\Models\DepresiasiAsset;
use App\Models\LogServiceAsset;
use App\Models\PeminjamanAsset;
use App\Models\LogPengaduanAsset;
use App\Helpers\DepresiasiHelpers;
use App\Models\LogPeminjamanAsset;
use App\Models\PerencanaanServices;
use App\Helpers\SistemConfigHelpers;
use App\Models\DetailPemutihanAsset;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPemindahanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Http\Requests\AssetData\AssetUpdateRequest;
use App\Http\Requests\AssetData\AssetDataDeleteRequest;
use App\Http\Requests\AssetData\AssetDataPublishRequest;
use App\Http\Requests\AssetData\AssetUpdateDraftRequest;
use App\Services\SistemConfig\SistemConfigQueryServices;

class AssetDataCommandServices
{
    protected $sistemConfigServices;

    public function __construct()
    {
        $this->sistemConfigServices = new SistemConfigQueryServices();
    }

    public function store(AssetStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $kategori_asset = KategoriAsset::find($request->id_kategori_asset);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($request->nilai_perolehan, ($kategori_asset->umur_asset * 12));

        $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);

        $min_asset_value = SistemConfigHelpers::get('min_asset_value');

        $asset = new AssetData();
        $asset->deskripsi = $request->deskripsi;
        $asset->kode_asset = $request->kode_asset;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_kelas_asset = $request->id_kelas_asset;
        $asset->id_kategori_asset = $request->id_kategori_asset;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->tanggal_perolehan = $request->tanggal_perolehan;
        $asset->tgl_pelunasan = $request->tanggal_pelunasan;
        $asset->nilai_perolehan = $request->nilai_perolehan;
        $asset->nilai_buku_asset = $request->nilai_perolehan;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->ownership = $request->ownership;
        $asset->tgl_register = date('Y-m-d');
        $asset->register_oleh = config('app.sso_siska') ? $user->guid : $user->id;
        $asset->no_memo_surat = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->no_memo_surat : $request->no_memo_surat_manual);
        $asset->id_surat_memo_andin = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->id_surat_memo_andin : null);
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_urut = $request->no_urut;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->status_kondisi = $request->status_kondisi;
        $asset->status_akunting = $request->status_akunting;
        $asset->no_seri = $request->no_seri;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->nilai_depresiasi = $nilai_depresiasi;
        $asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $asset->qr_code = $qr_name;
        $asset->umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($request->tanggal_perolehan, ($kategori_asset->umur_asset * 12));
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';
        $asset->is_inventaris = $min_asset_value > $request->nilai_perolehan ? '1' : '0';
        $asset->is_draft = '1';
        $asset->tanggal_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($request->tanggal_perolehan);
        $asset->tanggal_akhir_depresiasi = DepresiasiHelpers::getAkhirTanggalDepresiasi($asset->tanggal_awal_depresiasi, $kategori_asset->umur_asset);
        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $path = storage_path('app/images/asset');
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        $message_log = 'Asset baru dengan kode ' . $asset->kode_asset . ' telah ditambahkan';
        $this->insertLogAsset($asset->id, $message_log);

        if (isset($request->asal_asset)) {
            $asset_asal = AssetData::find($request->asal_asset);
            if (isset($asset_asal)) {
                $message_log = 'Asset ini diambil dari asset ' . $asset_asal->deskripsi . ' dengan kode asset ' . $asset_asal->kode_asset;
                if ($asset_asal->status_kondisi != 'rusak') {
                    $asset_asal->status_kondisi = 'tidak-lengkap';
                    $asset_asal->save();
                }

                // * insert log asset
                $this->insertLogAsset($asset->id, $message_log);

                // * store log ke asal asset
                $message_log_asal_asset = 'Sparepart Asset ' . $asset_asal->deskripsi . ' dengan kode asset ' . $asset_asal->kode_asset . ' telah diambil menjadi sparepart asset ' . $asset->deskripsi . ' dengan kode asset ' . $asset->kode_asset;
                $this->insertLogAsset($asset_asal->id, $message_log_asal_asset);
            }
        }

        return $asset;
    }

    public function update(AssetUpdateRequest $request, string $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $asset = AssetData::find($id);

        if ($request->kode_asset != $asset->kode_asset) {
            \File::delete(storage_path('app/images/qr-code/' . $asset->qr_code));
            $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);
            $asset->qr_code = $qr_name;
        }

        $asset->kode_asset = $request->kode_asset;
        $asset->deskripsi = $request->deskripsi;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->no_memo_surat = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->no_memo_surat : $request->no_memo_surat_manual);
        $asset->id_surat_memo_andin = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->id_surat_memo_andin : null);
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_seri = $request->no_seri;
        $asset->no_urut = $request->no_urut;
        $asset->tgl_pelunasan = Auth::user()->role == 'admin' ? $request->tanggal_pelunasan : $asset->tgl_pelunasan;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';

        if ($request->nilai_perolehan !=  $asset->nilai_perolehan) {
            DepresiasiAsset::query()
                ->where('id_asset_data', $asset->id)
                ->delete();

            $nilai_buku = DepresiasiHelpers::storePastDepresiasiAsset($asset, $asset->tanggal_awal_depresiasi, $request->nilai_perolehan);
            $asset->nilai_buku_asset = $nilai_buku;
            $asset->nilai_perolehan = $request->nilai_perolehan;
        }

        //block kode ini ditambahkan oleh wahyu
        if(isset($request->tanggal_pelunasan)){
            $nilai_buku = DepresiasiHelpers::storePastDepresiasiAsset($asset, $asset->tanggal_awal_depresiasi, $request->nilai_perolehan);
            $asset->nilai_buku_asset = $nilai_buku;
            $asset->nilai_buku_asset = $nilai_buku;
            $asset->nilai_perolehan = $request->nilai_perolehan;
        }

        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($asset->image[0])) {
                $pathOld = $path . '/' . $asset->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        return $asset;
    }

    public function updateDraft(AssetUpdateDraftRequest $request, string $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $asset = AssetData::find($id);
        $asset->deskripsi = $request->deskripsi;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->no_memo_surat = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->no_memo_surat : $request->no_memo_surat_manual);
        $asset->id_surat_memo_andin = isset($request->status_memorandum) && $request->status_memorandum == 'tidak-ada' ? null : ($request->status_memorandum == 'andin' ? $request->id_surat_memo_andin : null);
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_seri = $request->no_seri;
        $asset->no_urut = $request->no_urut;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';
        $asset->kode_asset = $request->kode_asset;
        $asset->id_kelas_asset = $request->id_kelas_asset;
        $asset->id_kategori_asset = $request->id_kategori_asset;
        $asset->tanggal_perolehan = $request->tanggal_perolehan;
        $asset->tgl_pelunasan = $request->tanggal_pelunasan;
        $asset->nilai_perolehan = $request->nilai_perolehan;
        $asset->nilai_buku_asset = $request->nilai_perolehan;
        $asset->ownership = $request->ownership;
        $asset->tgl_register = date('Y-m-d');
        $asset->status_kondisi = $request->status_kondisi;

        if ($request->kode_asset != $asset->kode_asset) {
            \File::delete(storage_path('app/images/qr-code/' . $asset->qr_code));
            $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);
            $asset->qr_code = $qr_name;
        }

        if ($request->tanggal_perolehan != $asset->tanggal_perolehan || $request->id_kategori_asset != $asset->id_kategori_asset || $request->nilai_perolehan != $asset->nilai_perolehan) {
            $kategori_asset = KategoriAsset::find($request->id_kategori_asset);
            $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($request->nilai_perolehan, ($kategori_asset->umur_asset * 12));
            $asset->nilai_depresiasi = $nilai_depresiasi;
            $asset->umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($request->tanggal_perolehan, ($kategori_asset->umur_asset * 12));
            $asset->tanggal_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($request->tanggal_perolehan);
            $asset->tanggal_akhir_depresiasi = DepresiasiHelpers::getAkhirTanggalDepresiasi($asset->tanggal_awal_depresiasi, $kategori_asset->umur_asset);
        }

        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($asset->image[0])) {
                $pathOld = $path . '/' . $asset->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        return $asset;
    }

    public function insertLogAsset($asset_id, $log)
    {
        $user = SsoHelpers::getUserLogin();

        $log_asset = new LogAsset();
        $log_asset->asset_id = $asset_id;
        $log_asset->log = $log;
        $log_asset->created_by = $user->name;
        $log_asset->save();
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    public function delete(string $id)
    {
        $asset = AssetData::find($id);

        if ($asset->is_draft == '0') {
            throw new \Exception('Tidak bisa menghapus asset yang sudah di publish');
        }
        $image = AssetImage::where('imageable_id', $asset->id)->first();
        $path = storage_path('app/images/asset');
        $pathOld = $path . '/' . $image->path;
        FileHelpers::removeFile($pathOld);
        $image->delete();

        $logs = LogAsset::where('asset_id', $asset->id)->get();
        foreach ($logs as $log) {
            $log->delete();
        }

        if ($asset) {
            $asset->forceDelete();
        }
    }

    public function putToTrash(string $id)
    {
        $asset = AssetData::find($id);

        if ($asset->is_draft == '1') {
            throw new \Exception('Tidak bisa menghapus asset yang belum di publish');
        }

        if ($asset->deleted_at != null) {
            throw new \Exception('Tidak bisa menghapus asset yang sudah di tempat sampah');
        }

        // Remove Asset Image
        // $images = AssetImage::where('imageable_id', $asset->id)->get();
        // foreach ($images as $image) {
        //     $path = storage_path('app/images/asset');
        //     $pathOld = $path . '/' . $image->path;
        //     FileHelpers::removeFile($pathOld);
        //     $image->delete();
        // }

        // Remove Depresiasi Asset
        $depresiasis = DepresiasiAsset::where('id_asset_data', $asset->id)->get();
        foreach ($depresiasis as $depresiasi) {
            $depresiasi->delete();
        }

        // Remove Detail Pemindahan Assets
        $detail_pemindahan_assets = DetailPemindahanAsset::where('id_asset', $asset->id)->get();
        foreach ($detail_pemindahan_assets as $detail_pemindahan_asset) {
            $detail_pemindahan_asset->delete();
        }

        // Remove Detail Peminjaman Asset
        $detail_peminjaman_assets = DetailPeminjamanAsset::where('id_asset', $asset->id)->get();
        foreach ($detail_peminjaman_assets as $detail_peminjaman_asset) {
            $peminjaman_asset = PeminjamanAsset::where('id', $detail_peminjaman_asset->id_peminjaman_asset)->first();

            $log_peminjaman_asset = new LogPeminjamanAsset();
            $log_peminjaman_asset->peminjaman_asset_id = $peminjaman_asset->id;
            $log_peminjaman_asset->created_by = Auth::user()->id;
            $log_peminjaman_asset->log_message = 'Asset ' . $asset->deskripsi . ' dengan kode asset ' . $asset->kode_asset . ' telah dihapus dari daftar peminjaman dikarenakan Asset dihapus oleh Admin';
            $log_peminjaman_asset->save();

            $detail_peminjaman_asset->delete();
        }

        // Remove Detail Pemutihan Asset
        $detail_pemutihan_assets = DetailPemutihanAsset::where('id_asset_data', $asset->id)->get();
        foreach ($detail_pemutihan_assets as $detail_pemutihan_asset) {
            $detail_pemutihan_asset->delete();
        }

        // Remove Detail Service
        $detail_services = DetailService::where('id_asset_data', $asset->id)->get();
        foreach ($detail_services as $detail_service) {
            $service = Service::where('id', $detail_service->id_service)->first();
            $log_services = LogServiceAsset::where('id_service', $service->id)->get();
            foreach ($log_services as $log_service) {
                $log_service->delete();
            }
            $service->delete();
            $detail_service->delete();
        }

        // Remove Log Asset
        $log_assets = LogAsset::where('asset_id', $asset->id)->get();
        foreach ($log_assets as $log_asset) {
            $log_asset->delete();
        }

        // Remove Log Asset Opname
        $log_opnames = LogAssetOpname::where('id_asset_data', $asset->id)->get();
        foreach ($log_opnames as $log_opname) {
            $log_opname->delete();
        }

        // Remove Pengaduan dan Log Pengaduan Asset
        $pengaduans = Pengaduan::where('id_asset_data', $asset->id)->get();
        foreach ($pengaduans as $pengaduan) {
            $log_pengaduans = LogPengaduanAsset::where('id_pengaduan', $pengaduan->id)->get();
            foreach ($log_pengaduans as $log_pengaduan) {
                $log_pengaduan->delete();
            }
            $pengaduan->delete();
        }

        // Remove Perencanaan Services
        $perencanaan_services = PerencanaanServices::where('id_asset_data', $asset->id)->get();
        foreach ($perencanaan_services as $perencanaan_service) {
            $perencanaan_service->delete();
        }

        return $asset->delete();
    }

    public function publishAssetMany(AssetDataPublishRequest $request)
    {
        $id_asset = json_decode($request->json_id_asset_selected);
        foreach ($id_asset as $id) {
            $asset = AssetData::find($id);
            if (isset($asset)) {
                $asset->is_draft = '0';
                if(isset($asset->tgl_pelunasan)){ // kondisi ini ditambahkan oleh wahyu
                    //original
                    $asset->nilai_buku_asset = DepresiasiHelpers::storePastDepresiasiAsset($asset, $asset->tanggal_awal_depresiasi);
                }else{
                    $asset->nilai_buku_asset = $asset->nilai_perolehan; // ditambahkan oleh wahyu
                }
                
                $asset->save();
            }
        }

        return true;
    }

    public function deleteAssetMany(AssetDataDeleteRequest $request)
    {
        $id_asset = json_decode($request->json_id_asset_selected);
        foreach ($id_asset as $id) {
            $asset = AssetData::find($id);
            if ($asset) {
                $findImage = AssetImage::where('imageable_id', $asset->id)->get();
                foreach ($findImage as $image) {
                    $path = storage_path('app/images/asset');
                    $pathOld = $path . '/' . $image->path;
                    FileHelpers::removeFile($pathOld);
                    $image->delete();
                }
                $logs = LogAsset::where('asset_id', $asset->id)->get();
                foreach ($logs as $log) {
                    $log->delete();
                }
                $asset->forceDelete();
            }
        }

        return true;
    }

    public function publishAllDraftAsset()
    {
        $query = AssetData::where('is_pemutihan', '0')->where('is_draft', '1')->get();
        foreach ($query as $data) {
            $data->is_draft = '0';
            if(isset($data->tgl_pelunasan)){ //kondisi ini ditambahkan oleh wahyu
                //original
                $data->nilai_buku_asset = DepresiasiHelpers::storePastDepresiasiAsset($data, $data->tanggal_awal_depresiasi);
            }else{
                $data->nilai_buku_asset = $data->nilai_perolehan; // ditambahkan oleh wahyu
            }
            
            $data->save();
        }
        return $query;
    }

    public function deleteAllDraftAsset()
    {
        $query = AssetData::where('is_pemutihan', '0')->where('is_draft', '1')->get();
        foreach ($query as $data) {
            $findImage = AssetImage::where('imageable_id', $data->id)->get();
            foreach ($findImage as $image) {
                $path = storage_path('app/images/asset');
                $pathOld = $path . '/' . $image->path;
                FileHelpers::removeFile($pathOld);
                $image->delete();
            }
            $logs = LogAsset::where('asset_id', $data->id)->get();
            foreach ($logs as $log) {
                $log->delete();
            }
            $data->forceDelete();
        }
        return $query;
    }

    public function updateImageAsset(Request $request, string $id)
    {
        $asset = AssetData::find($request->id_asset);
        $findImage = AssetImage::where('id', $id)->where('imageable_id', $asset->id)->where('imageable_type', 'App\\Models\\AssetData')->first();
        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($findImage)) {
                $pathOld = $path . '/' . $findImage->path;
                FileHelpers::removeFile($pathOld);
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $findImage->path = $filenamesave;
            $findImage->save();
        }
    }

    public function storeImageAsset(Request $request)
    {
        $asset = AssetData::find($request->id_asset);
        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }

    public function deleteImageAsset(string $id)
    {
        $findImage = AssetImage::find($id);
        $path = storage_path('app/images/asset');
        if (isset($findImage)) {
            $pathOld = $path . '/' . $findImage->path;
            FileHelpers::removeFile($pathOld);
        }
        $findImage->delete();
    }
}
