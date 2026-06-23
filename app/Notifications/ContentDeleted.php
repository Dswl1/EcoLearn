<?php

namespace App\Notifications;

use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentDeleted extends Notification
{
    use Queueable;

    public function __construct(
        public Content $content,
        public string $title,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'content_deleted',
            'content_id' => $this->content->id,
            'content_title' => $this->title,
            'message_en' => str_replace(':title', $this->title, 'Your content ":title" has been removed by admin.'),
            'message_id' => str_replace(':title', $this->title, 'Konten Anda ":title" telah dihapus oleh admin.'),
        ];
    }
}
