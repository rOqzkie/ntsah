<?php

// Load environment variables from .env file
require_once(__DIR__ . '/classes/EnvLoader.php');

// Auto-detect base URL: use localhost path when running locally, otherwise use .env value
function detect_base_url() {
    $configured = env('BASE_URL', 'http://localhost/ntsah/');
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, 'localhost:') === 0) {
        $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        // Walk up to find the project root relative to docroot
        $app_dir = str_replace('\\', '/', __DIR__);
        $doc_root = str_replace('\\', '/', isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '');
        $rel = '/ntsah/';
        if (!empty($doc_root) && strpos($app_dir, $doc_root) === 0) {
            $rel = substr($app_dir, strlen($doc_root));
            $rel = '/' . trim(str_replace('\\', '/', $rel), '/') . '/';
        }
        return 'http://localhost' . $rel;
    }
    return $configured;
}

if(!defined('base_url')) define('base_url', detect_base_url());
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
if(!defined('DB_SERVER')) define('DB_SERVER', env('DB_SERVER', 'localhost'));
if(!defined('DB_USERNAME')) define('DB_USERNAME', env('DB_USERNAME', ''));
if(!defined('DB_PASSWORD')) define('DB_PASSWORD', env('DB_PASSWORD', ''));
if(!defined('DB_NAME')) define('DB_NAME', env('DB_NAME', ''));

?>