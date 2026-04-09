<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
admin_logout();
header('Location: login.php');
exit;
