<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UserServices
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
     * @param array $arrayData
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function postOneNewUser(array $arrayData): ResponseInterface
    {
        $link = Constants::API_LINK . "/register";
        return $this -> httpClient -> request(
            'POST',
            $link,
            [
                'headers' => ['Content-Type' => 'application/json',],
                'json' => $arrayData,
            ]);
    }


}