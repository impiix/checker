<?php

namespace Checker;

use GuzzleHttp\Client;

class Bootstrap
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Bootstrap constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config['parameters'];
    }

    public function getWatcher(): Watcher
    {
        $watcher = new Watcher(
            $this->getMailer(),
            $this->getMailerMessage(),
            new Checker(
                new Client(),
                $this->config['url'],
                $this->config['search_string']
            )
        );

        return $watcher;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        $config = $this->config['mail'];

        $transport = new \Swift_SmtpTransport($config['host'], $config['port'], $config['security']);
        $transport->setUsername($config['username']);
        $transport->setPassword($config['password']);

        $service = new \Swift_Mailer($transport);

        return $service;
    }

    /**
     * @return \Swift_Message
     */
    public function getMailerMessage(): \Swift_Message
    {
        $config = $this->config['mail'];
        $message = new \Swift_Message("Uprast message.");
        $message->setFrom($config['username']);
        $message->setTo($config['to']);

        return $message;
    }
}
