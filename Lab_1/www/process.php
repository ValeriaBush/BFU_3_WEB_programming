<?php
session_start();

unset($_SESSION['errors']);

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$portions = htmlspecialchars($_POST['portions'] ?? '');
$dish = htmlspecialchars($_POST['dish'] ?? '');
$deliveryDate = htmlspecialchars($_POST['deliveryDate'] ?? '');
$sauce = isset($_POST['sauce']) ? 'Да' : 'Нет';
$deliveryType = htmlspecialchars($_POST['deliveryType'] ?? '');

$errors = [];

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

$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['portions'] = $portions;
$_SESSION['dish'] = $dish;
$_SESSION['deliveryDate'] = $deliveryDate;
$_SESSION['sauce'] = $sauce;
$_SESSION['deliveryType'] = $deliveryType;
$_SESSION['form_submitted'] = time();

unset($_SESSION['form_data']);

$line = $name . ";" . $email . ";" . $portions . ";" . $dish . ";" . $deliveryDate . ";" . $sauce . ";" . $deliveryType . ";" . date('Y-m-d H:i:s') . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'ApiClient.php';

$api = new ApiClient();
$url = 'https://www.themealdb.com/api/json/v1/1/categories.php';
$apiData = $api->request($url);

$_SESSION['api_data'] = $apiData;

$cookieTime = time() + 3600;
setcookie("last_submission", date('Y-m-d H:i:s'), $cookieTime, "/");

header("Location: index.php");
exit();
?>