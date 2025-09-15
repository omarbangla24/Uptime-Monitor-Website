<?php

namespace App\Notifications;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteDownNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Website $website,
        public string $errorMessage = ''
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
                    ->subject('Website Down Alert: ' . $this->website->name)
                    ->line('Your website ' . $this->website->name . ' appears to be down.')
                    ->line('URL: ' . $this->website->url)
                    ->line('Error: ' . $this->errorMessage)
                    ->action('View Website', route('websites.show', $this->website))
                    ->line('We will continue monitoring and notify you when it\'s back online.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'website_id' => $this->website->id,
            'website_name' => $this->website->name,
            'website_url' => $this->website->url,
            'error_message' => $this->errorMessage,
            'type' => 'website_down',
        ];
    }
}
