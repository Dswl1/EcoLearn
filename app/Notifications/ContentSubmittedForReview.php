<?php

namespace App\Notifications;

use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentSubmittedForReview extends Notification
{
    use Queueable;

    public function __construct(
        public Content $content,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'content_submitted',
            'content_id' => $this->content->id,
            'content_title' => $this->content->title,
            'author_name' => $this->content->user->name,
            'message_en' => 'Content "'.$this->content->title.'" submitted by '.$this->content->user->name.' for review.',
            'message_id' => 'Materi "'.$this->content->title.'" dikirim oleh '.$this->content->user->name.' untuk ditinjau.',
        ];
    }
}
