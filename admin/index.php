<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
admin_require_login();

$stats    = AdminDB::getOverviewStats();
$typeRank = AdminDB::getTypeRanking();
$hourly   = AdminDB::getHourlyDistribution();

// 补全7天趋势
$trendMap = [];
foreach ($stats['daily_7d'] as $r) $trendMap[$r['day']] = (int)$r['cnt'];
$t7Labels = []; $t7Values = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $t7Labels[] = date('m/d', strtotime($d));
    $t7Values[] = $trendMap[$d] ?? 0;
}

// 补全24h
$hMap = [];
foreach ($hourly as $r) $hMap[(int)$r['h']] = (int)$r['cnt'];
$hVals = [];
for ($h = 0; $h < 24; $h++) $hVals[] = $hMap[$h] ?? 0;

// 类型颜色
$TC = ['INTJ'=>'#818CF8','INTP'=>'#6EE7B7','ENTJ'=>'#FCA5A5','ENTP'=>'#FDE68A',
       'INFJ'=>'#C4B5FD','INFP'=>'#6EE7F0','ENFJ'=>'#86EFAC','ENFP'=>'#FCD34D',
       'ISTJ'=>'#94A3B8','ISTP'=>'#7DD3FC','ESTJ'=>'#F87171','ESTP'=>'#FB923C',
       'ISFJ'=>'#CBD5E1','ISFP'=>'#A5F3FC','ESFJ'=>'#BEF264','ESFP'=>'#FDBA74'];

$total  = $stats['total'] ?: 1;
$rankMax = !empty($typeRank) ? (int)$typeRank[0]['cnt'] : 1;

$pageTitle = '仪表盘';
include __DIR__ . '/header.php';
?>

<!-- ===== 页面标题 ===== -->
<div class="page-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <div>
    <div class="page-title"><i class="bi bi-grid-1x2-fill"></i>仪表盘</div>
    <div class="page-sub">数据总览 · <?= date('Y年m月d日') ?></div>
  </div>
  <a href="../index.php" target="_blank" class="btn btn-ghost btn-sm">
    <i class="bi bi-box-arrow-up-right"></i>查看前台
  </a>
</div>

<!-- ===== 核心统计卡片 ===== -->
<div class="stat-grid">

  <div class="stat-card" style="--card-glow:rgba(129,140,248,.1)">
    <div class="stat-card-top">
      <div>
        <div class="stat-num"><?= number_format($stats['total']) ?></div>
        <div class="stat-label">累计测试人数</div>
      </div>
      <div class="stat-icon" style="background:rgba(129,140,248,.12)">
        <i class="bi bi-people-fill" style="color:#818CF8"></i>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:6px">
      <div class="prog" style="flex:1"><div class="prog-fill" style="width:100%;background:linear-gradient(90deg,#6366F1,#818CF8)"></div></div>
    </div>
  </div>

  <div class="stat-card" style="--card-glow:rgba(52,211,153,.08)">
    <div class="stat-card-top">
      <div>
        <div class="stat-num" style="color:#34D399"><?= number_format($stats['today']) ?></div>
        <div class="stat-label">今日测试</div>
      </div>
      <div class="stat-icon" style="background:rgba(52,211,153,.12)">
        <i class="bi bi-lightning-charge-fill" style="color:#34D399"></i>
      </div>
    </div>
    <?php $todayPct = $stats['total'] > 0 ? min(100, round($stats['today'] / $stats['total'] * 100)) : 0; ?>
    <div class="prog"><div class="prog-fill" style="width:<?= $todayPct ?>%;background:#34D399"></div></div>
  </div>

  <div class="stat-card" style="--card-glow:rgba(251,191,36,.08)">
    <div class="stat-card-top">
      <div>
        <div class="stat-num" style="color:#FBBF24"><?= number_format($stats['this_week']) ?></div>
        <div class="stat-label">本周测试</div>
      </div>
      <div class="stat-icon" style="background:rgba(251,191,36,.12)">
        <i class="bi bi-calendar-week-fill" style="color:#FBBF24"></i>
      </div>
    </div>
    <?php $wPct = $stats['total'] > 0 ? min(100, round($stats['this_week'] / $stats['total'] * 100)) : 0; ?>
    <div class="prog"><div class="prog-fill" style="width:<?= $wPct ?>%;background:#FBBF24"></div></div>
  </div>

  <div class="stat-card" style="--card-glow:rgba(244,114,182,.08)">
    <div class="stat-card-top">
      <div>
        <div class="stat-num" style="color:#F472B6"><?= number_format($stats['this_month']) ?></div>
        <div class="stat-label">本月测试</div>
      </div>
      <div class="stat-icon" style="background:rgba(244,114,182,.12)">
        <i class="bi bi-calendar-month-fill" style="color:#F472B6"></i>
      </div>
    </div>
    <?php $mPct = $stats['total'] > 0 ? min(100, round($stats['this_month'] / $stats['total'] * 100)) : 0; ?>
    <div class="prog"><div class="prog-fill" style="width:<?= $mPct ?>%;background:#F472B6"></div></div>
  </div>

</div>

<!-- ===== 趋势图 + 24h ===== -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-activity"></i>近 7 天测试趋势</div>
      <span style="font-size:.78rem;color:var(--t3)">日均 <?= $stats['total'] > 0 ? round(array_sum($t7Values) / 7, 1) : 0 ?> 人</span>
    </div>
    <div class="card-body" style="padding:16px 20px 20px">
      <canvas id="c7d" style="width:100%;height:160px;display:block"></canvas>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-clock-history"></i>24 小时活跃分布</div>
      <a href="stats.php" style="font-size:.78rem;color:var(--t3)">查看详情 →</a>
    </div>
    <div class="card-body" style="padding:16px 20px 20px">
      <canvas id="c24h" style="width:100%;height:160px;display:block"></canvas>
    </div>
  </div>

</div>

<!-- ===== 类型排行榜 + 快捷操作 ===== -->
<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;margin-bottom:20px">

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-trophy-fill" style="color:#FBBF24"></i>人格类型热度排行</div>
      <a href="stats.php" class="btn btn-ghost btn-xs">完整分析 →</a>
    </div>
    <div class="card-body">
      <?php if (empty($typeRank)): ?>
      <div class="empty">
        <div class="empty-icon"><i class="bi bi-bar-chart"></i></div>
        <div class="empty-title">暂无数据</div>
        <div class="empty-sub">等待用户完成测试后显示</div>
      </div>
      <?php else: ?>
      <?php foreach (array_slice($typeRank, 0, 8) as $i => $row):
        $color  = $TC[$row['mbti_type']] ?? '#818CF8';
        $pct    = round($row['cnt'] / $total * 100, 1);
        $barPct = round($row['cnt'] / $rankMax * 100);
      ?>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
        <span style="width:20px;text-align:right;font-size:.78rem;color:var(--t4);flex-shrink:0"><?= $i+1 ?></span>
        <span class="type-chip" style="background:<?= $color ?>18;color:<?= $color ?>;border-color:<?= $color ?>35;width:50px;text-align:center;flex-shrink:0"><?= htmlspecialchars($row['mbti_type']) ?></span>
        <div style="flex:1;min-width:0">
          <div style="height:8px;background:var(--bg-c2);border-radius:4px;overflow:hidden">
            <div style="width:<?= $barPct ?>%;height:100%;background:<?= $color ?>;border-radius:4px;transition:width .7s"></div>
          </div>
        </div>
        <span style="font-size:.82rem;font-weight:700;color:var(--t1);width:32px;text-align:right;flex-shrink:0"><?= $row['cnt'] ?></span>
        <span style="font-size:.75rem;color:var(--t3);width:38px;flex-shrink:0"><?= $pct ?>%</span>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- 右侧：关键指标 + 快捷操作 -->
  <div style="display:flex;flex-direction:column;gap:16px">

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="bi bi-lightning-charge"></i>关键指标</div>
      </div>
      <div class="card-body" style="padding:14px 20px">
        <?php
        $rows2 = [
            ['今日 / 总量',   $stats['today'],      $stats['total'],      '#34D399'],
            ['本周 / 总量',   $stats['this_week'],   $stats['total'],      '#FBBF24'],
            ['本月 / 总量',   $stats['this_month'],  $stats['total'],      '#F472B6'],
            ['有邮箱 / 总量', $stats['with_email'],  $stats['total'],      '#22D3EE'],
        ];
        foreach ($rows2 as [$label, $a, $b, $color]):
            $p = $b > 0 ? round($a / $b * 100, 1) : 0;
        ?>
        <div style="margin-bottom:14px">
          <div style="display:flex;justify-content:space-between;margin-bottom:5px">
            <span style="font-size:.82rem;color:var(--t2)"><?= $label ?></span>
            <span style="font-size:.82rem;font-weight:700;color:<?= $color ?>"><?= $p ?>%</span>
          </div>
          <div class="prog">
            <div class="prog-fill" style="width:<?= $p ?>%;background:<?= $color ?>"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="bi bi-rocket-takeoff"></i>快捷操作</div>
      </div>
      <div class="card-body" style="padding:14px 20px;display:flex;flex-direction:column;gap:8px">
        <a href="users.php" class="btn btn-ghost" style="justify-content:flex-start">
          <i class="bi bi-people"></i>管理用户数据
        </a>
        <a href="stats.php" class="btn btn-ghost" style="justify-content:flex-start">
          <i class="bi bi-bar-chart-line"></i>查看数据分析
        </a>
        <a href="questions.php" class="btn btn-ghost" style="justify-content:flex-start">
          <i class="bi bi-card-checklist"></i>管理测试题目
        </a>
        <a href="users.php?export=1" class="btn btn-ghost" style="justify-content:flex-start">
          <i class="bi bi-download"></i>导出 CSV 数据
        </a>
      </div>
    </div>

  </div>
</div>

<!-- ===== Charts JS ===== -->
<script>
const DPR = window.devicePixelRatio || 1;
function mkCtx(id) {
  const c = document.getElementById(id);
  if (!c) return null;
  const r = c.getBoundingClientRect();
  c.width  = r.width  * DPR;
  c.height = r.height * DPR;
  const ctx = c.getContext('2d');
  ctx.scale(DPR, DPR);
  c._w = r.width; c._h = r.height;
  return ctx;
}
const GRID = 'rgba(255,255,255,.05)';
const LBL  = 'rgba(255,255,255,.38)';
const FONT = '11px system-ui,sans-serif';

// ── 7天趋势线图 ──────────────────────────────────────────────
(function(){
  const labels = <?= json_encode($t7Labels) ?>;
  const vals   = <?= json_encode($t7Values) ?>;
  setTimeout(()=>{
    const ctx = mkCtx('c7d'); if(!ctx) return;
    const W=ctx.canvas._w, H=ctx.canvas._h;
    const P={t:12,r:12,b:32,l:38};
    const cW=W-P.l-P.r, cH=H-P.t-P.b;
    const maxV=Math.max(...vals,1);
    const sx=cW/(labels.length-1||1);

    // 网格
    for(let i=0;i<=4;i++){
      const y=P.t+cH-cH/4*i;
      ctx.strokeStyle=GRID; ctx.lineWidth=1;
      ctx.beginPath(); ctx.moveTo(P.l,y); ctx.lineTo(P.l+cW,y); ctx.stroke();
      ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='right';
      ctx.fillText(Math.round(maxV/4*i), P.l-5, y+4);
    }

    // 渐变面积
    const grad=ctx.createLinearGradient(0,P.t,0,P.t+cH);
    grad.addColorStop(0,'rgba(99,102,241,.4)');
    grad.addColorStop(1,'rgba(99,102,241,.0)');
    ctx.fillStyle=grad;
    ctx.beginPath();
    vals.forEach((v,i)=>{
      const x=P.l+i*sx, y=P.t+cH-(v/maxV)*cH;
      i===0?ctx.moveTo(x,P.t+cH):null;
      ctx.lineTo(x,y);
    });
    ctx.lineTo(P.l+(vals.length-1)*sx, P.t+cH);
    ctx.closePath(); ctx.fill();

    // 线
    ctx.strokeStyle='#818CF8'; ctx.lineWidth=2.5;
    ctx.lineJoin='round'; ctx.lineCap='round';
    ctx.beginPath();
    vals.forEach((v,i)=>{
      const x=P.l+i*sx, y=P.t+cH-(v/maxV)*cH;
      i===0?ctx.moveTo(x,y):ctx.lineTo(x,y);
    });
    ctx.stroke();

    // 点
    vals.forEach((v,i)=>{
      const x=P.l+i*sx, y=P.t+cH-(v/maxV)*cH;
      ctx.beginPath(); ctx.arc(x,y,3.5,0,Math.PI*2);
      ctx.fillStyle='#818CF8'; ctx.fill();
      ctx.strokeStyle='#070711'; ctx.lineWidth=2; ctx.stroke();
    });

    // X标签
    ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='center';
    labels.forEach((lb,i)=> ctx.fillText(lb, P.l+i*sx, P.t+cH+16));
  },60);
})();

// ── 24h 柱状图 ────────────────────────────────────────────────
(function(){
  const vals = <?= json_encode($hVals) ?>;
  setTimeout(()=>{
    const ctx = mkCtx('c24h'); if(!ctx) return;
    const W=ctx.canvas._w, H=ctx.canvas._h;
    const P={t:12,r:8,b:32,l:38};
    const cW=W-P.l-P.r, cH=H-P.t-P.b;
    const maxV=Math.max(...vals,1);
    const bw=cW/24;

    // 网格
    for(let i=0;i<=4;i++){
      const y=P.t+cH-cH/4*i;
      ctx.strokeStyle=GRID; ctx.lineWidth=1;
      ctx.beginPath(); ctx.moveTo(P.l,y); ctx.lineTo(P.l+cW,y); ctx.stroke();
      ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='right';
      ctx.fillText(Math.round(maxV/4*i), P.l-5, y+4);
    }

    // 柱
    vals.forEach((v,i)=>{
      const h=Math.max(v>0?3:0,(v/maxV)*cH);
      const x=P.l+i*bw+bw*.15, bW=bw*.7;
      const y=P.t+cH-h;
      const grad=ctx.createLinearGradient(0,y,0,P.t+cH);
      grad.addColorStop(0,'#C4B5FD'); grad.addColorStop(1,'rgba(196,181,253,.25)');
      ctx.fillStyle=grad;
      const r=Math.min(3,bW/2);
      ctx.beginPath();
      ctx.moveTo(x+r,y); ctx.lineTo(x+bW-r,y);
      ctx.arcTo(x+bW,y,x+bW,y+r,r); ctx.lineTo(x+bW,P.t+cH);
      ctx.lineTo(x,P.t+cH); ctx.arcTo(x,y,x+r,y,r);
      ctx.closePath(); ctx.fill();
    });

    // X标签（0/6/12/18/23）
    ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='center';
    [0,6,12,18,23].forEach(i=>{
      ctx.fillText(i+'h', P.l+i*bw+bw/2, P.t+cH+16);
    });
  },60);
})();
</script>

<style>
@media(max-width:900px){
  .stat-grid{grid-template-columns:repeat(2,1fr)!important}
  #trend-hourly-grid{grid-template-columns:1fr!important}
  #rank-quick-grid{grid-template-columns:1fr!important}
}
</style>

<?php include __DIR__ . '/footer.php'; ?>
