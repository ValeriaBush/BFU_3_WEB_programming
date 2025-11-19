<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>🧪 Тест настройки Lab 7</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        .test-section { margin: 20px 0; padding: 15px; border-left: 4px solid #ccc; }
    </style>
</head>
<body>
    <h1>🧪 Тестирование настройки Lab 7 (Kafka)</h1>";

// Тест 1: Базовая проверка PHP
echo "<div class='test-section'>
        <h2>1. ✅ Базовая проверка PHP</h2>";
echo "Версия PHP: <strong>" . PHP_VERSION . "</strong><br>";
echo "Расширения: ";
$extensions = ['pdo_mysql', 'json', 'mbstring'];
foreach ($extensions as $ext) {
    echo extension_loaded($ext) ? "<span class='success'>$ext </span>" : "<span class='error'>$ext </span>";
}
echo "</div>";

// Тест 2: Проверка MySQL
echo "<div class='test-section'>
        <h2>2. 🐬 Тест подключения к MySQL</h2>";
try {
    require_once 'db.php';
    echo "<span class='success'>✅ MySQL подключен успешно</span><br>";
    echo "База данных: lab7_db<br>";
    
    // Проверяем существование таблицы
    $stmt = $pdo->query("SHOW TABLES LIKE 'food_orders'");
    if ($stmt->fetch()) {
        echo "<span class='success'>✅ Таблица food_orders существует</span><br>";
    } else {
        echo "<span class='warning'>⚠️ Таблица food_orders не существует</span><br>";
        echo "<a href='create-table.php'>Создать таблицу</a><br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Ошибка MySQL: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Тест 3: Проверка Composer и зависимостей
echo "<div class='test-section'>
        <h2>3. 📦 Тест Composer и зависимостей</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "<span class='success'>✅ Composer autoload работает</span><br>";
        
        // Проверяем Kafka библиотеки
        $kafkaClasses = [
            'Kafka\Producer' => 'Producer',
            'Kafka\Consumer' => 'Consumer', 
            'Kafka\ProducerConfig' => 'ProducerConfig',
            'Kafka\ConsumerConfig' => 'ConsumerConfig'
        ];
        
        foreach ($kafkaClasses as $class => $name) {
            if (class_exists($class)) {
                echo "<span class='success'>✅ $name найден</span><br>";
            } else {
                echo "<span class='error'>❌ $name не найден</span><br>";
            }
        }
    } else {
        echo "<span class='error'>❌ vendor/autoload.php не существует</span><br>";
        echo "Запустите: <code>composer install</code> в контейнере PHP<br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Ошибка Composer: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Тест 4: Проверка QueueManager
echo "<div class='test-section'>
        <h2>4. 📨 Тест QueueManager (Kafka)</h2>";
try {
    if (file_exists('QueueManager.php')) {
        require_once 'QueueManager.php';
        $queue = new QueueManager();
        echo "<span class='success'>✅ QueueManager загружен успешно</span><br>";
        echo "Топик: lab7_orders<br>";
        echo "Создание топика: " . $queue->createTopic() . "<br>";
    } else {
        echo "<span class='error'>❌ QueueManager.php не найден</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Ошибка QueueManager: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Тест 5: Проверка контейнеров
echo "<div class='test-section'>
        <h2>5. 🐳 Тест Docker контейнеров</h2>";
$containers = [
    'lab7_nginx' => 'Nginx (веб-сервер)',
    'lab7_php' => 'PHP-FPM',
    'lab7_mysql' => 'MySQL',
    'lab7_zookeeper' => 'Zookeeper',
    'lab7_kafka' => 'Kafka'
];

foreach ($containers as $container => $description) {
    $output = shell_exec("docker ps --filter name=$container --format '{{.Names}}'");
    if (trim($output) === $container) {
        echo "<span class='success'>✅ $description запущен ($container)</span><br>";
    } else {
        echo "<span class='error'>❌ $description не запущен ($container)</span><br>";
    }
}
echo "</div>";

// Итоги
echo "<div class='test-section'>
        <h2>🎯 Итоги проверки</h2>";

$allTests = [
    'PHP' => extension_loaded('pdo_mysql'),
    'MySQL' => class_exists('PDO'),
    'Composer' => file_exists('vendor/autoload.php'),
    'Kafka Classes' => class_exists('Kafka\Producer'),
    'QueueManager' => file_exists('QueueManager.php')
];

$passed = count(array_filter($allTests));
$total = count($allTests);

echo "Пройдено тестов: <strong>$passed из $total</strong><br>";

if ($passed === $total) {
    echo "<h3 class='success'>🎉 Все тесты пройдены! Можно переходить к следующему шагу.</h3>";
} else {
    echo "<h3 class='warning'>⚠️ Некоторые тесты не пройдены. Проверьте настройки.</h3>";
}

echo "</div>";

echo "<hr>
<h3>📝 Инструкция по проверке:</h3>
<ol>
    <li>Запустите контейнеры: <code>docker-compose up -d</code></li>
    <li>Откройте в браузере: <a href='http://localhost:8080/test-setup.php'>http://localhost:8080/test-setup.php</a></li>
    <li>Если есть ошибки - проверьте логи: <code>docker-compose logs</code></li>
    <li>Для установки зависимостей: <code>docker exec -it lab7_php composer install</code></li>
</ol>";

echo "</body></html>";
?>