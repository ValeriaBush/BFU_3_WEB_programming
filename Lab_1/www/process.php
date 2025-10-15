<?php
session_start();

unset($_SESSION['errors']);

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$portions = htmlspecialchars($_POST['portions'] ?? '');
$dish = htmlspecialchars($_POST['dish'] ?? '');
$deliveryDate = htmlspecialchars($_POST['deliveryDate'] ?? '');
$sauce = isset($_POST['sauce']) ? 'yes' : 'Нет';
$deliveryType = htmlspecialchars($_POST['deliveryType'] ?? '');

$errors = [];

if(empty(trim($name))) {
    $errors[] = "Имя не может быть пустым";
}

if(empty(trim($email))) {
    $errors[] = "Email не может быть пустым";
} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email";
}

if(empty($portions) || $portions < 1 || $portions > 10) {
    $errors[] = "Количество порций должно быть от 1 до 10";
}

if(empty($dish)) {
    $errors[] = "Необходимо выбрать блюдо";
}

if(empty($deliveryDate)) {
    $errors[] = "Необходимо указать дату доставки";
} else {
    $minDate = date('Y-m-d');
    if($deliveryDate < $minDate) {
        $errors[] = "Дата доставки не может быть в прошлом";
    }
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

header("Location: index.php");
exit();
?>