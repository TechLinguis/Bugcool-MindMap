<?php
/**
 * MBTI 性格测试系统 - 数据库连接与操作类
 */

require_once dirname(__DIR__) . '/config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['success' => false, 'message' => '数据库连接失败：' . $e->getMessage()]));
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    /**
     * 获取所有启用的测试题目
     */
    public function getQuestions() {
        $sql = "SELECT * FROM questions WHERE is_active = 1 ORDER BY FIELD(dimension, 'EI','SN','TF','JP'), sort_order";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * 获取当前启用题目数量
     */
    public function getActiveQuestionCount() {
        $sql = "SELECT COUNT(*) AS total FROM questions WHERE is_active = 1";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    /**
     * 根据MBTI类型获取描述信息
     */

    public function getMbtiType($typeCode) {
        $sql = "SELECT * FROM mbti_types WHERE type_code = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$typeCode]);
        return $stmt->fetch();
    }

    /**
     * 生成唯一证书编号
     */
    public function generateCertificateNo() {
        $date = date('Ymd');
        $maxAttempts = 10;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $random = strtoupper(bin2hex(random_bytes(CERT_NO_LENGTH / 2)));
            $certNo = CERT_PREFIX . '-' . $date . '-' . $random;

            // 检查是否已存在
            $sql = "SELECT COUNT(*) as cnt FROM certificates WHERE certificate_no = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$certNo]);
            $result = $stmt->fetch();

            if ($result['cnt'] == 0) {
                return $certNo;
            }
        }

        throw new Exception('生成证书编号失败，请稍后重试');
    }

    /**
     * 保存测试结果并生成证书
     */
    public function saveResult($name, $email, $mbtiType, $scores, $ipAddress = null, $userAgent = null) {
        $certNo = $this->generateCertificateNo();
        $scoresJson = json_encode($scores, JSON_UNESCAPED_UNICODE);
        
        // 获取类型详情
        $typeInfo = $this->getMbtiType($mbtiType);
        $resultDetail = json_encode([
            'type_code' => $mbtiType,
            'type_name' => $typeInfo['type_name'],
            'type_nickname' => $typeInfo['type_nickname'],
            'type_color' => $typeInfo['type_color'],
            'icon' => $typeInfo['icon'],
            'scores' => $scores
        ], JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO certificates (certificate_no, name, email, mbti_type, scores, result_detail, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$certNo, $name, $email, $mbtiType, $scoresJson, $resultDetail, $ipAddress, $userAgent]);

        return [
            'id' => $this->conn->lastInsertId(),
            'certificate_no' => $certNo
        ];
    }

    /**
     * 根据证书编号查询
     */
    public function getCertificateByNo($certNo) {
        $sql = "SELECT c.*, t.* 
                FROM certificates c 
                LEFT JOIN mbti_types t ON c.mbti_type = t.type_code 
                WHERE c.certificate_no = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$certNo]);
        return $stmt->fetch();
    }

    /**
     * 根据姓名查询证书列表
     */
    public function getCertificatesByName($name) {
        $sql = "SELECT c.certificate_no, c.name, c.mbti_type, c.created_at, t.type_name, t.type_nickname, t.icon, t.type_color
                FROM certificates c 
                LEFT JOIN mbti_types t ON c.mbti_type = t.type_code 
                WHERE c.name = ? 
                ORDER BY c.created_at DESC
                LIMIT 20";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetchAll();
    }

    /**
     * 计算MBTI类型
     */
    public static function calculateMbtiType($answers) {
        // answers 是数组，key是题目ID，value是 'a' 或 'b'
        $scores = ['E' => 0, 'I' => 0, 'S' => 0, 'N' => 0, 'T' => 0, 'F' => 0, 'J' => 0, 'P' => 0];
        
        $db = self::getInstance();
        $questions = $db->getQuestions();
        
        // 建立题目ID到维度的映射
        $questionMap = [];
        foreach ($questions as $q) {
            $questionMap[$q['id']] = $q['dimension'];
        }

        foreach ($answers as $questionId => $answer) {
            if (!isset($questionMap[$questionId])) continue;
            $dimension = $questionMap[$questionId];
            $letters = str_split($dimension);
            
            if ($answer === 'a') {
                $scores[$letters[0]]++;
            } elseif ($answer === 'b') {
                $scores[$letters[1]]++;
            }
        }

        // 确定每个维度的倾向
        $type = '';
        $type .= $scores['E'] >= $scores['I'] ? 'E' : 'I';
        $type .= $scores['S'] >= $scores['N'] ? 'S' : 'N';
        $type .= $scores['T'] >= $scores['F'] ? 'T' : 'F';
        $type .= $scores['J'] >= $scores['P'] ? 'J' : 'P';

        return [
            'type' => $type,
            'scores' => $scores
        ];
    }

    /**
     * 获取类型统计（用于首页展示）
     */
    public function getTypeStats() {
        $sql = "SELECT mbti_type, COUNT(*) as count 
                FROM certificates 
                GROUP BY mbti_type 
                ORDER BY count DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * 获取总测试人数
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as total FROM certificates";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * 获取今日测试人数
     */
    public function getTodayCount() {
        $sql = "SELECT COUNT(*) as today FROM certificates WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch();
        return (int)$result['today'];
    }

    // 防止克隆
    private function __clone() {}
}
