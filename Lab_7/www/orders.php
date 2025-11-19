<?php
session_start();
require_once 'db.php';
require_once 'FoodOrder.php';

try {
    $foodOrder = new FoodOrder($pdo);
    $orders = $foodOrder->getAll();
    $stats = $foodOrder->getDishStats();
} catch (Exception $e) {
    $error = "Ошибка при загрузке заказов: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы - Lab 7</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .orders-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .orders-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .orders-table tr:hover {
            background-color: #f5f5f5;
        }
        .status-pending { color: #f39c12; font-weight: bold; }
        .status-processing { color: #3498db; font-weight: bold; }
        .status-completed { color: #27ae60; font-weight: bold; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        .no-orders {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Список заказов</h1>
        
        <div class="actions">
            <a href="index.php" class="btn btn-primary">← На главную</a>
            <a href="form.php" class="btn btn-secondary">📝 Новый заказ</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <h3>❌ Ошибка</h3>
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <!-- Статистика -->
        <?php if (isset($stats) && !empty($stats)): ?>
        <div class="info-section">
            <h2>📊 Статистика по блюдам</h2>
            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                <div class="stat-card">
                    <h3><?= htmlspecialchars($stat['dish']) ?></h3>
                    <p><strong>Количество заказов:</strong> <?= $stat['count'] ?></p>
                    <p><strong>Всего порций:</strong> <?= $stat['total_portions'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="orders-section">
            <h2>📦 Все заказы</h2>
            
            <?php if (isset($orders) && !empty($orders)): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Блюдо</th>
                            <th>Порций</th>
                            <th>Соус</th>
                            <th>Тип доставки</th>
                            <th>Дата доставки</th>
                            <th>Статус</th>
                            <th>Время заказа</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['name']) ?></td>
                            <td><?= htmlspecialchars($order['email']) ?></td>
                            <td>
                                <?php 
                                $dishes = [
                                    'pizza' => '🍕 Пицца',
                                    'pasta' => '🍝 Паста', 
                                    'salad' => '🥗 Салат',
                                    'burger' => '🍔 Бургер'
                                ];
                                echo $dishes[$order['dish']] ?? htmlspecialchars($order['dish']);
                                ?>
                            </td>
                            <td><?= $order['portions'] ?></td>
                            <td><?= $order['sauce'] ? '✅ Да' : '❌ Нет' ?></td>
                            <td>
                                <?php
                                $deliveryTypes = [
                                    'courier' => '🚚 Курьер',
                                    'pickup' => '🏪 Самовывоз',
                                    'express' => '⚡ Экспресс'
                                ];
                                echo $deliveryTypes[$order['delivery_type']] ?? htmlspecialchars($order['delivery_type']);
                                ?>
                            </td>
                            <td><?= $order['delivery_date'] ?></td>
                            <td>
                                <?php 
                                $statusClass = 'status-' . ($order['status'] ?? 'pending');
                                $statusText = [
                                    'pending' => '⏳ Ожидает',
                                    'processing' => '🔄 Обрабатывается', 
                                    'completed' => '✅ Завершен'
                                ];
                                $status = $order['status'] ?? 'pending';
                                ?>
                                <span class="<?= $statusClass ?>">
                                    <?= $statusText[$status] ?? $status ?>
                                </span>
                            </td>
                            <td><?= $order['order_time'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="orders-info">
                    <p><strong>Всего заказов:</strong> <?= count($orders) ?></p>
                </div>
                
            <?php else: ?>
                <div class="no-orders">
                    <h3>📭 Заказов пока нет</h3>
                    <p>Создайте первый заказ через форму заказа</p>
                    <a href="form.php" class="btn btn-primary">Создать заказ</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="instructions">
            <h3>ℹ️ Информация</h3>
            <p>Заказы обрабатываются асинхронно через Kafka. Для обработки заказов запустите воркер:</p>
            <code>docker exec -it lab7_php php worker.php</code>
            <p>После обработки воркером статус заказов изменится на "Завершен".</p>
        </div>
    </div>
</body>
</html>