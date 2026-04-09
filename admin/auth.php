<?php
/**
 * 后台认证函数库
 */

if (!defined('ADMIN_ROOT')) {
    define('ADMIN_ROOT', true);
}

require_once dirname(__FILE__) . '/config.php';

function admin_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        @session_name('mbti_admin_session');
        @session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/admin/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        @session_start();
    }
}

function admin_is_logged_in() {
    admin_session_start();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true
        && isset($_SESSION['admin_expire']) && $_SESSION['admin_expire'] > time();
}

function admin_require_login() {
    if (!admin_is_logged_in()) {
        $reqUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        header('Location: login.php?redirect=' . urlencode($reqUri));
        exit;
    }
    $_SESSION['admin_expire'] = time() + SESSION_LIFETIME;
}

function admin_login($username, $password) {
    if ($username !== ADMIN_USERNAME) return false;
    // 临时简单验证（生产环境建议使用 password_verify + 固定哈希）
    if ($password !== 'admin123') return false;
    admin_session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username']  = $username;
    $_SESSION['admin_expire']    = time() + SESSION_LIFETIME;
    return true;
}

function admin_logout() {
    admin_session_start();
    $_SESSION = [];
    session_destroy();
}

function csrf_token() {
    admin_session_start();
    if (empty($_SESSION['csrf_token'])) {
        if (function_exists('random_bytes')) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $_SESSION['csrf_token'] = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 64);
        }
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify($token) {
    admin_session_start();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
