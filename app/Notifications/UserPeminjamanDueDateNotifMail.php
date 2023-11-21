<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserPeminjamanDueDateNotifMail extends Notification
{
    use Queueable;

    protected $peminjaman;
    protected $detail_peminjaman_asset;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @param mixed $peminjaman
     * @param mixed $detail_peminjaman_asset
     *
     * @return void
     */
    public function __construct($peminjaman, $detail_peminjaman_asset)
    {
        $this->peminjaman = $peminjaman;
        $this->detail_peminjaman_asset = $detail_peminjaman_asset;
        $this->url = route('user.asset-data.peminjaman.index') . '?peminjaman_asset_id=' . $this->peminjaman->id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Keterlambatan Peminjaman Aset')
            ->markdown('layouts.mails.peminjaman-duedate', [
                'peminjaman' => $this->peminjaman,
                'detail_peminjaman_asset' => $this->detail_peminjaman_asset,
                'url' => $this->url,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'peminjaman' => $this->peminjaman,
        ];
    }
}
