<?php
/**
 * MBTI 测试 API 接口
 * 所有AJAX请求统一入口
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/email.php';



$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $db = Database::getInstance();

    switch ($action) {
        case 'get_questions':
            // 获取所有测试题目
            $questions = $db->getQuestions();
            echo json_encode(['success' => true, 'data' => $questions]);
            break;

        case 'submit_test':
            // 提交测试结果
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $answers = $_POST['answers'] ?? '';

            if (empty($answers)) {
                throw new Exception('测试数据异常，请重新测试');
            }

            // 解析答案
            $answers = json_decode($answers, true);
            $requiredQuestionCount = $db->getActiveQuestionCount();
            if (!is_array($answers) || count($answers) < $requiredQuestionCount) {
                throw new Exception('请完成全部 ' . $requiredQuestionCount . ' 道测试题目');
            }


            // 计算MBTI类型
            $result = Database::calculateMbtiType($answers);
            $mbtiType = $result['type'];
            $scores = $result['scores'];

            // 保存结果
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $cert = $db->saveResult($name, $email, $mbtiType, $scores, $ipAddress, $userAgent);

            // 获取类型详情
            $typeInfo = $db->getMbtiType($mbtiType);

            // 发送邮件（异步，不阻塞响应）
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailData = [
                    'name'            => $name,
                    'mbti_type'       => $mbtiType,
                    'certificate_no'   => $cert['certificate_no'],
                    'type_name'        => $typeInfo['type_name'] ?? '',
                    'type_nickname'    => $typeInfo['type_nickname'] ?? '',
                    'E' => $scores['E'], 'I' => $scores['I'],
                    'S' => $scores['S'], 'N' => $scores['N'],
                    'T' => $scores['T'], 'F' => $scores['F'],
                    'J' => $scores['J'], 'P' => $scores['P'],
                ];
                @EmailSender::sendResultEmail($emailData, $email);
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'certificate_no' => $cert['certificate_no'],
                    'mbti_type' => $mbtiType,
                    'scores' => $scores,
                    'type_info' => $typeInfo,
                    'name' => $name
                ]
            ], JSON_UNESCAPED_UNICODE);
            break;

        case 'query_certificate':
            // 查询证书
            $queryType = $_POST['query_type'] ?? 'no';
            
            if ($queryType === 'name') {
                $name = trim($_POST['name'] ?? '');
                if (empty($name)) {
                    throw new Exception('请输入查询姓名');
                }
                $results = $db->getCertificatesByName($name);
                echo json_encode(['success' => true, 'data' => $results], JSON_UNESCAPED_UNICODE);
            } else {
                $certNo = trim($_POST['certificate_no'] ?? '');
                if (empty($certNo)) {
                    throw new Exception('请输入证书编号');
                }
                $cert = $db->getCertificateByNo($certNo);
                if (!$cert) {
                    throw new Exception('未找到该证书编号，请核实后重试');
                }
                $cert['scores'] = json_decode($cert['scores'], true);
                $cert['strengths'] = json_decode($cert['strengths'], true);
                $cert['weaknesses'] = json_decode($cert['weaknesses'], true);
                $cert['careers'] = json_decode($cert['careers'], true);
                $cert['celebrities'] = json_decode($cert['celebrities'], true);
                echo json_encode(['success' => true, 'data' => $cert], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'get_stats':
            // 获取统计信息
            $stats = $db->getTypeStats();
            $total = $db->getTotalCount();
            $today = $db->getTodayCount();
            echo json_encode([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'today' => $today,
                    'type_stats' => $stats
                ]
            ], JSON_UNESCAPED_UNICODE);
            break;

        default:
            throw new Exception('无效的操作类型');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
