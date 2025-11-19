<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма заказа - Lab 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🍽️ Форма заказа еды</h1>
        
        <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <h3>❌ Ошибки при заполнении:</h3>
            <ul>
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); endif; ?>

        <form action="process.php" method="POST" class="order-form">
            <div class="form-group">
                <label for="name">👤 Имя:</label>
                <input type="text" id="name" name="name" value="<?= $_SESSION['form_data']['name'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" id="email" name="email" value="<?= $_SESSION['form_data']['email'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="portions">🍽️ Количество порций:</label>
                <input type="number" id="portions" name="portions" min="1" max="10" value="<?= $_SESSION['form_data']['portions'] ?? 1 ?>" required>
            </div>

            <div class="form-group">
                <label for="dish">🍕 Блюдо:</label>
                <select id="dish" name="dish" required>
                    <option value="">Выберите блюдо</option>
                    <option value="pizza" <?= ($_SESSION['form_data']['dish'] ?? '') == 'pizza' ? 'selected' : '' ?>>Пицца Маргарита</option>
                    <option value="pasta" <?= ($_SESSION['form_data']['dish'] ?? '') == 'pasta' ? 'selected' : '' ?>>Паста Карбонара</option>
                    <option value="salad" <?= ($_SESSION['form_data']['dish'] ?? '') == 'salad' ? 'selected' : '' ?>>Греческий салат</option>
                    <option value="burger" <?= ($_SESSION['form_data']['dish'] ?? '') == 'burger' ? 'selected' : '' ?>>Бургер с говядиной</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deliveryDate">📅 Дата доставки:</label>
                <input type="date" id="deliveryDate" name="deliveryDate" value="<?= $_SESSION['form_data']['deliveryDate'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label>🥫 Добавить соус?</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="sauce" name="sauce" value="1" <?= ($_SESSION['form_data']['sauce'] ?? 0) ? 'checked' : '' ?>>
                    <label for="sauce">Да, добавить соус (+50 руб)</label>
                </div>
            </div>

            <div class="form-group">
                <label>🚚 Тип доставки:</label>
                <div class="radio-group">
                    <input type="radio" id="courier" name="deliveryType" value="courier" <?= ($_SESSION['form_data']['deliveryType'] ?? 'courier') == 'courier' ? 'checked' : '' ?>>
                    <label for="courier">Курьерская доставка</label>
                </div>
                <div class="radio-group">
                    <input type="radio" id="pickup" name="deliveryType" value="pickup" <?= ($_SESSION['form_data']['deliveryType'] ?? '') == 'pickup' ? 'checked' : '' ?>>
                    <label for="pickup">Самовывоз</label>
                </div>
                <div class="radio-group">
                    <input type="radio" id="express" name="deliveryType" value="express" <?= ($_SESSION['form_data']['deliveryType'] ?? '') == 'express' ? 'checked' : '' ?>>
                    <label for="express">Экспресс-доставка (30 мин)</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">📨 Отправить в очередь</button>
        </form>

        <div class="back-link">
            <a href="index.php">← На главную</a>
        </div>
    </div>

    <script>
        document.getElementById('deliveryDate').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
<?php unset($_SESSION['form_data']); ?>