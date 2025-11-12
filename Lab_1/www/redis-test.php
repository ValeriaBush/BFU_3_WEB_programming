<?php
require_once __DIR__ . '/vendor/autoload.php';

// Явно подключаем необходимые классы
require_once __DIR__ . '/App/Helpers/ClientFactory.php';

use App\Helpers\ClientFactory;

echo "<h1>🧪 Тестирование Redis Commander</h1>";

try {
    // Проверяем доступность Redis Commander
    $client = ClientFactory::make('http://redis-commander:8081/');
    
    $response = $client->get('/');
    echo "<p style='color: green;'>✅ Redis Commander доступен (Status: " . $response->getStatusCode() . ")</p>";
    
    // Тестируем API endpoints
    echo "<h2>Тестирование API endpoints:</h2>";
    
    // Тест 1: Получение списка ключей
    try {
        $keysResponse = $client->get('/api/keys');
        echo "<p style='color: green;'>✅ /api/keys - работает</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ /api/keys - ошибка: " . $e->getMessage() . "</p>";
    }
    
    // Тест 2: Установка значения
    try {
        $testKey = 'test_key_' . time();
        $testValue = 'test_value_' . uniqid();
        $setResponse = $client->get("/api/set/$testKey/$testValue");
        echo "<p style='color: green;'>✅ /api/set - работает</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ /api/set - ошибка: " . $e->getMessage() . "</p>";
    }
    
    // Тест 3: Получение значения
    try {
        $getResponse = $client->get("/api/get/$testKey");
        $value = $getResponse->getBody()->getContents();
        echo "<p style='color: green;'>✅ /api/get - работает: $value</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ /api/get - ошибка: " . $e->getMessage() . "</p>";
    }
    
    echo "<h3>Доступные API endpoints Redis Commander:</h3>";
    echo "<ul>";
    echo "<li><strong>GET /api/keys</strong> - список всех ключей</li>";
    echo "<li><strong>GET /api/get/{key}</strong> - получить значение ключа</li>";
    echo "<li><strong>GET /api/set/{key}/{value}</strong> - установить значение</li>";
    echo "<li><strong>GET /api/del/{key}</strong> - удалить ключ</li>";
    echo "<li><strong>GET /api/type/{key}</strong> - тип значения</li>";
    echo "<li><strong>GET /api/ttl/{key}</strong> - время жизни ключа</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Ошибка подключения к Redis Commander: " . $e->getMessage() . "</p>";
    echo "<p>Проверьте:</p>";
    echo "<ul>";
    echo "<li>Запущен ли контейнер redis-commander</li>";
    echo "<li>Доступен ли он по http://redis-commander:8081/</li>";
    echo "<li>Логи контейнера: <code>docker logs lab6_redis_commander</code></li>";
    echo "</ul>";
}

echo "<br><a href='index.php'>← На главную</a>";