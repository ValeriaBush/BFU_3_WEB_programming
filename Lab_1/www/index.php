<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once 'ApiClient.php';
require_once 'UserInfo.php';
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
    
    <div class="user-info">
        <h2>🌐 Информация о пользователе:</h2>
        <?php
        $info = UserInfo::getInfo();
        echo "<ul>";
        foreach ($info as $key => $val) {
            echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($val) . "</li>";
        }
        
        if (isset($_COOKIE['last_submission'])) {
            echo "<li><strong>Последняя отправка формы:</strong> " . htmlspecialchars($_COOKIE['last_submission']) . "</li>";
        } else {
            echo "<li><strong>Последняя отправка формы:</strong> никогда</li>";
        }
        echo "</ul>";
        ?>
    </div>
    
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
        <h2>📋 Данные из сессии:</h2>
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

    <?php if (isset($_SESSION['api_data'])): ?>
    <div class="session-data">
        <h2>🍽️ Категории блюд из The Meal DB:</h2>
        <?php if (isset($_SESSION['api_data']['categories'])): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach($_SESSION['api_data']['categories'] as $category): ?>
                    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: white;">
                        <h3 style="margin-top: 0; color: #333;"><?= htmlspecialchars($category['strCategory']) ?></h3>
                        <?php if (!empty($category['strCategoryThumb'])): ?>
                            <img src="<?= htmlspecialchars($category['strCategoryThumb']) ?>" 
                                 alt="<?= htmlspecialchars($category['strCategory']) ?>" 
                                 style="max-width: 100%; height: auto; border-radius: 5px; margin-bottom: 10px;">
                        <?php endif; ?>
                        <?php if (!empty($category['strCategoryDescription'])): ?>
                            <p style="font-size: 14px; color: #666; line-height: 1.4;">
                                <?= substr(htmlspecialchars($category['strCategoryDescription']), 0, 150) ?>...
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($_SESSION['api_data']['error'])): ?>
            <div class="error-container">
                <p>Ошибка при получении данных из API: <?= $_SESSION['api_data']['error'] ?></p>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>Нет данных от API</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="links">
        <a href="form.html">Заполнить форму</a>
        <a href="view.php">Посмотреть все данные</a>
        <a href="test-apiclient.php">Тест API</a>
    </div>
</body>
</html>