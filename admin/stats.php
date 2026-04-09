<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
admin_require_login();

$range = in_array((int)($_GET['range'] ?? 30), [7,14,30,90]) ? (int)$_GET['range'] : 30;

$overview    = AdminDB::getOverviewStats();
$dailyTrend  = AdminDB::getDailyTrend($range);
$typeRanking = AdminDB::getTypeRanking();
$hourly      = AdminDB::getHourlyDistribution();
$dimBalance  = AdminDB::getDimensionBalance(); // ['E'=>n,'I'=>n,...]

// ── 补全趋势 ─────────────────────────────────────────────────
$trendMap = [];
foreach ($dailyTrend as $r) $trendMap[$r['day']] = (int)$r['cnt'];
$trendLabels = []; $trendValues = [];
for ($i = $range - 1; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $trendLabels[] = date('m/d', strtotime($d));
    $trendValues[] = $trendMap[$d] ?? 0;
}

// ── 补全24h ──────────────────────────────────────────────────
$hMap = [];
foreach ($hourly as $r) $hMap[(int)$r['h']] = (int)$r['cnt'];
$hVals = [];
for ($h = 0; $h < 24; $h++) $hVals[] = $hMap[$h] ?? 0;

// ── 类型颜色 ─────────────────────────────────────────────────
$TC = ['INTJ'=>'#818CF8','INTP'=>'#6EE7B7','ENTJ'=>'#FCA5A5','ENTP'=>'#FDE68A',
       'INFJ'=>'#C4B5FD','INFP'=>'#6EE7F0','ENFJ'=>'#86EFAC','ENFP'=>'#FCD34D',
       'ISTJ'=>'#94A3B8','ISTP'=>'#7DD3FC','ESTJ'=>'#F87171','ESTP'=>'#FB923C',
       'ISFJ'=>'#CBD5E1','ISFP'=>'#A5F3FC','ESFJ'=>'#BEF264','ESFP'=>'#FDBA74'];

// ── 四大人格组 ───────────────────────────────────────────────
$groups = [
    '分析型' => ['types'=>['INTJ','INTP','ENTJ','ENTP'],'color'=>'#818CF8'],
    '外交型' => ['types'=>['INFJ','INFP','ENFJ','ENFP'],'color'=>'#6EE7B7'],
    '守护型' => ['types'=>['ISTJ','ISFJ','ESTJ','ESFJ'],'color'=>'#FCA5A5'],
    '探索型' => ['types'=>['ISTP','ISFP','ESTP','ESFP'],'color'=>'#FDE68A'],
];
$total = $overview['total'] ?: 1;
$cntByType = [];
foreach ($typeRanking as $r) $cntByType[$r['mbti_type']] = (int)$r['cnt'];

$groupStats = [];
foreach ($groups as $gName => $g) {
    $cnt = 0;
    foreach ($g['types'] as $t) $cnt += $cntByType[$t] ?? 0;
    $groupStats[$gName] = ['cnt'=>$cnt,'color'=>$g['color'],'types'=>$g['types']];
}

// ── 维度平衡（安全读取）────────────────────────────────────────
$dims = [
    ['EI', 'E', 'I', '#818CF8', '#94A3B8'],
    ['SN', 'S', 'N', '#6EE7B7', '#22D3EE'],
    ['TF', 'T', 'F', '#FBBF24', '#F472B6'],
    ['JP', 'J', 'P', '#FCA5A5', '#C4B5FD'],
];

// 准备 JS 数据
$typeLabels = array_column($typeRanking, 'mbti_type');
$typeValues = array_map(fn($r) => (int)$r['cnt'], $typeRanking);
$typeColors = array_map(fn($r) => $TC[$r['mbti_type']] ?? '#818CF8', $typeRanking);

$pageTitle = '数据分析';
include __DIR__ . '/header.php';
?>

<!-- ===== 页面标题 + 范围切换 ===== -->
<div class="page-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <div>
    <div class="page-title"><i class="bi bi-bar-chart-line-fill"></i>数据分析</div>
    <div class="page-sub">深度洞察测试数据规律与趋势</div>
  </div>
  <div style="display:flex;gap:4px;background:var(--bg-c2);padding:4px;border-radius:var(--rs);border:1px solid var(--b1)">
    <?php foreach ([7=>'7天',14=>'14天',30=>'30天',90=>'90天'] as $d => $lbl): ?>
    <a href="?range=<?= $d ?>"
       style="padding:5px 12px;border-radius:6px;font-size:.8rem;font-weight:600;text-decoration:none;
              <?= $range===$d ? 'background:var(--p-dim);color:#fff' : 'color:var(--t3)' ?>">
      <?= $lbl ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ===== 核心统计 ===== -->
<div class="stat-grid" style="margin-bottom:24px">
  <?php
  $cards = [
      ['total',      number_format($overview['total']),       '累计测试人数', '#818CF8', 'people-fill'],
      ['today',      number_format($overview['today']),       '今日测试',     '#34D399', 'lightning-charge-fill'],
      ['this_week',  number_format($overview['this_week']),   '本周测试',     '#FBBF24', 'calendar-week-fill'],
      ['email_pct',  ($overview['total']>0?round($overview['with_email']/$overview['total']*100,1):0).'%', '邮箱填写率', '#22D3EE', 'envelope-check-fill'],
  ];
  foreach ($cards as [$key,$val,$label,$color,$icon]):
  ?>
  <div class="stat-card">
    <div class="stat-card-top">
      <div>
        <div class="stat-num" style="color:<?= $color ?>"><?= $val ?></div>
        <div class="stat-label"><?= $label ?></div>
      </div>
      <div class="stat-icon" style="background:<?= $color ?>18">
        <i class="bi bi-<?= $icon ?>" style="color:<?= $color ?>"></i>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ===== 趋势图 + 四大人格组饼图 ===== -->
<div style="display:grid;grid-template-columns:1fr 320px;gap:16px;margin-bottom:20px">

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-activity"></i>每日测试趋势</div>
      <span style="font-size:.78rem;color:var(--t3)">近 <?= $range ?> 天 · 总计 <?= array_sum($trendValues) ?> 次</span>
    </div>
    <div class="card-body"><canvas id="cTrend" style="width:100%;height:180px;display:block"></canvas></div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-pie-chart-fill" style="color:#6EE7B7"></i>四大人格组</div>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
      <canvas id="cGroupPie" style="width:100%;height:140px;display:block"></canvas>
      <?php foreach ($groupStats as $gName => $g):
        $pct = round($g['cnt'] / $total * 100, 1);
      ?>
      <div style="display:flex;align-items:center;gap:8px">
        <span style="width:9px;height:9px;border-radius:2px;background:<?= $g['color'] ?>;flex-shrink:0;display:inline-block"></span>
        <span style="font-size:.82rem;color:var(--t2);flex:1"><?= $gName ?></span>
        <span style="font-size:.82rem;font-weight:700;color:var(--t1)"><?= $pct ?>%</span>
        <span style="font-size:.78rem;color:var(--t3)">(<?= $g['cnt'] ?>)</span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- ===== 16型条形图 + 四维度平衡 ===== -->
<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;margin-bottom:20px">

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-bar-chart-fill" style="color:#FCA5A5"></i>16 种人格类型分布</div>
    </div>
    <div class="card-body"><canvas id="cTypeBar" style="width:100%;height:220px;display:block"></canvas></div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-toggles" style="color:#FDE68A"></i>四维度偏好平衡</div>
    </div>
    <div class="card-body" style="padding:20px 20px">
      <?php foreach ($dims as [$pair, $aKey, $bKey, $aColor, $bColor]):
        $aVal  = (int)($dimBalance[$aKey] ?? 0);
        $bVal  = (int)($dimBalance[$bKey] ?? 0);
        $aSum  = $aVal + $bVal ?: 1;
        $aPct  = round($aVal / $aSum * 100, 1);
        $bPct  = round($bVal / $aSum * 100, 1);
      ?>
      <div style="margin-bottom:20px">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;align-items:center">
          <span style="font-weight:700;font-size:.92rem;color:<?= $aColor ?>"><?= $aKey ?> <span style="font-size:.75rem;font-weight:400;color:var(--t3)"><?= number_format($aVal) ?></span></span>
          <span style="font-size:.78rem;color:var(--t3)"><?= $aPct ?>% · <?= $bPct ?>%</span>
          <span style="font-weight:700;font-size:.92rem;color:<?= $bColor ?>"><?= $bKey ?> <span style="font-size:.75rem;font-weight:400;color:var(--t3)"><?= number_format($bVal) ?></span></span>
        </div>
        <div style="height:10px;border-radius:5px;background:var(--bg-c2);overflow:hidden;display:flex">
          <div style="width:<?= $aPct ?>%;background:<?= $aColor ?>;transition:width .8s;border-radius:5px 0 0 5px"></div>
          <div style="width:<?= $bPct ?>%;background:<?= $bColor ?>;transition:width .8s;border-radius:0 5px 5px 0"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- ===== 24h分布 + 完整排名 ===== -->
<div style="display:grid;grid-template-columns:380px 1fr;gap:16px;margin-bottom:20px">

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-clock-history" style="color:#C4B5FD"></i>24 小时活跃分布</div>
    </div>
    <div class="card-body"><canvas id="c24h" style="width:100%;height:200px;display:block"></canvas></div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="bi bi-trophy-fill" style="color:#FBBF24"></i>完整类型排名</div>
    </div>
    <div class="card-body" style="max-height:300px;overflow-y:auto;padding:16px 20px">
      <?php if (empty($typeRanking)): ?>
      <div class="empty"><div class="empty-icon"><i class="bi bi-bar-chart"></i></div><div class="empty-title">暂无数据</div></div>
      <?php else:
      $rankMax = (int)$typeRanking[0]['cnt'] ?: 1;
      foreach ($typeRanking as $i => $row):
        $color  = $TC[$row['mbti_type']] ?? '#818CF8';
        $pct    = round($row['cnt'] / $total * 100, 1);
        $barPct = round($row['cnt'] / $rankMax * 100);
      ?>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
        <span style="width:22px;text-align:right;font-size:.78rem;color:var(--t4);flex-shrink:0"><?= $i+1 ?></span>
        <span class="type-chip" style="background:<?= $color ?>18;color:<?= $color ?>;border-color:<?= $color ?>35;width:48px;text-align:center;flex-shrink:0"><?= htmlspecialchars($row['mbti_type']) ?></span>
        <div style="flex:1;height:7px;background:var(--bg-c2);border-radius:4px;overflow:hidden">
          <div style="width:<?= $barPct ?>%;height:100%;background:<?= $color ?>;border-radius:4px"></div>
        </div>
        <span style="font-size:.82rem;font-weight:700;width:30px;text-align:right;flex-shrink:0"><?= number_format((int)$row['cnt']) ?></span>
        <span style="font-size:.75rem;color:var(--t3);width:38px;flex-shrink:0"><?= $pct ?>%</span>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

</div>

<!-- ===== 四大人格组详情卡 ===== -->
<div class="card" style="margin-bottom:20px">
  <div class="card-header">
    <div class="card-title"><i class="bi bi-grid-3x3-gap-fill" style="color:#86EFAC"></i>四大人格组详情</div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
      <?php foreach ($groupStats as $gName => $g):
        $gPct = round($g['cnt'] / $total * 100, 1);
      ?>
      <div style="background:var(--bg-c2);border-radius:var(--rs);padding:16px;border:1px solid <?= $g['color'] ?>28">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
          <span style="font-weight:700;color:<?= $g['color'] ?>;font-size:.92rem"><?= $gName ?></span>
          <span style="font-size:.82rem;color:var(--t3)"><?= $gPct ?>%</span>
        </div>
        <div style="height:4px;background:var(--bg-c);border-radius:2px;margin-bottom:12px;overflow:hidden">
          <div style="width:<?= $gPct ?>%;height:100%;background:<?= $g['color'] ?>;border-radius:2px"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
          <?php foreach ($g['types'] as $t):
            $tc = $cntByType[$t] ?? 0;
            $col = $TC[$t] ?? '#818CF8';
          ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 6px;background:var(--bg-c);border-radius:5px">
            <span style="font-size:.78rem;font-weight:700;color:<?= $col ?>"><?= $t ?></span>
            <span style="font-size:.75rem;color:var(--t3)"><?= $tc ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== Charts ===== -->
<script>
const DPR = window.devicePixelRatio||1;
function mkCtx(id){
  const c=document.getElementById(id); if(!c) return null;
  const r=c.getBoundingClientRect();
  c.width=r.width*DPR; c.height=r.height*DPR;
  const ctx=c.getContext('2d'); ctx.scale(DPR,DPR);
  c._w=r.width; c._h=r.height; return ctx;
}
const GRID='rgba(255,255,255,.05)', LBL='rgba(255,255,255,.38)', FONT='11px system-ui,sans-serif';

// ── 趋势折线 ─────────────────────────────────────────────────
(function(){
  const lbs=<?= json_encode($trendLabels) ?>, vs=<?= json_encode($trendValues) ?>;
  setTimeout(()=>{
    const ctx=mkCtx('cTrend'); if(!ctx) return;
    const W=ctx.canvas._w, H=ctx.canvas._h;
    const P={t:12,r:12,b:32,l:40};
    const cW=W-P.l-P.r, cH=H-P.t-P.b;
    const maxV=Math.max(...vs,1);
    const sx=cW/(lbs.length-1||1);

    for(let i=0;i<=4;i++){
      const y=P.t+cH-cH/4*i;
      ctx.strokeStyle=GRID; ctx.lineWidth=1;
      ctx.beginPath(); ctx.moveTo(P.l,y); ctx.lineTo(P.l+cW,y); ctx.stroke();
      ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='right';
      ctx.fillText(Math.round(maxV/4*i),P.l-5,y+4);
    }

    const g=ctx.createLinearGradient(0,P.t,0,P.t+cH);
    g.addColorStop(0,'rgba(99,102,241,.4)'); g.addColorStop(1,'rgba(99,102,241,0)');
    ctx.fillStyle=g;
    ctx.beginPath(); ctx.moveTo(P.l,P.t+cH);
    vs.forEach((v,i)=>ctx.lineTo(P.l+i*sx, P.t+cH-(v/maxV)*cH));
    ctx.lineTo(P.l+(vs.length-1)*sx,P.t+cH); ctx.closePath(); ctx.fill();

    ctx.strokeStyle='#818CF8'; ctx.lineWidth=2.5;
    ctx.lineJoin='round'; ctx.lineCap='round';
    ctx.beginPath();
    vs.forEach((v,i)=>{ const x=P.l+i*sx,y=P.t+cH-(v/maxV)*cH; i?ctx.lineTo(x,y):ctx.moveTo(x,y); });
    ctx.stroke();

    vs.forEach((v,i)=>{
      const x=P.l+i*sx,y=P.t+cH-(v/maxV)*cH;
      ctx.beginPath(); ctx.arc(x,y,3,0,Math.PI*2);
      ctx.fillStyle='#818CF8'; ctx.fill();
      ctx.strokeStyle='#070711'; ctx.lineWidth=2; ctx.stroke();
    });

    const en=Math.ceil(lbs.length/8);
    ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='center';
    lbs.forEach((lb,i)=>{ if(i%en===0||i===lbs.length-1) ctx.fillText(lb,P.l+i*sx,P.t+cH+16); });
  },60);
})();

// ── 四大人格组饼图 ───────────────────────────────────────────
(function(){
  const vals=[<?= implode(',',array_map(fn($g)=>$g['cnt'],$groupStats)) ?>];
  const cols=['#818CF8','#6EE7B7','#FCA5A5','#FDE68A'];
  setTimeout(()=>{
    const ctx=mkCtx('cGroupPie'); if(!ctx) return;
    const W=ctx.canvas._w,H=ctx.canvas._h;
    const cx=W/2,cy=H/2,r=Math.min(W/2,H/2)-8,ri=r*.52;
    const tot=vals.reduce((a,b)=>a+b,0)||1;
    let a=-Math.PI/2;
    vals.forEach((v,i)=>{
      const s=(v/tot)*Math.PI*2;
      ctx.beginPath(); ctx.moveTo(cx,cy); ctx.arc(cx,cy,r,a,a+s);
      ctx.closePath(); ctx.fillStyle=cols[i]; ctx.fill();
      a+=s;
    });
    ctx.beginPath(); ctx.arc(cx,cy,ri,0,Math.PI*2);
    ctx.fillStyle='#10101F'; ctx.fill();
    ctx.fillStyle='rgba(255,255,255,.9)'; ctx.font='bold 18px system-ui,sans-serif';
    ctx.textAlign='center'; ctx.textBaseline='middle';
    ctx.fillText(tot,cx,cy-7);
    ctx.font='10px system-ui,sans-serif'; ctx.fillStyle='rgba(255,255,255,.38)';
    ctx.fillText('总测试',cx,cy+10);
  },60);
})();

// ── 16型条形图 ───────────────────────────────────────────────
(function(){
  const lbs=<?= json_encode($typeLabels) ?>;
  const vs=<?= json_encode($typeValues) ?>;
  const cols=<?= json_encode($typeColors) ?>;
  setTimeout(()=>{
    const ctx=mkCtx('cTypeBar'); if(!ctx) return;
    const W=ctx.canvas._w,H=ctx.canvas._h;
    const P={t:20,r:10,b:36,l:40};
    const cW=W-P.l-P.r,cH=H-P.t-P.b;
    const n=lbs.length,bw=cW/n,maxV=Math.max(...vs,1);

    for(let i=0;i<=4;i++){
      const y=P.t+cH-cH/4*i;
      ctx.strokeStyle=GRID; ctx.lineWidth=1;
      ctx.beginPath(); ctx.moveTo(P.l,y); ctx.lineTo(P.l+cW,y); ctx.stroke();
      ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='right';
      ctx.fillText(Math.round(maxV/4*i),P.l-4,y+4);
    }

    vs.forEach((v,i)=>{
      const h=Math.max(v>0?3:0,(v/maxV)*cH);
      const x=P.l+i*bw+bw*.1,bW=bw*.8,y=P.t+cH-h,r=Math.min(4,bW/2);
      const g=ctx.createLinearGradient(0,y,0,P.t+cH);
      g.addColorStop(0,cols[i]); g.addColorStop(1,cols[i]+'55');
      ctx.fillStyle=g;
      ctx.beginPath();
      ctx.moveTo(x+r,y); ctx.lineTo(x+bW-r,y);
      ctx.arcTo(x+bW,y,x+bW,y+r,r); ctx.lineTo(x+bW,P.t+cH);
      ctx.lineTo(x,P.t+cH); ctx.arcTo(x,y,x+r,y,r);
      ctx.closePath(); ctx.fill();

      if(v>0){
        ctx.fillStyle=cols[i]; ctx.font='9px system-ui,sans-serif'; ctx.textAlign='center';
        ctx.fillText(v,x+bW/2,y-4);
      }
    });

    ctx.fillStyle=LBL; ctx.font='9px system-ui,sans-serif'; ctx.textAlign='center';
    lbs.forEach((lb,i)=>ctx.fillText(lb,P.l+i*bw+bw/2,P.t+cH+16));
  },60);
})();

// ── 24h 柱状图 ───────────────────────────────────────────────
(function(){
  const vs=<?= json_encode($hVals) ?>;
  setTimeout(()=>{
    const ctx=mkCtx('c24h'); if(!ctx) return;
    const W=ctx.canvas._w,H=ctx.canvas._h;
    const P={t:12,r:8,b:32,l:38};
    const cW=W-P.l-P.r,cH=H-P.t-P.b;
    const bw=cW/24,maxV=Math.max(...vs,1);

    for(let i=0;i<=4;i++){
      const y=P.t+cH-cH/4*i;
      ctx.strokeStyle=GRID; ctx.lineWidth=1;
      ctx.beginPath(); ctx.moveTo(P.l,y); ctx.lineTo(P.l+cW,y); ctx.stroke();
      ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='right';
      ctx.fillText(Math.round(maxV/4*i),P.l-4,y+4);
    }

    vs.forEach((v,i)=>{
      const h=Math.max(v>0?3:0,(v/maxV)*cH);
      const x=P.l+i*bw+bw*.15,bW=bw*.7,y=P.t+cH-h,r=Math.min(3,bW/2);
      const g=ctx.createLinearGradient(0,y,0,P.t+cH);
      g.addColorStop(0,'#C4B5FD'); g.addColorStop(1,'rgba(196,181,253,.25)');
      ctx.fillStyle=g;
      ctx.beginPath();
      ctx.moveTo(x+r,y); ctx.lineTo(x+bW-r,y);
      ctx.arcTo(x+bW,y,x+bW,y+r,r); ctx.lineTo(x+bW,P.t+cH);
      ctx.lineTo(x,P.t+cH); ctx.arcTo(x,y,x+r,y,r);
      ctx.closePath(); ctx.fill();
    });

    ctx.fillStyle=LBL; ctx.font=FONT; ctx.textAlign='center';
    [0,6,12,18,23].forEach(i=>ctx.fillText(i+'h',P.l+i*bw+bw/2,P.t+cH+16));
  },60);
})();
</script>

<style>
@media(max-width:1100px){
  .stat-grid{grid-template-columns:repeat(2,1fr)!important}
}
@media(max-width:900px){
  .stat-grid{grid-template-columns:1fr 1fr!important}
}
</style>

<?php include __DIR__ . '/footer.php'; ?>
