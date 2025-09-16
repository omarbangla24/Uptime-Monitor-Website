<?php

namespace App\Notifications;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SSLCertificateExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $website;
    protected $sslInfo;

    public function __construct(Website $website, array $sslInfo)
    {
        $this->website = $website;
        $this->sslInfo = $sslInfo;
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
        $daysLeft = $this->sslInfo['days_until_expiry'];
        $expiryDate = $this->sslInfo['valid_to']->format('M j, Y');

        return (new MailMessage)
            ->warning()
            ->subject("🔒 SSL Certificate Expiring: {$this->website->name}")
            ->greeting("SSL Certificate Alert")
            ->line("The SSL certificate for **{$this->website->name}** is expiring soon.")
            ->line("**Domain:** {$this->sslInfo['domain']}")
            ->line("**Issuer:** {$this->sslInfo['issuer']}")
            ->line("**Expires:** {$expiryDate} ({$daysLeft} days)")
            ->when($this->sslInfo['validation_errors'], function($mail) {
                return $mail->line("**Issues:** {$this->sslInfo['validation_errors']}");
            })
            ->action('View SSL Details', route('websites.show', $this->website))
            ->line('Please renew your SSL certificate before it expires to avoid service interruption.')
            ->salutation("Best regards,\nUptimeMonitor Team");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'ssl_expiring',
            'website_id' => $this->website->id,
            'website_name' => $this->website->name,
            'domain' => $this->sslInfo['domain'],
            'expires_at' => $this->sslInfo['valid_to']->toISOString(),
            'days_until_expiry' => $this->sslInfo['days_until_expiry'],
            'issuer' => $this->sslInfo['issuer'],
        ];
    }
}
