<?php
// Включение отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>🔐 Тестирование системы сессий (Redis)</h1>";
echo "🔍 Отладка: Начало работы...<br>";

// 1. Подключаем файлы вручную
require_once __DIR__ . '/vendor/autoload.php';
echo "✅ Autoload подключен<br>";

require_once __DIR__ . '/App/Helpers/ClientFactory.php';
echo "✅ ClientFactory подключен<br>";

require_once __DIR__ . '/App/SessionManager.php';
echo "✅ SessionManager подключен<br>";

// 2. Создаем менеджер сессий
echo "🔍 Создаем SessionManager...<br>";
try {
    $sessionManager = new SessionManager();
    echo "✅ SessionManager создан успешно<br>";
} catch (Exception $e) {
    echo "❌ Ошибка создания SessionManager: " . $e->getMessage() . "<br>";
    exit;
}

// 3. Тестируем подключение к Redis
echo "<h2>🔴 Тест подключения к Redis</h2>";
try {
    $connectionTest = $sessionManager->testConnection();
    echo "<p style='color: green;'>$connectionTest</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Ошибка подключения: " . $e->getMessage() . "</p>";
}

// 4. Тестируем создание сессии
echo "<h2>📝 Тест создания сессии</h2>";
try {
    $sessionId = $sessionManager->createSession(123, [
        'username' => 'john_doe',
        'email' => 'john@example.com',
        'role' => 'user'
    ]);
    echo "<p style='color: green;'>✅ Сессия создана: $sessionId</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Ошибка создания сессии: " . $e->getMessage() . "</p>";
    $sessionId = null;
}

// 5. Тестируем получение сессии (если создана успешно)
if ($sessionId) {
    echo "<h2>🔍 Тест получения сессии</h2>";
    try {
        $sessionData = $sessionManager->getSession($sessionId);
        if ($sessionData) {
            echo "<p style='color: green;'>✅ Данные сессии получены:</p>";
            echo "<pre>" . print_r($sessionData, true) . "</pre>";
        } else {
            echo "<p style='color: red;'>❌ Сессия не найдена</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Ошибка получения сессии: " . $e->getMessage() . "</p>";
    }
}

// 6. Тестируем информацию о сессии
if ($sessionId) {
    echo "<h2>ℹ️ Тест информации о сессии</h2>";
    try {
        $sessionInfo = $sessionManager->getSessionInfo($sessionId);
        echo "<p style='color: green;'>✅ Информация о сессии:</p>";
        echo "<pre>" . print_r($sessionInfo, true) . "</pre>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Ошибка получения информации: " . $e->getMessage() . "</p>";
    }
}

// 7. Тестируем обновление активности
if ($sessionId) {
    echo "<h2>🔄 Тест обновления активности</h2>";
    try {
        $updated = $sessionManager->updateSessionActivity($sessionId);
        if ($updated) {
            echo "<p style='color: green;'>✅ Активность обновлена</p>";
        } else {
            echo "<p style='color: red;'>❌ Не удалось обновить активность</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Ошибка обновления: " . $e->getMessage() . "</p>";
    }
}

// 8. Тестируем удаление сессии
if ($sessionId) {
    echo "<h2>🗑️ Тест удаления сессии</h2>";
    try {
        $result = $sessionManager->destroySession($sessionId);
        echo "<p style='color: green;'>✅ Сессия удалена: $result</p>";
        
        // Проверяем что сессия действительно удалена
        $deletedSession = $sessionManager->getSession($sessionId);
        if (!$deletedSession) {
            echo "<p style='color: green;'>✅ Подтверждение: сессия больше не существует</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Ошибка удаления: " . $e->getMessage() . "</p>";
    }
}

// 9. Статистика
echo "<h2>📊 Статистика сессий</h2>";
try {
    $sessionsInfo = $sessionManager->getUserSessions(123);
    echo "<p style='color: green;'>✅ $sessionsInfo</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Ошибка статистики: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php'>← На главную</a>";