<?php
require_once 'db.php';

try {
    // Создаём таблицу для заказов еды
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
            order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "<h1 style='color: green;'>✅ Таблица 'food_orders' создана успешно!</h1>";
    
    // Проверяем структуру таблицы
    $stmt = $pdo->query("DESCRIBE food_orders");
    $columns = $stmt->fetchAll();
    
    echo "<h3>Структура таблицы:</h3>";
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
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
    echo "<h1 style='color: red;'>❌ Ошибка создания таблицы: " . $e->getMessage() . "</h1>";
}
?>

<br>
<a href="index.php">← На главную</a>
<a href="test-db.php">Проверить подключение</a>