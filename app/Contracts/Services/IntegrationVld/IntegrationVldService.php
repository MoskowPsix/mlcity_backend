<?php

namespace App\Contracts\Services\IntegrationVld;

use GuzzleHttp\Client;

class IntegrationVldService implements IntegrationVldServiceInterface
{
    private string $url = 'http://178.250.157.85:9200';
    private string $scroll = "60m";
    public function getFirstScroll(): object | null
    {
        try {
            $client = new Client();
            $url = "$this->url/events/_search/";
            $params = [
                    "query" => [
                        "scroll" => $this->scroll,
                    ],
            ];
            $response = $client->request('POST', $url, $params);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getNextScroll(string $scroll_id): object | null
    {
        try {
            $client = new Client();
            $url = "$this->url/_search/scroll";
            $params = [
                "query" => [
                        "scroll_id" => $scroll_id,
                        "scroll" => $this->scroll
                    ],
            ];
            $response = $client->request('POST', $url, $params);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }
}
