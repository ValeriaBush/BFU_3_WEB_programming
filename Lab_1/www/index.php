<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница - Заказ еды</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Система заказа еды</h1>
    
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="error-container">
            <h3>Ошибки при заполнении формы:</h3>
            <ul class="error-list">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <div class="session-data">
        <h2>Данные из сессии:</h2>
        <?php if(isset($_SESSION['name'])): ?>
            <ul>
                <li><strong>Имя:</strong> <?= $_SESSION['name'] ?></li>
                <li><strong>Email:</strong> <?= $_SESSION['email'] ?></li>
                <li><strong>Количество порций:</strong> <?= $_SESSION['portions'] ?></li>
                <li><strong>Блюдо:</strong> <?= $_SESSION['dish'] ?></li>
                <li><strong>Дата доставки:</strong> <?= $_SESSION['deliveryDate'] ?></li>
                <li><strong>Добавить соус:</strong> <?= $_SESSION['sauce'] ?></li>
                <li><strong>Тип доставки:</strong> <?= $_SESSION['deliveryType'] ?></li>
                <?php if(isset($_SESSION['form_submitted'])): ?>
                    <li><strong>Время отправки:</strong> <?= date('Y-m-d H:i:s', $_SESSION['form_submitted']) ?></li>
                <?php endif; ?>
            </ul>
        <?php else: ?>
            <div class="no-data">
                <p>Данных пока нет.</p>
                <p>Заполните форму, чтобы увидеть данные здесь.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="links">
        <a href="form.html">Заполнить форму</a>
        <a href="view.php">Посмотреть все данные</a>
    </div>
</body>
</html>