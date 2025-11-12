<?php
// SessionManager с прямым подключением к Redis

class SessionManager
{
    private $redis;
    private $sessionPrefix = 'session:';

    public function __construct()
    {
        // Проверяем установлено ли Redis расширение
        if (!extension_loaded('redis')) {
            throw new Exception("Redis extension not loaded. Please install php-redis extension.");
        }
        
        // Прямое подключение к Redis
        $this->redis = new Redis();
        $this->redis->connect('redis', 6379); // Подключаемся к контейнеру redis
        
        // Проверяем подключение
        if (!$this->redis->ping()) {
            throw new Exception("Cannot connect to Redis server");
        }
    }

    /**
     * Тестирование подключения к Redis
     */
    public function testConnection()
    {
        try {
            $pong = $this->redis->ping();
            return "✅ Redis подключен: $pong";
        } catch (Exception $e) {
            return "❌ Ошибка подключения: " . $e->getMessage();
        }
    }

    /**
     * Создание новой сессии
     */
    public function createSession($userId, $userData = [])
    {
        try {
            $sessionId = uniqid('sess_', true);
            $sessionKey = $this->sessionPrefix . $sessionId;
            
            $sessionData = [
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
                'last_activity' => date('Y-m-d H:i:s'),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'data' => $userData
            ];

            // Сохраняем сессию на 1 час (3600 секунд)
            $this->redis->setex($sessionKey, 3600, json_encode($sessionData));
            
            return $sessionId;
            
        } catch (Exception $e) {
            throw new Exception("Create session error: " . $e->getMessage());
        }
    }

    /**
     * Получение данных сессии
     */
    public function getSession($sessionId)
    {
        try {
            $sessionKey = $this->sessionPrefix . $sessionId;
            $data = $this->redis->get($sessionKey);
            
            return $data ? json_decode($data, true) : null;
            
        } catch (Exception $e) {
            throw new Exception("Get session error: " . $e->getMessage());
        }
    }

    /**
     * Обновление активности сессии
     */
    public function updateSessionActivity($sessionId)
    {
        $session = $this->getSession($sessionId);
        if ($session) {
            $session['last_activity'] = date('Y-m-d H:i:s');
            $sessionKey = $this->sessionPrefix . $sessionId;
            
            try {
                // Обновляем сессию с тем же TTL
                $ttl = $this->redis->ttl($sessionKey);
                if ($ttl > 0) {
                    $this->redis->setex($sessionKey, $ttl, json_encode($session));
                }
                return true;
            } catch (Exception $e) {
                throw new Exception("Update session error: " . $e->getMessage());
            }
        }
        return false;
    }

    /**
     * Удаление сессии (logout)
     */
    public function destroySession($sessionId)
    {
        try {
            $sessionKey = $this->sessionPrefix . $sessionId;
            $result = $this->redis->del($sessionKey);
            return "Удалено сессий: $result";
        } catch (Exception $e) {
            throw new Exception("Destroy session error: " . $e->getMessage());
        }
    }

    /**
     * Получение всех активных сессий пользователя
     */
    public function getUserSessions($userId)
    {
        try {
            // Ищем все сессии пользователя
            $pattern = $this->sessionPrefix . '*';
            $keys = $this->redis->keys($pattern);
            $userSessions = [];
            
            foreach ($keys as $key) {
                $sessionData = $this->redis->get($key);
                if ($sessionData) {
                    $data = json_decode($sessionData, true);
                    if ($data['user_id'] == $userId) {
                        $userSessions[] = $data;
                    }
                }
            }
            
            return "Найдено сессий пользователя $userId: " . count($userSessions);
            
        } catch (Exception $e) {
            throw new Exception("Get sessions stats error: " . $e->getMessage());
        }
    }

    /**
     * Получение статистики по всем сессиям
     */
    public function getSessionStats()
    {
        try {
            $pattern = $this->sessionPrefix . '*';
            $keys = $this->redis->keys($pattern);
            $totalSessions = count($keys);
            
            $users = [];
            foreach ($keys as $key) {
                $sessionData = $this->redis->get($key);
                if ($sessionData) {
                    $data = json_decode($sessionData, true);
                    $users[$data['user_id']] = true;
                }
            }
            
            $uniqueUsers = count($users);
            
            return "Всего сессий: $totalSessions, Уникальных пользователей: $uniqueUsers";
            
        } catch (Exception $e) {
            throw new Exception("Get session stats error: " . $e->getMessage());
        }
    }

    /**
     * Очистка устаревших сессий
     */
    public function cleanupExpiredSessions()
    {
        // Redis автоматически удаляет expired ключи через TTL
        return "Redis автоматически удаляет expired ключи через TTL";
    }
}