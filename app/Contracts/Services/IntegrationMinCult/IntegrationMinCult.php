<?php

namespace App\Contracts\Services\IntegrationMinCult;

use App\Contracts\Services\IntegrationMinCult\IntegrationMinCultInterface;
use Illuminate\Support\Carbon;

class IntegrationMinCult implements IntegrationMinCultInterface
{
    public function getTotal(): int
    {
        $response =  json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"'.Carbon::now()->format('Y-m-d').'"}}&l=1', true, $this->getHeader()));
        return $response->total;
    }
    public function getEvents(int $limit, int $offset): array
    {
        $response = json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"'.Carbon::now()->format('Y-m-d').'"}}&l=' . $limit . '&s=' . $offset, true, $this->getHeader()));
        return $response->data;
    }
    private function getHeader()
    {
        // Create a stream
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-API-KEY: " . env('API_KEY_MIN_CULT') . "\r\n"
            ]
        ];
        return stream_context_create($opts);
    }
}
