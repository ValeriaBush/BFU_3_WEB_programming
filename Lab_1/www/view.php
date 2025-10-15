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
        
        <h2>История заказов:</h2>
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
                    echo "<li class='no-data'>Данных нет</li>";
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