<?php
/**
 * 后台数据库操作类（扩展主站 Database 类的功能）
 */

if (!defined('ADMIN_ROOT')) {
    define('ADMIN_ROOT', true);
}

require_once dirname(__DIR__) . '/includes/database.php';

class AdminDB {
    private static ?PDO $conn = null;

    private static function db(): PDO {
        if (self::$conn === null) {
            self::$conn = Database::getInstance()->getConnection();
        }
        return self::$conn;
    }

    // ========== 概览统计 ==========

    public static function getOverviewStats(): array {
        $db = self::db();
        $stats = [];

        // 总人数
        $stats['total'] = (int)$db->query("SELECT COUNT(*) FROM certificates")->fetchColumn();

        // 今日
        $stats['today'] = (int)$db->query(
            "SELECT COUNT(*) FROM certificates WHERE DATE(created_at) = CURDATE()"
        )->fetchColumn();

        // 本周
        $stats['this_week'] = (int)$db->query(
            "SELECT COUNT(*) FROM certificates WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)"
        )->fetchColumn();

        // 本月
        $stats['this_month'] = (int)$db->query(
            "SELECT COUNT(*) FROM certificates WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())"
        )->fetchColumn();

        // 各类型统计（按数量降序）
        $stmt = $db->query(
            "SELECT mbti_type, COUNT(*) as cnt FROM certificates GROUP BY mbti_type ORDER BY cnt DESC"
        );
        $stats['type_distribution'] = $stmt->fetchAll();

        // 最近7天每日数量
        $stmt = $db->query(
            "SELECT DATE(created_at) as day, COUNT(*) as cnt FROM certificates
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
             GROUP BY DATE(created_at) ORDER BY day ASC"
        );
        $stats['daily_7d'] = $stmt->fetchAll();

        // 有邮箱的比例
        $stats['with_email'] = (int)$db->query(
            "SELECT COUNT(*) FROM certificates WHERE email IS NOT NULL AND email != ''"
        )->fetchColumn();

        return $stats;
    }

    // ========== 用户列表（分页 + 搜索 + 筛选）==========

    public static function getCertificates(array $params = []): array {
        $db = self::db();

        $page    = max(1, (int)($params['page'] ?? 1));
        $size    = PAGE_SIZE;
        $offset  = ($page - 1) * $size;
        $search  = trim($params['search'] ?? '');
        $type    = trim($params['type'] ?? '');
        $date_from = trim($params['date_from'] ?? '');
        $date_to   = trim($params['date_to'] ?? '');
        $sort    = in_array($params['sort'] ?? '', ['id','name','mbti_type','created_at']) ? ($params['sort'] ?? 'id') : 'id';
        $order   = strtoupper($params['order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

        $where = [];
        $binds = [];

        if ($search !== '') {
            $where[] = "(c.name LIKE ? OR c.certificate_no LIKE ? OR c.email LIKE ?)";
            $like = '%' . $search . '%';
            $binds[] = $like; $binds[] = $like; $binds[] = $like;
        }
        if ($type !== '') {
            $where[] = "c.mbti_type = ?";
            $binds[] = strtoupper($type);
        }
        if ($date_from !== '') {
            $where[] = "DATE(c.created_at) >= ?";
            $binds[] = $date_from;
        }
        if ($date_to !== '') {
            $where[] = "DATE(c.created_at) <= ?";
            $binds[] = $date_to;
        }

        $whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        // 总数
        $countSQL = "SELECT COUNT(*) FROM certificates c $whereSQL";
        $countStmt = $db->prepare($countSQL);
        $countStmt->execute($binds);
        $total = (int)$countStmt->fetchColumn();

        // 数据
        $dataSQL = "SELECT c.id, c.certificate_no, c.name, c.email, c.mbti_type, c.ip_address, c.created_at,
                           t.type_name, t.type_color, t.icon
                    FROM certificates c
                    LEFT JOIN mbti_types t ON c.mbti_type = t.type_code
                    $whereSQL
                    ORDER BY c.$sort $order
                    LIMIT $size OFFSET $offset";
        $dataStmt = $db->prepare($dataSQL);
        $dataStmt->execute($binds);
        $rows = $dataStmt->fetchAll();

        return [
            'rows'       => $rows,
            'total'      => $total,
            'page'       => $page,
            'page_size'  => $size,
            'page_count' => (int)ceil($total / $size),
        ];
    }

    // ========== 单条证书详情 ==========

    public static function getCertificateById(int $id): ?array {
        $db = self::db();
        $stmt = $db->prepare(
            "SELECT c.*, t.type_name, t.type_nickname, t.type_color, t.icon, t.description,
                    t.strengths, t.weaknesses, t.careers, t.celebrities
             FROM certificates c
             LEFT JOIN mbti_types t ON c.mbti_type = t.type_code
             WHERE c.id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        foreach (['scores','result_detail','strengths','weaknesses','careers','celebrities'] as $field) {
            if (isset($row[$field]) && is_string($row[$field])) {
                $row[$field] = json_decode($row[$field], true);
            }
        }
        return $row;
    }

    // ========== 删除证书 ==========

    public static function deleteCertificate(int $id): bool {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM certificates WHERE id = ?");
        return $stmt->execute([$id]) && $stmt->rowCount() > 0;
    }

    public static function deleteCertificates(array $ids): int {
        if (empty($ids)) return 0;
        $db = self::db();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("DELETE FROM certificates WHERE id IN ($placeholders)");
        $intIds = array_map('intval', $ids);
        $stmt->execute($intIds);
        return $stmt->rowCount();
    }

    // ========== 题目管理 ==========

    public static function getAllQuestions(string $dimension = ''): array {
        $db = self::db();
        if ($dimension) {
            $stmt = $db->prepare("SELECT * FROM questions WHERE dimension = ? ORDER BY FIELD(dimension,'EI','SN','TF','JP'), sort_order");
            $stmt->execute([$dimension]);
        } else {
            $stmt = $db->query("SELECT * FROM questions ORDER BY FIELD(dimension,'EI','SN','TF','JP'), sort_order");
        }
        return $stmt->fetchAll();
    }

    public static function toggleQuestion(int $id, bool $active): bool {
        $db = self::db();
        $stmt = $db->prepare("UPDATE questions SET is_active = ? WHERE id = ?");
        return $stmt->execute([$active ? 1 : 0, $id]);
    }

    // ========== 统计分析 ==========

    public static function getDailyTrend(int $days = 30): array {
        $db = self::db();
        $stmt = $db->prepare(
            "SELECT DATE(created_at) as day, COUNT(*) as cnt
             FROM certificates
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(created_at) ORDER BY day ASC"
        );
        $stmt->execute([$days - 1]);
        return $stmt->fetchAll();
    }

    public static function getTypeRanking(): array {
        $db = self::db();
        $stmt = $db->query(
            "SELECT c.mbti_type, COUNT(*) as cnt, t.type_name, t.type_color, t.icon
             FROM certificates c
             LEFT JOIN mbti_types t ON c.mbti_type = t.type_code
             GROUP BY c.mbti_type
             ORDER BY cnt DESC"
        );
        return $stmt->fetchAll();
    }

    public static function getHourlyDistribution(): array {
        $db = self::db();
        $stmt = $db->query(
            "SELECT HOUR(created_at) as h, COUNT(*) as cnt
             FROM certificates GROUP BY HOUR(created_at) ORDER BY h"
        );
        return $stmt->fetchAll();
    }

    public static function getDimensionBalance(): array {
        $db = self::db();
        $stmt = $db->query("SELECT mbti_type FROM certificates");
        $all = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $scores = ['E'=>0,'I'=>0,'S'=>0,'N'=>0,'T'=>0,'F'=>0,'J'=>0,'P'=>0];
        foreach ($all as $type) {
            if (strlen($type) === 4) {
                foreach (str_split($type) as $letter) {
                    if (isset($scores[$letter])) $scores[$letter]++;
                }
            }
        }
        return $scores;
    }
}
