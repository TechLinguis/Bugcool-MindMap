<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
admin_require_login();

// ── 批量删除（POST）────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'batch_delete') {
    if (!csrf_verify($_POST['csrf'] ?? '')) {
        $flash = ['err', 'CSRF 验证失败'];
    } else {
        $ids   = array_filter(array_map('intval', $_POST['ids'] ?? []));
        $count = $ids ? AdminDB::deleteCertificates($ids) : 0;
        $flash = ['ok', "已删除 {$count} 条记录"];
    }
    header('Location: users.php?' . http_build_query(array_filter([
        'search'    => $_POST['search']    ?? '',
        'type'      => $_POST['type']      ?? '',
        'date_from' => $_POST['date_from'] ?? '',
        'date_to'   => $_POST['date_to']   ?? '',
        'page'      => $_POST['page']      ?? 1,
        'flash'     => $flash[1],
        'ftype'     => $flash[0],
    ])));
    exit;
}

// ── 单条删除（GET）────────────────────────────────────────────
if (($_GET['action'] ?? '') === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if (csrf_verify($_GET['csrf'] ?? '') && $id > 0) {
        AdminDB::deleteCertificate($id);
        $flash = ['ok', '已删除该记录'];
    } else {
        $flash = ['err', '操作失败'];
    }
    header('Location: users.php?flash=' . urlencode($flash[1]) . '&ftype=' . $flash[0]);
    exit;
}

// ── 导出 CSV ──────────────────────────────────────────────────
if (($_GET['export'] ?? '') === '1') {
    $all = AdminDB::getCertificates(['page' => 1, 'page_size_override' => 99999] + $_GET);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="mbti_users_' . date('YmdHis') . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM for Excel
    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['ID', '证书编号', '姓名', '邮箱', 'MBTI类型', 'IP地址', '测试时间']);
    foreach ($all['rows'] as $r) {
        fputcsv($fp, [$r['id'], $r['certificate_no'], $r['name'], $r['email'] ?? '', $r['mbti_type'], $r['ip_address'] ?? '', $r['created_at']]);
    }
    fclose($fp);
    exit;
}

// ── 列表查询 ──────────────────────────────────────────────────
$params = [
    'page'      => max(1, (int)($_GET['page']      ?? 1)),
    'search'    => trim($_GET['search']    ?? ''),
    'type'      => trim($_GET['type']      ?? ''),
    'date_from' => trim($_GET['date_from'] ?? ''),
    'date_to'   => trim($_GET['date_to']   ?? ''),
    'sort'      => $_GET['sort']  ?? 'id',
    'order'     => $_GET['order'] ?? 'DESC',
];
$data = AdminDB::getCertificates($params);

// Flash message from redirect
$flash     = ($_GET['flash']  ?? '') ?: null;
$flashType = ($_GET['ftype']  ?? 'ok');

$csrf = csrf_token();

// 16 MBTI types for filter
$mbtiTypes = ['INTJ','INTP','ENTJ','ENTP','INFJ','INFP','ENFJ','ENFP',
              'ISTJ','ISTP','ESTJ','ESTP','ISFJ','ISFP','ESFJ','ESFP'];

$TC = ['INTJ'=>'#818CF8','INTP'=>'#6EE7B7','ENTJ'=>'#FCA5A5','ENTP'=>'#FDE68A',
       'INFJ'=>'#C4B5FD','INFP'=>'#6EE7F0','ENFJ'=>'#86EFAC','ENFP'=>'#FCD34D',
       'ISTJ'=>'#94A3B8','ISTP'=>'#7DD3FC','ESTJ'=>'#F87171','ESTP'=>'#FB923C',
       'ISFJ'=>'#CBD5E1','ISFP'=>'#A5F3FC','ESFJ'=>'#BEF264','ESFP'=>'#FDBA74'];

$pageTitle = '用户数据';
include __DIR__ . '/header.php';
?>

<!-- ===== 页面标题 ===== -->
<div class="page-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <div>
    <div class="page-title"><i class="bi bi-people-fill"></i>用户数据</div>
    <div class="page-sub">共 <strong style="color:var(--t1)"><?= number_format($data['total']) ?></strong> 条测试记录</div>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <a href="?<?= http_build_query(array_merge($params, ['export'=>'1'])) ?>"
       class="btn btn-ghost btn-sm">
      <i class="bi bi-download"></i>导出 CSV
    </a>
  </div>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= $flashType ?>">
  <i class="bi bi-<?= $flashType === 'ok' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
  <?= htmlspecialchars($flash) ?>
</div>
<?php endif; ?>

<!-- ===== 搜索筛选栏 ===== -->
<div class="card" style="margin-bottom:16px">
  <form method="get" style="padding:16px 20px">
    <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end">

      <div style="flex:1;min-width:200px">
        <div style="font-size:.75rem;color:var(--t3);margin-bottom:5px;font-weight:600">搜索</div>
        <input class="inp" name="search" style="width:100%"
               placeholder="姓名 / 证书编号 / 邮箱"
               value="<?= htmlspecialchars($params['search']) ?>">
      </div>

      <div style="min-width:140px">
        <div style="font-size:.75rem;color:var(--t3);margin-bottom:5px;font-weight:600">人格类型</div>
        <select class="sel" name="type" style="width:100%">
          <option value="">全部类型</option>
          <?php foreach ($mbtiTypes as $t): ?>
          <option value="<?= $t ?>" <?= $params['type'] === $t ? 'selected' : '' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <div style="font-size:.75rem;color:var(--t3);margin-bottom:5px;font-weight:600">开始日期</div>
        <input class="inp" type="date" name="date_from" value="<?= htmlspecialchars($params['date_from']) ?>">
      </div>

      <div>
        <div style="font-size:.75rem;color:var(--t3);margin-bottom:5px;font-weight:600">结束日期</div>
        <input class="inp" type="date" name="date_to" value="<?= htmlspecialchars($params['date_to']) ?>">
      </div>

      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i>搜索</button>
        <a href="users.php" class="btn btn-ghost"><i class="bi bi-x-circle"></i>重置</a>
      </div>
    </div>
  </form>
</div>

<!-- ===== 表格 ===== -->
<div class="card">
  <!-- 批量操作栏 -->
  <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--b1)">
    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;color:var(--t2)">
      <input type="checkbox" class="admin-check" id="chk-all" onchange="toggleAll(this)">
      全选本页
    </label>
    <div style="display:flex;align-items:center;gap:10px">
      <span style="font-size:.8rem;color:var(--t3)">
        第 <?= $data['page'] ?> / <?= max(1,$data['page_count']) ?> 页 · 每页 <?= PAGE_SIZE ?> 条
      </span>
      <button onclick="batchDelete()" class="btn btn-danger btn-sm" id="del-btn">
        <i class="bi bi-trash3"></i>删除所选
      </button>
    </div>
  </div>

  <?php if (empty($data['rows'])): ?>
  <div class="empty">
    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
    <div class="empty-title">暂无数据</div>
    <div class="empty-sub">
      <?= $params['search'] || $params['type'] || $params['date_from'] ? '没有符合条件的记录，试试调整筛选条件' : '等待用户完成测试后显示' ?>
    </div>
  </div>
  <?php else: ?>

  <div style="overflow-x:auto">
    <table class="tbl">
      <thead>
        <tr>
          <th style="width:40px;text-align:center"></th>
          <th><?= mkSort('id', 'ID', $params) ?></th>
          <th>证书编号</th>
          <th><?= mkSort('name', '姓名', $params) ?></th>
          <th>邮箱</th>
          <th><?= mkSort('mbti_type', '类型', $params) ?></th>
          <th><?= mkSort('created_at', '测试时间', $params) ?></th>
          <th>IP</th>
          <th style="text-align:center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data['rows'] as $row):
          $color = $TC[$row['mbti_type']] ?? '#818CF8';
        ?>
        <tr>
          <td style="text-align:center">
            <input type="checkbox" class="admin-check row-check" value="<?= $row['id'] ?>">
          </td>
          <td class="mono" style="color:var(--t3)">#<?= $row['id'] ?></td>
          <td class="mono" style="font-size:.78rem;color:var(--t2)"><?= htmlspecialchars($row['certificate_no']) ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($row['name']) ?></td>
          <td style="color:var(--t3);font-size:.83rem">
            <?= $row['email'] ? htmlspecialchars($row['email']) : '<span style="color:var(--t4)">—</span>' ?>
          </td>
          <td>
            <span class="type-chip" style="background:<?= $color ?>18;color:<?= $color ?>;border-color:<?= $color ?>35">
              <?= htmlspecialchars($row['mbti_type']) ?>
            </span>
          </td>
          <td style="color:var(--t2);font-size:.83rem;white-space:nowrap"><?= date('Y/m/d H:i', strtotime($row['created_at'])) ?></td>
          <td class="mono" style="font-size:.78rem;color:var(--t3)"><?= htmlspecialchars($row['ip_address'] ?? '—') ?></td>
          <td style="text-align:center;white-space:nowrap">
            <a href="user_view.php?id=<?= $row['id'] ?>" class="btn btn-ghost btn-xs"><i class="bi bi-eye"></i>查看</a>
            <a href="users.php?action=delete&id=<?= $row['id'] ?>&csrf=<?= $csrf ?>"
               class="btn btn-danger btn-xs"
               onclick="return confirm('确认删除「<?= htmlspecialchars(addslashes($row['name'])) ?>」的测试记录？')">
              <i class="bi bi-trash3"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <!-- 分页 -->
  <?php if ($data['page_count'] > 1): ?>
  <div style="padding:14px 20px;border-top:1px solid var(--b1);display:flex;justify-content:flex-end">
    <div class="pages">
      <?php
      $urlBase = '?' . http_build_query(array_filter([
          'search'    => $params['search'],
          'type'      => $params['type'],
          'date_from' => $params['date_from'],
          'date_to'   => $params['date_to'],
          'sort'      => $params['sort'],
          'order'     => $params['order'],
      ]));
      $p    = $data['page'];
      $pMax = $data['page_count'];
      echo pagItem($urlBase . '&page=' . max(1,$p-1), '←', $p === 1);
      $range = range(max(1,$p-2), min($pMax,$p+2));
      if ($range[0] > 1)  { echo pagItem($urlBase.'&page=1','1',false,$p===1); if($range[0]>2) echo '<span style="color:var(--t4);padding:0 4px">…</span>'; }
      foreach ($range as $pg) echo pagItem($urlBase.'&page='.$pg, $pg, false, $pg===$p);
      if (end($range) < $pMax) { if(end($range)<$pMax-1) echo '<span style="color:var(--t4);padding:0 4px">…</span>'; echo pagItem($urlBase.'&page='.$pMax,$pMax,false,$p===$pMax); }
      echo pagItem($urlBase . '&page=' . min($pMax,$p+1), '→', $p >= $pMax);
      ?>
    </div>
  </div>
  <?php endif; ?>

</div>

<!-- 批量删除表单（隐藏） -->
<form id="batch-form" method="post" style="display:none">
  <input type="hidden" name="action" value="batch_delete">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
  <input type="hidden" name="search"    value="<?= htmlspecialchars($params['search']) ?>">
  <input type="hidden" name="type"      value="<?= htmlspecialchars($params['type']) ?>">
  <input type="hidden" name="date_from" value="<?= htmlspecialchars($params['date_from']) ?>">
  <input type="hidden" name="date_to"   value="<?= htmlspecialchars($params['date_to']) ?>">
  <input type="hidden" name="page"      value="<?= $data['page'] ?>">
  <div id="batch-ids"></div>
</form>

<script>
function batchDelete(){
  const ids = getCheckedIds();
  if(!ids.length){ alert('请先选择要删除的记录'); return; }
  if(!confirm(`确认删除选中的 ${ids.length} 条记录？此操作不可恢复！`)) return;
  const f = document.getElementById('batch-form');
  document.getElementById('batch-ids').innerHTML = ids.map(id=>`<input type="hidden" name="ids[]" value="${id}">`).join('');
  f.submit();
}
// 全选联动
document.querySelectorAll('.row-check').forEach(cb=>{
  cb.addEventListener('change',()=>{
    const all = document.querySelectorAll('.row-check');
    const chk = document.querySelectorAll('.row-check:checked');
    document.getElementById('chk-all').indeterminate = chk.length > 0 && chk.length < all.length;
    document.getElementById('chk-all').checked = chk.length === all.length;
  });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

<?php
// ── 辅助函数 ─────────────────────────────────────────────────
function mkSort(string $col, string $label, array $p): string {
    $cur   = $p['sort'] === $col;
    $order = ($cur && $p['order'] === 'ASC') ? 'DESC' : 'ASC';
    $icon  = $cur ? ($p['order']==='ASC' ? '↑' : '↓') : '';
    $qs    = http_build_query(array_merge($p, ['sort'=>$col,'order'=>$order,'page'=>1]));
    return "<a href='users.php?{$qs}' style='color:inherit;text-decoration:none;white-space:nowrap'>{$label} <span style='color:var(--p)'>{$icon}</span></a>";
}
function pagItem(string $href, string $label, bool $dis, bool $cur=false): string {
    $cls = $cur ? 'page-item cur' : ($dis ? 'page-item dis' : 'page-item');
    return "<a href='{$href}' class='{$cls}'>{$label}</a>";
}
?>
