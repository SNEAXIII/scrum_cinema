<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ReservationService
{
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this -> httpClient = $httpClient;
    }

    /**
     * @param string $token
     * @param int $idSeance
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function postReserverUnFilm(string $token,int $idSeance): ResponseInterface
    {
        $link = Constants::API_LINK . "/reserver";
        return $this -> httpClient -> request(
            'POST',
            $link,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer $token"
                ],
                'json' => ["id"=>$idSeance],
            ]
        );
    }
}