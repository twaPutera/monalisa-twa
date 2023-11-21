<?php

namespace App\Services\Notification;

use App\Models\Notification;

class NotificationCommandServices
{
    public function readNotification($id)
    {
        $notification = Notification::find($id);
        $notification->read_at = date('Y-m-d H:i:s');
        $notification->save();

        return $notification;
    }
}
