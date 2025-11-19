<?php
require_once 'vendor/autoload.php';
require_once 'QueueManager.php';

header('Content-Type: application/json');

$name = $_POST['name'] ?? 'Без имени';
$email = $_POST['email'] ?? 'test@example.com';
$portions = intval($_POST['portions'] ?? 1);
$dish = $_POST['dish'] ?? 'pizza';
$deliveryDate = $_POST['deliveryDate'] ?? date('Y-m-d');
$sauce = isset($_POST['sauce']) ? 1 : 0;
$deliveryType = $_POST['deliveryType'] ?? 'courier';

try {
    $queueManager = new QueueManager();
    
    $messageData = [
        'action' => 'create_order',
        'data' => [
            'name' => $name,
            'email' => $email,
            'portions' => $portions,
            'dish' => $dish,
            'sauce' => $sauce,
            'delivery_type' => $deliveryType,
            'delivery_date' => $deliveryDate,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ],
        'timestamp' => time(),
        'source' => 'send.php'
    ];
    
    $result = $queueManager->publish($messageData);
    
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Сообщение отправлено в очередь Kafka!',
            'data' => $messageData,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Ошибка при отправке в Kafka',
            'data' => $messageData
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => '❌ Исключение: ' . $e->getMessage()
    ]);
}
?>