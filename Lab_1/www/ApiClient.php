<?php
require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;
use App\Helpers\ClientFactory;

class ApiClient {
    private Client $client;

    public function __construct() {
        $this->client = ClientFactory::make('https://www.themealdb.com/api/json/v1/1/');
    }

    public function request(string $url): array {
        try {
            $response = $this->client->get($url);
            $body = $response->getBody()->getContents();
            
            // Пробуем декодировать JSON
            $decoded = json_decode($body, true);
            
            // Если не JSON, возвращаем как текст
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['response' => $body, 'content_type' => 'text'];
            }
            
            return $decoded ?: ['response' => $body];
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Специальный метод для Clickhouse (возвращает текст)
     */
    public function requestText(string $url): string {
        try {
            $response = $this->client->get($url);
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Специальный метод для Clickhouse POST запросов
     */
    public function queryClickhouse(string $sql): string {
        try {
            $client = ClientFactory::make('http://clickhouse:8123/');
            $response = $client->post('', [
                'body' => $sql,
                'headers' => [
                    'Content-Type' => 'text/plain'
                ]
            ]);
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}