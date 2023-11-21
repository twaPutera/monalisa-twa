<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\PeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UserNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\UserPeminjamanDueDateNotifMail;

class PeminjamanDueDateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_peminjaman;
    protected $date;

    /**
     * Create a new job instance.
     *
     * @param mixed $id_peminjaman
     * @param mixed $date
     *
     * @return void
     */
    public function __construct($id_peminjaman, $date)
    {
        $this->id_peminjaman = $id_peminjaman;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $peminjaman = PeminjamanAsset::find($this->id_peminjaman);
        $tanggal_pengembalian = $peminjaman->tanggal_pengembalian . ' ' . $peminjaman->jam_selesai;
        logger('PeminjamanDueDateJob: ', [
            'id_peminjaman' => $this->id_peminjaman,
            'tanggal_pengembalian' => $tanggal_pengembalian,
            'date' => $this->date,
        ]);
        if ($this->date == $tanggal_pengembalian && $peminjaman->status != 'selesai') {
            $peminjaman->status = 'duedate';
            $peminjaman->save();

            $notifikasi = [
                'title' => 'Peminjaman Asset',
                'message' => 'Peminjaman Asset dengan kode ' . $peminjaman->code . ' telah melewati batas waktu pengembalian.',
                'url' => route('user.asset-data.peminjaman.index') . '?peminjaman_asset_id=' . $peminjaman->id,
                'date' => date('d/m/Y H:i'),
            ];
            $peminjam = User::find($peminjaman->guid_peminjam_asset);
            $peminjam->notify(new UserNotification($notifikasi));
            $detail_peminjaman_asset = DetailPeminjamanAsset::where('id_peminjaman_asset', $this->id_peminjaman)->get();
            $peminjam->notify(new UserPeminjamanDueDateNotifMail($peminjaman, $detail_peminjaman_asset));
        }
    }
}
