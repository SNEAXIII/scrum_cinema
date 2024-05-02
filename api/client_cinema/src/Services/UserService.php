<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UserService
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
            ]
        );
    }

    /**
     * @param array $arrayData
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function postLoginCheck(array $arrayData): ResponseInterface
    {
        $link = Constants::API_LINK . "/login_check";
        return $this -> httpClient -> request(
            'POST',
            $link,
            [
                'headers' => ['Content-Type' => 'application/json',],
                'json' => $arrayData,
            ]
        );
    }

    /**
     * @param string $token
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function postLoginTest(string $token): ResponseInterface
    {
        $link = Constants::API_LINK . "/logintest";
        return $this -> httpClient -> request(
            'GET',
            $link,
            [
                'headers' => ['Authorization' => "Bearer $token"]
            ]
        );
    }
}