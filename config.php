<?php
/**
 * MBTI 性格测试系统 - 数据库配置文件
 * 请根据您的实际环境修改以下配置
 */

// 数据库配置
define('DB_HOST', 'localhost'); // 默认的，无需更改
define('DB_PORT', '3306'); // 默认端口，无需更改
define('DB_NAME', '154_36_153_124'); // 修改为你的数据库名称
define('DB_USER', '154_36_153_124'); // 修改为你的数据库名称
define('DB_PASS', 'TW883cAykSyy5cNJ'); // 修改为你的数据库密码
define('DB_CHARSET', 'utf8mb4');

// 网站配置
define('SITE_NAME', 'MBTI 性格测试');
define('SITE_URL', 'https://mbti.bugcool.cn');  // 修改为您的实际域名
define('SITE_DESCRIPTION', '通过科学的MBTI性格测试，探索你的人格类型，获取专属性格证书');

// 证书配置
define('CERT_PREFIX', 'MBTI');  // 证书编号前缀
define('CERT_NO_LENGTH', 6);    // 证书编号随机部分长度

// 时区设置
date_default_timezone_set('Asia/Shanghai');

// 错误报告（生产环境请设为0）
error_reporting(0);
ini_set('display_errors', 0);
