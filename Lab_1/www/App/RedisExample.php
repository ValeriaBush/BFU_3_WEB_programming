<?php

namespace App;

use App\Helpers\ClientFactory;

class RedisExample
{
    private $client;

    public function __construct()
    {
        // Redis Commander работает на порту 8081 внутри контейнера
        $this->client = ClientFactory::make('http://redis-commander:8081/');
    }

    public function setValue($key, $value)
    {
        // Правильный endpoint для Redis Commander API
        $response = $this->client->request('GET', "/api/set/$key/$value");
        return $response->getBody()->getContents();
    }

    public function getValue($key)
    {
        $response = $this->client->request('GET', "/api/get/$key");
        return $response->getBody()->getContents();
    }

    // Дополнительные методы для тестирования
    public function getAllKeys()
    {
        $response = $this->client->request('GET', '/api/keys');
        return $response->getBody()->getContents();
    }

    public function deleteKey($key)
    {
        $response = $this->client->request('GET', "/api/del/$key");
        return $response->getBody()->getContents();
    }
}