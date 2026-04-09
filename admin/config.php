<?php
/**
 * 后台管理系统配置文件
 */

// 防止直接访问
if (!defined('ADMIN_ROOT')) {
    define('ADMIN_ROOT', true);
}

// 引入主配置
require_once dirname(__DIR__) . '/config.php';

// 管理员账号配置（生产环境请修改）
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', password_hash('admin123', PASSWORD_DEFAULT)); // 默认密码: admin123

// Session 配置
define('SESSION_NAME', 'mbti_admin_session');
define('SESSION_LIFETIME', 7200); // 2小时

// 每页显示条数
define('PAGE_SIZE', 20);

// 版本
define('ADMIN_VERSION', '1.0.0');
