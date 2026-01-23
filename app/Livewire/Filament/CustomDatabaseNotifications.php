<?php

namespace App\Livewire\Filament;

use Filament\Notifications\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Filament\Notifications\Notification;
use Illuminate\Notifications\DatabaseNotification;

class CustomDatabaseNotifications extends BaseDatabaseNotifications
{
    public static ?string $pollingInterval = '30s';

    public function getNotification(DatabaseNotification $notification): Notification
    {
        $filamentNotification = Notification::fromDatabase($notification)
            ->date($this->formatNotificationDate($notification->getAttributeValue('created_at')));

        // If the notification is already read, make its duration persistent
        if ($notification->read_at !== null) {
            $filamentNotification->duration('persistent');
        }

        return $filamentNotification;
    }
}
