<?php

namespace App\Mail;

use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class VpnSafeMailManager extends MailManager
{
    protected function configureSmtpTransport(EsmtpTransport $transport, array $config)
    {
        $transport = parent::configureSmtpTransport($transport, $config);
        $stream = $transport->getStream();
        $peer = $config['peer_name'] ?? null;
        if ($stream instanceof SocketStream && is_string($peer) && $peer !== '') {
            $options = $stream->getStreamOptions();
            $options['ssl']['peer_name'] = $peer;
            $stream->setStreamOptions($options);
        }

        return $transport;
    }
}
