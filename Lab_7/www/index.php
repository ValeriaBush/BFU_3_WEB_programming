<?php
session_start();
require_once 'db.php';
require_once 'FoodOrder.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 7 - Система заказов с Kafka</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🍕 Система заказов еды (Lab 7 - Kafka)</h1>
        
        <div class="info-section">
            <h2>📊 Статус системы</h2>
            <div class="status-grid">
                <div class="status-item">
                    <strong>Kafka:</strong> 
                    <span class="status-online">✅ Онлайн</span>
                </div>
                <div class="status-item">
                    <strong>MySQL:</strong> 
                    <span class="status-online">✅ Онлайн</span>
                </div>
                <div class="status-item">
                    <strong>Очередь:</strong> 
                    <span class="status-online">lab7_orders</span>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="form.php" class="btn btn-primary">📝 Создать заказ</a>
            <a href="send_form.html" class="btn btn-info">📨 API отправки</a>
            <a href="orders.php" class="btn btn-secondary">📋 Посмотреть заказы</a>
            <a href="test-setup.php" class="btn btn-info">🧪 Тест системы</a>
        </div>

        <?php if (isset($_SESSION['order_result'])): ?>
        <div class="alert alert-success">
            <h3>✅ Заказ отправлен в обработку!</h3>
            <p>Сообщение отправлено в Kafka: <?= $_SESSION['order_result']['kafka_result'] ? 'Да' : 'Нет' ?></p>
            <p>Время отправки: <?= date('Y-m-d H:i:s') ?></p>
            <small>Заказ будет обработан асинхронно воркером.</small>
        </div>
        <?php unset($_SESSION['order_result']); endif; ?>

        <?php if (isset($_SESSION['last_order'])): ?>
        <div class="order-preview" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3498db;">
            <h3>📋 Последний отправленный заказ:</h3>
            <div class="order-details">
                <p><strong>👤 Имя:</strong> <?= htmlspecialchars($_SESSION['last_order']['name']) ?></p>
                <p><strong>📧 Email:</strong> <?= htmlspecialchars($_SESSION['last_order']['email']) ?></p>
                <p><strong>🍽️ Порций:</strong> <?= htmlspecialchars($_SESSION['last_order']['portions']) ?></p>
                <p><strong>🍕 Блюдо:</strong> <?= htmlspecialchars($_SESSION['last_order']['dish']) ?></p>
                <p><strong>📅 Дата доставки:</strong> <?= htmlspecialchars($_SESSION['last_order']['delivery_date']) ?></p>
                <p><strong>🥫 Соус:</strong> <?= htmlspecialchars($_SESSION['last_order']['sauce']) ?></p>
                <p><strong>🚚 Тип доставки:</strong> <?= htmlspecialchars($_SESSION['last_order']['delivery_type']) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="instructions">
            <h3>🎯 Как работает система:</h3>
            <ol>
                <li>Форма отправляет заказ в очередь Kafka</li>
                <li>Воркер (worker.php) обрабатывает заказы из очереди</li>
                <li>Заказ сохраняется в MySQL после обработки</li>
                <li>Статус можно отслеживать на странице заказов</li>
            </ol>
            
            <h4>🚀 Запуск воркера:</h4>
            <code>docker exec -it lab7_php php worker.php</code>

            <h4>🔧 Доступные интерфейсы:</h4>
            <ul>
                <li><strong>📝 Форма заказа</strong> - Классический веб-интерфейс</li>
                <li><strong>📨 API отправки</strong> - JSON API для интеграций</li>
                <li><strong>📋 Просмотр заказов</strong> - Мониторинг и статистика</li>
            </ul>
        </div>
    </div>
</body>
</html>