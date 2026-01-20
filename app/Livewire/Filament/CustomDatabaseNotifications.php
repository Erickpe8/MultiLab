<?php

namespace App\Livewire\Filament;

use Filament\Notifications\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Illuminate\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;

class CustomDatabaseNotifications extends BaseDatabaseNotifications
{
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