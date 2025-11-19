<?php
require_once 'db.php';
require_once 'FoodOrder.php';
require_once 'QueueManager.php';

echo "👷 Worker started...\n";
echo "📊 Listening for messages in Kafka...\n";
echo "⏳ Press Ctrl+C to stop\n\n";

// Создаем подключение к БД и объект FoodOrder
$foodOrder = new FoodOrder($pdo);
$queueManager = new QueueManager();

try {
    $queueManager->consume(function($data) use ($foodOrder, $pdo) {
        echo "📥 Received message: " . json_encode($data) . "\n";
        
        // Обрабатываем разные типы действий
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create_order':
                    echo "🍕 Processing new order...\n";
                    
                    $orderData = $data['data'];
                    try {
                        // Сохраняем заказ в БД
                        $orderId = $foodOrder->add(
                            $orderData['name'],
                            $orderData['email'],
                            $orderData['portions'],
                            $orderData['dish'],
                            $orderData['sauce'],
                            $orderData['delivery_type'],
                            $orderData['delivery_date']
                        );
                        
                        echo "✅ Order saved to database. ID: $orderId\n";
                        
                        // Обновляем статус заказа (используем $pdo напрямую)
                        $stmt = $pdo->prepare("UPDATE food_orders SET status = 'completed', processed_time = NOW() WHERE id = ?");
                        $stmt->execute([$orderId]);
                            
                        echo "✅ Order marked as completed\n";
                        
                    } catch (Exception $e) {
                        echo "❌ Error saving order: " . $e->getMessage() . "\n";
                    }
                    break;
                    
                default:
                    echo "⚠️ Unknown action: " . $data['action'] . "\n";
                    break;
            }
        } else {
            echo "⚠️ No action specified in message\n";
        }
        
        echo "---\n";
    });
    
} catch (Exception $e) {
    echo "❌ Worker error: " . $e->getMessage() . "\n";
    echo "🔄 Restarting in 5 seconds...\n";
    sleep(5);
}