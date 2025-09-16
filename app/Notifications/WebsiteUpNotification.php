<?php

namespace App\Notifications;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $website;
    protected $responseTime;

    public function __construct(Website $website, $responseTime = null)
    {
        $this->website = $website;
        $this->responseTime = $responseTime;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->hasFeature('email_alerts')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject("✅ Website Back Online: {$this->website->name}")
            ->greeting("Website Restored")
            ->line("Great news! Your website **{$this->website->name}** is back online.")
            ->line("**URL:** {$this->website->url}")
            ->line("**Domain:** {$this->website->domain}")
            ->when($this->responseTime, function($mail) {
                return $mail->line("**Response Time:** {$this->responseTime}ms");
            })
            ->line("**Time:** " . now()->format('M j, Y \a\t g:i A T'))
            ->action('View Website Details', route('websites.show', $this->website))
            ->line('Your website is now responding normally.')
            ->salutation("Best regards,\nUptimeMonitor Team");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'website_up',
            'website_id' => $this->website->id,
            'website_name' => $this->website->name,
            'website_url' => $this->website->url,
            'response_time' => $this->responseTime,
            'checked_at' => now()->toISOString(),
        ];
    }
}
