<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все данные</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Все сохранённые данные</h1>
        
        <h2>История заказов из MySQL:</h2>
        <?php
        require_once 'db.php';
        require_once 'FoodOrder.php';
        
        try {
            $foodOrder = new FoodOrder($pdo);
            $orders = $foodOrder->getAll();
            
            if(!empty($orders)) {
                echo '<ul class="orders-list">';
                foreach($orders as $order) {
                    $sauceText = $order['sauce'] ? '✅ С соусом' : '❌ Без соуса';
                    echo "<li>";
                    echo "<strong>{$order['name']}</strong> ({$order['email']})";
                    echo "<div class='order-details'>";
                    echo "Порций: {$order['portions']} | Блюдо: {$order['dish']} | Дата доставки: {$order['delivery_date']}<br>";
                    echo "Соус: $sauceText | Тип доставки: {$order['delivery_type']} | Время заказа: {$order['order_time']}";
                    echo " | <strong>MySQL ID: {$order['id']}</strong>";
                    echo "</div>";
                    echo "</li>";
                }
                echo '</ul>';
            } else {
                echo "<li class='no-data'>В MySQL данных нет</li>";
            }
        } catch (PDOException $e) {
            echo "<li class='no-data'>Ошибка подключения к MySQL: " . $e->getMessage() . "</li>";
        }
        ?>
        
        <h2>История заказов из файла (старая версия):</h2>
        <ul class="orders-list">
            <?php
            if(file_exists("data.txt")){
                $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
                if(!empty($lines)) {
                    foreach($lines as $index => $line){
                        $data = explode(";", $line);
                        if(count($data) >= 8) {
                            list($name, $email, $portions, $dish, $deliveryDate, $sauce, $deliveryType, $timestamp) = $data;
                            echo "<li>";
                            echo "<strong>$name</strong> ($email)";
                            echo "<div class='order-details'>";
                            echo "Порций: $portions | Блюдо: $dish | Дата доставки: $deliveryDate<br>";
                            echo "Соус: $sauce | Тип доставки: $deliveryType | Время заказа: $timestamp";
                            echo "</div>";
                            echo "</li>";
                        } else {
                            echo "<li>Неверный формат данных в строке " . ($index + 1) . "</li>";
                        }
                    }
                } else {
                    echo "<li class='no-data'>В файле данных нет</li>";
                }
            } else {
                echo "<li class='no-data'>Файл с данными не найден</li>";
            }
            ?>
        </ul>

        <div class="links">
            <a href="index.php">На главную</a>
            <a href="form.html">Сделать новый заказ</a>
        </div>
    </div>
</body>
</html>