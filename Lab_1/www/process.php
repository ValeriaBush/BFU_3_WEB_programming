<?php
session_start();
require_once 'db.php';
require_once 'FoodOrder.php';

unset($_SESSION['errors']);

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$portions = intval($_POST['portions'] ?? 1);
$dish = htmlspecialchars($_POST['dish'] ?? '');
$deliveryDate = htmlspecialchars($_POST['deliveryDate'] ?? '');
$sauce = isset($_POST['sauce']) ? 1 : 0;
$deliveryType = htmlspecialchars($_POST['deliveryType'] ?? '');

$errors = [];

// Валидация данных
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

if(!empty($errors)) {
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
    header("Location: index.php");
    exit();
}

try {
    $foodOrder = new FoodOrder($pdo);
    $orderId = $foodOrder->add($name, $email, $portions, $dish, $sauce, $deliveryType, $deliveryDate);
    
    $line = $name . ";" . $email . ";" . $portions . ";" . $dish . ";" . $deliveryDate . ";" . ($sauce ? 'Да' : 'Нет') . ";" . $deliveryType . ";" . date('Y-m-d H:i:s') . "\n";
    file_put_contents("data.txt", $line, FILE_APPEND);
    
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['portions'] = $portions;
    $_SESSION['dish'] = $dish;
    $_SESSION['deliveryDate'] = $deliveryDate;
    $_SESSION['sauce'] = $sauce ? 'Да' : 'Нет';
    $_SESSION['deliveryType'] = $deliveryType;
    $_SESSION['form_submitted'] = time();
    $_SESSION['mysql_order_id'] = $orderId;
    
    unset($_SESSION['form_data']);

    require_once __DIR__ . '/vendor/autoload.php';
    require_once 'ApiClient.php';
    
    $api = new ApiClient();
    $url = 'https://www.themealdb.com/api/json/v1/1/categories.php';
    $apiData = $api->request($url);
    
    $_SESSION['api_data'] = $apiData;

    // Куки
    $cookieTime = time() + 3600;
    setcookie("last_submission", date('Y-m-d H:i:s'), $cookieTime, "/");
    
    header("Location: index.php");
    exit();
    
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Ошибка сохранения в базу данных: " . $e->getMessage()];
    header("Location: index.php");
    exit();
}
?>