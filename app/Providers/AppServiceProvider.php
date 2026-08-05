<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->environment('local')) {
            $stream = new SocketStream();
            $stream->setStreamOptions([
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ],
            ]);

            $transport = new EsmtpTransport(
                host: config('mail.mailers.smtp.host'),
                port: (int) config('mail.mailers.smtp.port'),
                tls: true,
                stream: $stream,
            );

            $transport->setUsername(config('mail.mailers.smtp.username'));
            $transport->setPassword(config('mail.mailers.smtp.password'));

            Mail::setSymfonyTransport($transport);
        }
    }
}