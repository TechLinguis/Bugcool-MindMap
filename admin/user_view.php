<?php
/**
 * 证书详情页
 */
$pageTitle = '用户详情';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/header.php';

$id  = (int)($_GET['id'] ?? 0);
$row = $id ? AdminDB::getCertificateById($id) : null;

if (!$row) {
    echo '<div class="alert-admin alert-danger"><i class="bi bi-exclamation-circle-fill"></i> 记录不存在或已被删除。
          <a href="users.php" style="color:inherit;margin-left:8px;">← 返回列表</a></div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$color = $row['type_color'] ?? '#818CF8';
$icon  = $row['icon']       ?? '✦';

// 维度得分
$scores = $row['scores'] ?? [];

// 4 维度对 (极性字母, 分数)
$dims = [
    ['E','I', $scores['E'] ?? 50, $scores['I'] ?? 50, '#22D3EE', '#F472B6'],
    ['S','N', $scores['S'] ?? 50, $scores['N'] ?? 50, '#34D399', '#FBBF24'],
    ['T','F', $scores['T'] ?? 50, $scores['F'] ?? 50, '#818CF8', '#FB923C'],
    ['J','P', $scores['J'] ?? 50, $scores['P'] ?? 50, '#6366F1', '#A78BFA'],
];

$csrf = csrf_token();
?>

<!-- 面包屑 + 操作按钮 -->
<div class="d-flex align-items-center justify-content-between" style="margin-bottom:20px;">
    <nav style="font-size:0.85rem;color:var(--text-3);">
        <a href="users.php" style="color:var(--text-3);text-decoration:none;">用户数据</a>
        <span style="margin:0 6px;">/</span>
        <span style="color:var(--text-1);"><?= htmlspecialchars($row['name']) ?></span>
    </nav>
    <div style="display:flex;gap:8px;">
        <a href="users.php" class="btn-admin btn-ghost-a btn-sm-a">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
        <button class="btn-admin btn-danger-a btn-sm-a"
                onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'])) ?>')">
            <i class="bi bi-trash3"></i> 删除记录
        </button>
    </div>
</div>

<div class="row g-3">

    <!-- 左列：用户信息 + 类型概览 -->
    <div class="col-lg-4">

        <!-- 人格卡片 -->
        <div class="admin-card" style="text-align:center;padding:28px 20px;background:linear-gradient(135deg,<?= $color ?>18,transparent);">
            <div style="font-size:3.5rem;line-height:1;margin-bottom:8px;"><?= $icon ?></div>
            <div style="font-size:2.2rem;font-weight:900;color:<?= $color ?>;letter-spacing:2px;margin-bottom:4px;">
                <?= htmlspecialchars($row['mbti_type']) ?>
            </div>
            <div style="font-size:1rem;color:var(--text-2);font-weight:600;margin-bottom:4px;">
                <?= htmlspecialchars($row['type_name'] ?? '') ?>
            </div>
            <?php if (!empty($row['type_nickname'])): ?>
            <div style="font-size:0.83rem;color:var(--text-3);">「<?= htmlspecialchars($row['type_nickname']) ?>」</div>
            <?php endif; ?>
        </div>

        <!-- 基本信息 -->
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title"><i class="bi bi-person-vcard"></i> 基本信息</span>
            </div>
            <table style="width:100%;font-size:0.875rem;border-collapse:collapse;">
                <?php
                $fields = [
                    ['ID',       '#' . $row['id']],
                    ['姓名',     htmlspecialchars($row['name'])],
                    ['邮箱',     $row['email'] ? htmlspecialchars($row['email']) : '<span style="color:var(--text-muted);">未填写</span>'],
                    ['证书编号', '<span style="font-family:monospace;font-size:0.8rem;">' . htmlspecialchars($row['certificate_no']) . '</span>'],
                    ['IP 地址',  '<span style="font-family:monospace;">' . htmlspecialchars($row['ip_address'] ?? '—') . '</span>'],
                    ['测试时间', date('Y-m-d H:i:s', strtotime($row['created_at']))],
                ];
                foreach ($fields as [$label, $val]):
                ?>
                <tr>
                    <td style="padding:8px 0;color:var(--text-3);width:72px;vertical-align:top;"><?= $label ?></td>
                    <td style="padding:8px 0 8px 8px;color:var(--text-1);border-bottom:1px solid var(--border-1);"><?= $val ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- 右列：维度分析 + 解读 -->
    <div class="col-lg-8">

        <!-- 维度得分 -->
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title"><i class="bi bi-bar-chart-horizontal"></i> 四维度得分</span>
            </div>

            <?php foreach ($dims as [$l1, $l2, $v1, $v2, $c1, $c2]): ?>
            <?php
            $total = max(1, $v1 + $v2);
            $pct1  = round($v1 / $total * 100);
            $pct2  = 100 - $pct1;
            $dominant = $v1 >= $v2 ? $l1 : $l2;
            $dominantColor = $v1 >= $v2 ? $c1 : $c2;
            ?>
            <div style="margin-bottom:18px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1rem;font-weight:800;color:<?= $c1 ?>;width:20px;"><?= $l1 ?></span>
                        <span class="type-badge" style="background:<?= $dominantColor ?>22;color:<?= $dominantColor ?>;border:1px solid <?= $dominantColor ?>44;font-size:0.7rem;">
                            <?= $dominant ?> 倾向
                        </span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:0.82rem;color:var(--text-3);"><?= $pct1 ?>%</span>
                        <span style="font-size:0.75rem;color:var(--text-muted);">vs</span>
                        <span style="font-size:0.82rem;color:var(--text-3);"><?= $pct2 ?>%</span>
                        <span style="font-size:1rem;font-weight:800;color:<?= $c2 ?>;width:20px;text-align:right;"><?= $l2 ?></span>
                    </div>
                </div>
                <!-- 双向进度条 -->
                <div style="display:flex;height:8px;border-radius:4px;overflow:hidden;gap:2px;">
                    <div style="width:<?= $pct1 ?>%;background:<?= $c1 ?>;border-radius:4px 0 0 4px;transition:width .8s;"></div>
                    <div style="width:<?= $pct2 ?>%;background:<?= $c2 ?>;border-radius:0 4px 4px 0;transition:width .8s;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- 类型描述 -->
        <?php if (!empty($row['description'])): ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title"><i class="bi bi-file-text"></i> 类型描述</span>
            </div>
            <p style="color:var(--text-2);font-size:0.9rem;line-height:1.8;margin:0;">
                <?= htmlspecialchars($row['description']) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- 优势 & 弱点 -->
        <div class="row g-3">
            <?php if (!empty($row['strengths'])): ?>
            <div class="col-md-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header">
                        <span class="admin-card-title"><i class="bi bi-stars text-emerald"></i> 优势特质</span>
                    </div>
                    <ul style="margin:0;padding-left:18px;color:var(--text-2);font-size:0.875rem;line-height:2;">
                        <?php foreach ((array)$row['strengths'] as $s): ?>
                        <li><?= htmlspecialchars($s) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($row['weaknesses'])): ?>
            <div class="col-md-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header">
                        <span class="admin-card-title"><i class="bi bi-lightning-charge text-amber"></i> 待改进点</span>
                    </div>
                    <ul style="margin:0;padding-left:18px;color:var(--text-2);font-size:0.875rem;line-height:2;">
                        <?php foreach ((array)$row['weaknesses'] as $w): ?>
                        <li><?= htmlspecialchars($w) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- 职业方向 -->
        <?php if (!empty($row['careers'])): ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title"><i class="bi bi-briefcase"></i> 适合职业</span>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <?php foreach ((array)$row['careers'] as $c): ?>
                <span style="padding:4px 12px;border-radius:999px;background:var(--bg-card2);border:1px solid var(--border-2);color:var(--text-2);font-size:0.82rem;">
                    <?= htmlspecialchars($c) ?>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 知名人物 -->
        <?php if (!empty($row['celebrities'])): ?>
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="admin-card-title"><i class="bi bi-person-badge"></i> 同类型名人</span>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <?php foreach ((array)$row['celebrities'] as $cel): ?>
                <span style="padding:4px 12px;border-radius:999px;background:<?= $color ?>18;border:1px solid <?= $color ?>33;color:<?= $color ?>;font-size:0.82rem;">
                    <?= htmlspecialchars($cel) ?>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /右列 -->
</div><!-- /row -->

<!-- 删除表单 -->
<form id="deleteForm" method="POST" action="users.php" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="action" value="delete_one">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
</form>

<?php require_once __DIR__ . '/footer.php'; ?>

<script>
function confirmDelete(id, name) {
    if (confirm(`确认删除「${name}」的测试记录？此操作不可恢复！`)) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
