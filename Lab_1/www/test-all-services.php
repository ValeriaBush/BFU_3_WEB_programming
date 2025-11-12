<?php
require_once __DIR__ . '/vendor/autoload.php';

// Временно подключаем классы напрямую
require_once __DIR__ . '/App/RedisExample.php';
require_once __DIR__ . '/App/ElasticExample.php';
require_once __DIR__ . '/App/ClickhouseExample.php';
require_once __DIR__ . '/App/Helpers/ClientFactory.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

echo "<h1>🧪 Тестирование всех сервисов Lab 6</h1>";

// 1. Тестирование Redis через REST API
echo "<h2>🔴 Redis Test (Temporarily disabled - fixing API)</h2>";
echo "<p style='color: orange;'>⚠️ Redis API настройка в процессе...</p>";

// 2. Тестирование Elasticsearch
echo "<h2>📊 Elasticsearch Test</h2>";
try {
    $elastic = new ElasticExample();
    
    // Создаем индекс и документ
    $indexResult = $elastic->indexDocument('books', 1, [
        'title' => '1984', 
        'author' => 'George Orwell',
        'year' => 1949
    ]);
    echo "<p style='color: green;'>✅ Document indexed</p>";
    
    // Поиск документа
    $searchResult = $elastic->search('books', ['author' => 'Orwell']);
    echo "<p style='color: green;'>✅ Search completed</p>";
    echo "<pre>" . $searchResult . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Elasticsearch Error: " . $e->getMessage() . "</p>";
}

// 3. Тестирование Clickhouse
echo "<h2>🐡 Clickhouse Test</h2>";
try {
    $click = new ClickhouseExample();
    
    // Простой запрос
    $queryResult = $click->query('SELECT version() as version');
    echo "<p style='color: green;'>✅ Query executed: $queryResult</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Clickhouse Error: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php'>← На главную</a>";