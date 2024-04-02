<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FilmServices
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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAllFilms(): array
    {
        $link = Constants::API_LINK . "/films";
        $reponse = $this -> httpClient -> request("GET", $link) -> toArray();
        return $reponse;
    }

    public function getOneFilms(int $id): array
    {
        $link = Constants::API_LINK . "/films/$id";
        $reponse = $this -> httpClient -> request("GET", $link) -> toArray();
        return $reponse;
    }

}