<?php
/**
 * 后台 API 接口（Ajax 请求处理）
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

admin_require_login();

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ——— 批量删除证书 ———
    case 'delete_certificates':
        if (!csrf_verify($_POST['csrf'] ?? '')) {
            echo json_encode(['ok'=>false,'msg'=>'CSRF 验证失败']);
            exit;
        }
        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || empty($ids)) {
            echo json_encode(['ok'=>false,'msg'=>'未选择任何记录']);
            exit;
        }
        $count = AdminDB::deleteCertificates($ids);
        echo json_encode(['ok'=>true,'msg'=>"已删除 {$count} 条记录",'count'=>$count]);
        break;

    // ——— 删除单条证书 ———
    case 'delete_certificate':
        if (!csrf_verify($_POST['csrf'] ?? '')) {
            echo json_encode(['ok'=>false,'msg'=>'CSRF 验证失败']);
            exit;
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['ok'=>false,'msg'=>'无效 ID']);
            exit;
        }
        $ok = AdminDB::deleteCertificate($id);
        echo json_encode(['ok'=>$ok,'msg'=>$ok ? '删除成功' : '未找到该记录']);
        break;

    // ——— 切换题目启用状态 ———
    case 'toggle_question':
        if (!csrf_verify($_POST['csrf'] ?? '')) {
            echo json_encode(['ok'=>false,'msg'=>'CSRF 验证失败']);
            exit;
        }
        $id     = (int)($_POST['id'] ?? 0);
        $active = (bool)(int)($_POST['active'] ?? 1);
        if ($id <= 0) {
            echo json_encode(['ok'=>false,'msg'=>'无效 ID']);
            exit;
        }
        $ok = AdminDB::toggleQuestion($id, $active);
        echo json_encode(['ok'=>$ok,'msg'=>$ok ? '状态已更新' : '更新失败']);
        break;

    // ——— 获取概览统计（刷新仪表板卡片）———
    case 'get_overview':
        $stats = AdminDB::getOverviewStats();
        echo json_encode(['ok'=>true,'data'=>[
            'total'      => $stats['total'],
            'today'      => $stats['today'],
            'this_week'  => $stats['this_week'],
            'this_month' => $stats['this_month'],
        ]]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['ok'=>false,'msg'=>'未知操作']);
        break;
}
exit;
