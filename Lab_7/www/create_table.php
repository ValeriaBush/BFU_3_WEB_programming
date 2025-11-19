<?php
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Создание таблицы - Lab 7</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; }
        .error { color: red; }
        table { border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🗃️ Создание таблицы food_orders</h1>";

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS food_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            portions INT NOT NULL,
            dish VARCHAR(50) NOT NULL,
            sauce TINYINT(1) DEFAULT 0,
            delivery_type VARCHAR(50) NOT NULL,
            delivery_date DATE NOT NULL,
            order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            processed_time TIMESTAMP NULL,
            status ENUM('pending', 'processing', 'completed') DEFAULT 'pending'
        )
    ");
    
    echo "<p class='success'>✅ Таблица 'food_orders' создана успешно!</p>";
    
    // Показываем структуру таблицы
    $stmt = $pdo->query("DESCRIBE food_orders");
    $columns = $stmt->fetchAll();
    
    echo "<h3>Структура таблицы:</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Ошибка создания таблицы: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php'>← На главную</a>";
echo "</body></html>";
?>