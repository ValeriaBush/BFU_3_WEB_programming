<?php
error_reporting(0);
session_start();
require_once 'db.php';
require_once 'QueueManager.php';

unset($_SESSION['errors']);

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$portions = intval($_POST['portions'] ?? 1);
$dish = htmlspecialchars($_POST['dish'] ?? '');
$deliveryDate = htmlspecialchars($_POST['deliveryDate'] ?? '');
$sauce = isset($_POST['sauce']) ? 1 : 0;
$deliveryType = htmlspecialchars($_POST['deliveryType'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = "Имя обязательно для заполнения";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Введите корректный email";
}
if ($portions < 1 || $portions > 10) {
    $errors[] = "Количество порций должно быть от 1 до 10";
}
if (empty($dish)) {
    $errors[] = "Выберите блюдо";
}
if (empty($deliveryDate)) {
    $errors[] = "Выберите дату доставки";
}
if (empty($deliveryType)) {
    $errors[] = "Выберите тип доставки";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = [
        'name' => $name,
        'email' => $email,
        'portions' => $portions,
        'dish' => $dish,
        'deliveryDate' => $deliveryDate,
        'sauce' => $sauce,
        'deliveryType' => $deliveryType
    ];
    header("Location: form.php");
    exit();
}

try {
    $orderData = [
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
        'timestamp' => time()
    ];

    $queueManager = new QueueManager();
    $kafkaResult = $queueManager->publish($orderData);

    $_SESSION['order_result'] = [
        'kafka_result' => $kafkaResult,
        'order_data' => $orderData,
        'message' => $kafkaResult ? 
            "Заказ отправлен в очередь Kafka успешно!" : 
            "Ошибка при отправке в Kafka"
    ];

    $_SESSION['last_order'] = [
        'name' => $name,
        'email' => $email,
        'portions' => $portions,
        'dish' => $dish,
        'delivery_date' => $deliveryDate,
        'sauce' => $sauce ? 'Да' : 'Нет',
        'delivery_type' => $deliveryType
    ];

    unset($_SESSION['form_data']);

    header("Location: index.php");
    exit();

} catch (Exception $e) {
    $_SESSION['errors'] = ["Ошибка при обработке заказа: " . $e->getMessage()];
    header("Location: form.php");
    exit();
}
?>