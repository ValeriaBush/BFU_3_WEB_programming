<?php
require_once 'db.php';

try {
    echo "<h1>Тест подключения к БД</h1>";
    
    // Проверяем подключение
    echo "<p style='color: green;'>✅ Подключение к MySQL успешно!</p>";
    
    // Проверяем существование таблицы food_orders
    $stmt = $pdo->query("SHOW TABLES LIKE 'food_orders'");
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        echo "<p style='color: green;'>✅ Таблица 'food_orders' существует</p>";
        
        // Показываем количество записей
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM food_orders");
        $count = $stmt->fetch()['count'];
        echo "<p>Количество заказов в таблице: $count</p>";
        
        // Показываем последние заказы
        $stmt = $pdo->query("SELECT * FROM food_orders ORDER BY order_time DESC LIMIT 5");
        $orders = $stmt->fetchAll();
        
        if ($orders) {
            echo "<h3>Последние заказы:</h3>";
            echo "<ul>";
            foreach ($orders as $order) {
                $sauce = $order['sauce'] ? '✅ С соусом' : '❌ Без соуса';
                echo "<li>{$order['name']} - {$order['dish']} ({$order['portions']} порций) - {$order['delivery_type']} - $sauce</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Таблица 'food_orders' не существует. <a href='create-table.php'>Создать таблицу</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h1 style='color: red;'>❌ Ошибка: " . $e->getMessage() . "</h1>";
}
?>

<br>
<a href="create-table.php">Создать таблицу</a> | 
<a href="index.php">На главную</a>