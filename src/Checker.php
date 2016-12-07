<?php

namespace Checker;

use GuzzleHttp\ClientInterface;

class Checker
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $searchString;

    public function __construct(ClientInterface $client, string $url, string $searchString)
    {
        $this->client = $client;
        $this->url = $url;
        $this->searchString = $searchString;
    }

    /**
     * @return int
     */
    public function checkPrices(): int
    {
        $request = $this->client->request("get", $this->url);
        $content = $request->getBody()->getContents();
        preg_match_all(
            $this->searchString,
            $content,
            $output
        );
        $price = 0;
        if (isset($output[1][0])) {
            $price = $output[1][0];
        }

        return $price;
    }

}
