<?php
echo "<h1>Debug Info</h1>";

echo "Current dir: " . __DIR__ . "<br>";
echo "Script: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";

$paths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    '/var/www/html/vendor/autoload.php',
    '/var/www/vendor/autoload.php'
];

foreach ($paths as $path) {
    echo "Checking: $path<br>";
    if (file_exists($path)) {
        echo "✅ EXISTS<br>";
        require_once $path;
        if (class_exists('GuzzleHttp\Client')) {
            echo "✅ Guzzle loaded!<br>";
        }
    } else {
        echo "❌ NOT FOUND<br>";
    }
    echo "<hr>";
}
?>