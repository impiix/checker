<?php
/**
 * Date: 3/25/16
 * Time: 8:15 PM
 */
namespace Checker;

/**
 * Class Watcher
 */
class Watcher
{
    /**
     * @var Checker
     */
    protected $checker;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Swift_Message
     */
    protected $message;

    public function __construct( \Swift_Mailer $mailer, \Swift_Message $message, Checker $checker)
    {
        $this->message = $message;
        $this->mailer = $mailer;
        $this->checker = $checker;
    }

    public function run()
    {
        $text = "";
        $text .= $this->checkPrices();

        if ($text) {
            $this->send($text);
        }
    }

    /**
     * @return string
     */
    protected function checkPrices(): string
    {
        $price = $this->checker->checkPrices();

        $text = "";
        $filename = "./eve_price";

        if (!file_exists($filename) || ($oldPrice = file_get_contents($filename)) === false || $price != $oldPrice) {
            fopen($filename, "w");
            file_put_contents($filename, $price);
            $text = sprintf("There is new price for the route you observe: %d.\n", $price);
        }

        return $text;
    }

    /**
     * @param string $text
     */
    protected function send(string $text)
    {
        $this->message->setBody($text, 'text/plain');
        $this->mailer->send($this->message);
    }
}
