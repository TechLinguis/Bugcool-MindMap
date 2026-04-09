<?php
/**
 * 题目管理页面
 */
$pageTitle = '题目管理';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

// ---- AJAX 处理：启用/禁用 ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        echo json_encode(['ok' => false, 'msg' => 'CSRF 验证失败']);
        exit;
    }

    if ($_POST['action'] === 'toggle') {
        $id     = (int)($_POST['id'] ?? 0);
        $active = (int)($_POST['active'] ?? 1);
        $ok     = AdminDB::toggleQuestion($id, (bool)$active);
        echo json_encode(['ok' => $ok]);
        exit;
    }
}

// ---- 读取题目 ----
$dimFilter = in_array($_GET['dim'] ?? '', ['EI','SN','TF','JP','']) ? ($_GET['dim'] ?? '') : '';
$questions = AdminDB::getAllQuestions($dimFilter);

// 按维度分组统计
$dimStats = ['EI' => ['total'=>0,'active'=>0], 'SN' => ['total'=>0,'active'=>0],
             'TF' => ['total'=>0,'active'=>0], 'JP' => ['total'=>0,'active'=>0]];
foreach ($questions as $q) {
    $d = strtoupper($q['dimension'] ?? '');
    if (isset($dimStats[$d])) {
        $dimStats[$d]['total']++;
        if ($q['is_active']) $dimStats[$d]['active']++;
    }
}

$dimColors = ['EI' => '#22D3EE', 'SN' => '#34D399', 'TF' => '#818CF8', 'JP' => '#FBBF24'];
$dimNames  = ['EI' => '内外倾向 E/I', 'SN' => '感知方式 S/N', 'TF' => '决策方式 T/F', 'JP' => '生活方式 J/P'];

$csrf = csrf_token();
include __DIR__ . '/header.php';
?>

<!-- ===== 页面标题 ===== -->
<div class="page-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <div>
    <div class="page-title"><i class="bi bi-list-check"></i>题目管理</div>
    <div class="page-sub">共 <strong style="color:var(--t1)"><?= count($questions) ?></strong> 道题目</div>
  </div>
</div>

<!-- ===== 维度统计卡片 ===== -->
<div class="row g-3" style="margin-bottom:16px">
    <?php foreach ($dimStats as $dim => $s):
        $dc  = $dimColors[$dim];
        $pct = $s['total'] > 0 ? round($s['active'] / $s['total'] * 100) : 0;
    ?>
    <div class="col-6 col-lg-3">
        <div class="card" style="padding:16px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                <span style="font-size:.8rem;font-weight:700;color:<?= $dc ?>;letter-spacing:1px"><?= $dim ?></span>
                <span style="font-size:.72rem;color:var(--t3)"><?= $dimNames[$dim] ?></span>
            </div>
            <div style="display:flex;align-items:baseline;gap:5px;margin-bottom:8px">
                <span style="font-size:1.5rem;font-weight:700;color:<?= $dc ?>"><?= $s['active'] ?></span>
                <span style="font-size:.78rem;color:var(--t3)">/ <?= $s['total'] ?> 启用</span>
            </div>
            <div style="height:4px;background:var(--b1);border-radius:2px;overflow:hidden">
                <div style="height:100%;width:<?= $pct ?>%;background:<?= $dc ?>;border-radius:2px;transition:width .4s"></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== 维度筛选 Tab ===== -->
<div class="card" style="padding:0;overflow:hidden;margin-bottom:16px">
    <div style="display:flex;border-bottom:1px solid var(--b1)">
        <?php
        $tabs = ['' => '全部', 'EI' => 'E/I', 'SN' => 'S/N', 'TF' => 'T/F', 'JP' => 'J/P'];
        foreach ($tabs as $key => $label):
            $active = ($dimFilter === $key);
        ?>
        <a href="questions.php<?= $key ? '?dim=' . $key : '' ?>"
           style="padding:11px 18px;font-size:.85rem;font-weight:<?= $active ? '700' : '500' ?>;
                  color:<?= $active ? 'var(--p)' : 'var(--t3)' ?>;
                  text-decoration:none;border-bottom:2px solid <?= $active ? 'var(--p)' : 'transparent' ?>;
                  transition:all .2s;white-space:nowrap;display:inline-flex;align-items:center;gap:5px">
            <?php if ($key && isset($dimColors[$key])): ?>
            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:<?= $dimColors[$key] ?>"></span>
            <?php endif; ?>
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- 题目表格 -->
    <?php if (empty($questions)): ?>
    <div class="empty">
        <div class="empty-icon"><i class="bi bi-inbox"></i></div>
        <div class="empty-title">暂无题目</div>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th><?= $dimFilter ? '' : '维度' ?></th>
                    <th>题目内容</th>
                    <th style="width:150px">选项 A</th>
                    <th style="width:150px">选项 B</th>
                    <th style="width:90px;text-align:center">启用</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($questions as $q):
                $dim = strtoupper($q['dimension'] ?? '');
                $dc  = $dimColors[$dim] ?? '#818CF8';
                $txt = $q['question_text'] ?? $q['question'] ?? '';
            ?>
                <tr class="<?= $q['is_active'] ? '' : 'q-disabled' ?>" data-id="<?= $q['id'] ?>">
                    <td>
                        <span class="type-chip" style="background:<?= $dc ?>18;color:<?= $dc ?>;border-color:<?= $dc ?>35">
                            <?= htmlspecialchars($dim) ?>
                        </span>
                    </td>
                    <td style="max-width:320px">
                        <span style="color:<?= $q['is_active'] ? 'var(--t1)' : 'var(--t4)' ?>;font-size:.875rem">
                            <?= htmlspecialchars($txt) ?>
                        </span>
                    </td>
                    <td style="color:var(--t3);font-size:.8rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                        title="<?= htmlspecialchars($q['option_a'] ?? '') ?>">
                        <?= htmlspecialchars($q['option_a'] ?? '') ?>
                    </td>
                    <td style="color:var(--t3);font-size:.8rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                        title="<?= htmlspecialchars($q['option_b'] ?? '') ?>">
                        <?= htmlspecialchars($q['option_b'] ?? '') ?>
                    </td>
                    <td style="text-align:center">
                        <label class="toggle-switch" title="<?= $q['is_active'] ? '点击禁用' : '点击启用' ?>">
                            <input type="checkbox" class="toggle-input" data-id="<?= $q['id'] ?>"
                                   <?= $q['is_active'] ? 'checked' : '' ?> onchange="toggleQuestion(this)">
                            <span class="toggle-track"></span>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Toast 提示 -->
<div id="toast" style="position:fixed;top:20px;right:20px;padding:10px 16px;border-radius:var(--rs);
     background:var(--bg-c2);border:1px solid var(--b);color:var(--t1);
     font-size:.85rem;display:none;align-items:center;gap:8px;z-index:9999;
     box-shadow:0 4px 20px rgba(0,0,0,.5);backdrop-filter:blur(4px)">
    <span id="toastIcon" style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;font-size:.7rem"></span>
    <span id="toastMsg"></span>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<style>
/* 切换开关 */
.toggle-switch { cursor: pointer; display: inline-flex; }
.toggle-input  { display: none; }
.toggle-track {
    display: inline-block;
    width: 38px; height: 22px;
    background: var(--b1);
    border: 1px solid var(--b1);
    border-radius: 11px;
    position: relative;
    transition: background .25s, border-color .25s;
}
.toggle-track::after {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 14px; height: 14px;
    background: var(--t4);
    border-radius: 50%;
    transition: transform .25s, background .25s;
}
.toggle-input:checked + .toggle-track {
    background: var(--p-dim);
    border-color: var(--p-dim);
}
.toggle-input:checked + .toggle-track::after {
    transform: translateX(16px);
    background: #fff;
}
.q-disabled td { opacity: .45; }
</style>

<script>
const CSRF = <?= json_encode($csrf) ?>;

function toggleQuestion(el) {
    const id     = el.dataset.id;
    const active = el.checked ? 1 : 0;
    const row    = el.closest('tr');

    fetch('questions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'toggle', id, active, csrf_token: CSRF })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            row.classList.toggle('q-disabled', !active);
            showToast(active ? '✓ 已启用' : '— 已禁用', active ? 'emerald' : 'muted');
        } else {
            el.checked = !el.checked;
            showToast('操作失败，请重试', 'red');
        }
    })
    .catch(() => { el.checked = !el.checked; showToast('网络错误', 'red'); });
}

function showToast(msg, color) {
    const t  = document.getElementById('toast');
    const ic = document.getElementById('toastIcon');
    const ms = document.getElementById('toastMsg');
    const map = {
        emerald: { bg: 'rgba(16,185,129,.18)',  color: '#10b981', icon: '✓' },
        muted:  { bg: 'rgba(100,116,139,.18)', color: 'var(--t2)', icon: '–' },
        red:    { bg: 'rgba(244,63,94,.18)',   color: '#f43f5e', icon: '✗' }
    };
    const c = map[color] || map.muted;
    ic.style.background = c.bg;
    ic.style.color = c.color;
    ic.textContent = c.icon;
    ms.textContent = msg;
    t.style.display = 'flex';
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.style.display = 'none', 2200);
}
</script>
