<?php
if (!defined('ADMIN_ROOT')) define('ADMIN_ROOT', true);
require_once __DIR__ . '/auth.php';
admin_require_login();
$currentFile = basename($_SERVER['PHP_SELF']);
$adminUser   = $_SESSION['admin_username'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? '管理后台') ?> · Bugcool MindMap</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* ================================================================
   DESIGN TOKENS
================================================================ */
:root {
  /* Brand */
  --p:       #818CF8;
  --p-dim:   #6366F1;
  --p-soft:  rgba(129,140,248,.12);
  --p-glow:  rgba(129,140,248,.28);

  /* Accent palette */
  --rose:    #F472B6;
  --emerald: #34D399;
  --amber:   #FBBF24;
  --cyan:    #22D3EE;
  --red:     #F87171;

  /* Surfaces */
  --bg:        #070711;
  --bg-s:      #0C0C1E;        /* sidebar */
  --bg-c:      #10101F;        /* card */
  --bg-c2:     #161628;        /* card alt */
  --bg-inp:    #0B0B1A;
  --bg-hov:    rgba(129,140,248,.07);
  --bg-act:    rgba(129,140,248,.14);

  /* Text */
  --t1: #EEEEF6;
  --t2: #9898B4;
  --t3: #5C5C78;
  --t4: #3A3A58;

  /* Borders */
  --b1: rgba(255,255,255,.055);
  --b2: rgba(255,255,255,.10);
  --b3: rgba(129,140,248,.30);

  /* Layout */
  --sw: 248px;   /* sidebar width */
  --th: 58px;    /* topbar height */
  --r:  14px;    /* radius */
  --rs: 9px;     /* radius small */
}

/* ================================================================
   RESET / BASE
================================================================ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:'DouyinSansFull','DouyinSansSubset',-apple-system,'PingFang SC','Microsoft YaHei',sans-serif;
  background:var(--bg);
  color:var(--t1);
  font-size:14px;
  line-height:1.65;
  overflow-x:hidden;
}
a{text-decoration:none;color:inherit}
img{max-width:100%;display:block}

@font-face{font-family:'DouyinSansSubset';src:url('../ttf/DouyinSansBold-subset.woff2') format('woff2');font-weight:100 900;font-display:fallback}
@font-face{font-family:'DouyinSansFull';src:url('../ttf/DouyinSansBold.ttf') format('truetype');font-weight:100 900;font-display:fallback}

/* ================================================================
   SIDEBAR
================================================================ */
.sidebar{
  position:fixed;top:0;left:0;bottom:0;
  width:var(--sw);
  background:var(--bg-s);
  border-right:1px solid var(--b1);
  display:flex;flex-direction:column;
  z-index:200;
  overflow:hidden;
}

/* ---- 顶部 Logo ---- */
.sidebar-brand{
  display:flex;align-items:center;gap:11px;
  padding:18px 18px 16px;
  border-bottom:1px solid var(--b1);
  flex-shrink:0;
}
.brand-icon{
  width:38px;height:38px;border-radius:12px;
  background:linear-gradient(135deg,#6366F1 0%,#F472B6 100%);
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 4px 16px rgba(99,102,241,.45);
  flex-shrink:0;
}
.brand-name{font-size:.9rem;font-weight:700;color:var(--t1);letter-spacing:.3px}
.brand-ver{font-size:.68rem;color:var(--t3);margin-top:1px}

/* ---- 导航 ---- */
.sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px;scrollbar-width:none}
.sidebar-nav::-webkit-scrollbar{display:none}

.nav-section{
  font-size:.67rem;font-weight:700;letter-spacing:1.2px;
  text-transform:uppercase;color:var(--t4);
  padding:10px 10px 4px;
}
.nav-link{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;
  border-radius:var(--rs);
  color:var(--t2);font-size:.875rem;font-weight:500;
  transition:background .18s,color .18s,transform .15s;
  margin-bottom:1px;
  position:relative;
  white-space:nowrap;
}
.nav-link i{font-size:1rem;flex-shrink:0;width:18px;text-align:center}
.nav-link:hover{background:var(--bg-hov);color:var(--t1)}
.nav-link.active{
  background:var(--bg-act);
  color:var(--p);
  font-weight:600;
}
.nav-link.active::before{
  content:'';
  position:absolute;left:0;top:20%;bottom:20%;
  width:3px;border-radius:2px;
  background:var(--p);
}
.nav-link.active i{color:var(--p)}
.nav-link.danger{color:var(--red)}
.nav-link.danger:hover{background:rgba(248,113,113,.08)}
.nav-link.danger i{color:var(--red)}

/* ---- 底部用户 ---- */
.sidebar-user{
  padding:12px;border-top:1px solid var(--b1);flex-shrink:0;
}
.user-card{
  display:flex;align-items:center;gap:10px;
  padding:9px 10px;border-radius:var(--rs);
  background:var(--bg-hov);
}
.user-avatar{
  width:30px;height:30px;border-radius:50%;
  background:linear-gradient(135deg,var(--p-dim),var(--rose));
  display:flex;align-items:center;justify-content:center;
  font-size:.72rem;font-weight:800;color:#fff;flex-shrink:0;
}
.user-name{font-size:.82rem;font-weight:600;color:var(--t1)}
.user-role{font-size:.68rem;color:var(--t3)}

/* ================================================================
   TOPBAR
================================================================ */
.topbar{
  position:fixed;top:0;left:var(--sw);right:0;
  height:var(--th);
  background:rgba(7,7,17,.82);
  backdrop-filter:blur(20px) saturate(180%);
  -webkit-backdrop-filter:blur(20px) saturate(180%);
  border-bottom:1px solid var(--b1);
  display:flex;align-items:center;justify-content:space-between;
  padding:0 24px;
  z-index:199;
}
.topbar-left{display:flex;align-items:center;gap:10px}
.topbar-title{
  font-size:1.02rem;font-weight:700;color:var(--t1);
}
.topbar-right{display:flex;align-items:center;gap:8px}
.topbar-btn{
  display:inline-flex;align-items:center;gap:6px;
  padding:5px 12px;border-radius:var(--rs);
  font-size:.8rem;font-weight:600;
  border:1px solid var(--b2);
  background:var(--bg-c2);color:var(--t2);
  cursor:pointer;transition:all .18s;
}
.topbar-btn:hover{border-color:var(--b3);color:var(--t1)}
.topbar-avatar{
  width:30px;height:30px;border-radius:50%;
  background:linear-gradient(135deg,var(--p-dim),var(--rose));
  display:flex;align-items:center;justify-content:center;
  font-size:.7rem;font-weight:800;color:#fff;
  box-shadow:0 2px 8px rgba(99,102,241,.4);
  cursor:pointer;
}

/* ================================================================
   MAIN LAYOUT
================================================================ */
.admin-main{
  margin-left:var(--sw);
  padding-top:var(--th);
  min-height:100vh;
}
.page-wrap{
  padding:28px 28px;
  max-width:1440px;
}

/* ================================================================
   PAGE HEADER
================================================================ */
.page-header{margin-bottom:28px}
.page-title{
  font-size:1.65rem;font-weight:800;color:var(--t1);
  letter-spacing:-.3px;line-height:1.2;
  display:flex;align-items:center;gap:10px;
}
.page-title i{color:var(--p)}
.page-sub{font-size:.83rem;color:var(--t3);margin-top:4px}

/* ================================================================
   STAT CARDS
================================================================ */
.stat-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
  gap:16px;
  margin-bottom:24px;
}
.stat-card{
  background:var(--bg-c);
  border:1px solid var(--b1);
  border-radius:var(--r);
  padding:20px 20px 18px;
  position:relative;
  overflow:hidden;
  transition:transform .22s,border-color .22s,box-shadow .22s;
  cursor:default;
}
.stat-card::after{
  content:'';
  position:absolute;inset:0;
  border-radius:var(--r);
  background:radial-gradient(ellipse at top left,var(--card-glow,rgba(129,140,248,.06)) 0%,transparent 70%);
  pointer-events:none;
}
.stat-card:hover{
  transform:translateY(-4px);
  border-color:var(--b3);
  box-shadow:0 12px 40px rgba(129,140,248,.12);
}
.stat-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px}
.stat-icon{
  width:46px;height:46px;border-radius:13px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.25rem;flex-shrink:0;
}
.stat-num{
  font-size:2.1rem;font-weight:800;color:var(--t1);
  line-height:1;letter-spacing:-.5px;
  font-variant-numeric:tabular-nums;
}
.stat-label{font-size:.8rem;color:var(--t3);margin-top:4px}
.stat-badge{
  display:inline-flex;align-items:center;gap:3px;
  padding:2px 7px;border-radius:99px;
  font-size:.7rem;font-weight:600;
  margin-top:8px;
}
.stat-badge.up{background:rgba(52,211,153,.12);color:var(--emerald)}
.stat-badge.neutral{background:rgba(129,140,248,.12);color:var(--p)}

/* ================================================================
   CARDS
================================================================ */
.card{
  background:var(--bg-c);
  border:1px solid var(--b1);
  border-radius:var(--r);
  overflow:hidden;
}
.card-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:16px 20px;
  border-bottom:1px solid var(--b1);
}
.card-title{
  display:flex;align-items:center;gap:8px;
  font-size:.93rem;font-weight:600;color:var(--t1);
}
.card-title i{color:var(--p);font-size:1rem}
.card-body{padding:20px}
.card-body-np{padding:0}

/* ================================================================
   TABLE
================================================================ */
.tbl{width:100%;border-collapse:collapse;font-size:.875rem}
.tbl thead th{
  padding:10px 14px;
  color:var(--t3);font-size:.72rem;font-weight:700;
  letter-spacing:.6px;text-transform:uppercase;
  border-bottom:1px solid var(--b2);
  white-space:nowrap;background:transparent;
}
.tbl tbody td{
  padding:12px 14px;
  border-bottom:1px solid var(--b1);
  color:var(--t1);vertical-align:middle;
}
.tbl tbody tr:last-child td{border-bottom:none}
.tbl tbody tr{transition:background .12s}
.tbl tbody tr:hover{background:var(--bg-hov)}

/* ================================================================
   FORM CONTROLS
================================================================ */
.inp{
  background:var(--bg-inp);
  border:1px solid var(--b2);
  border-radius:var(--rs);
  color:var(--t1);
  padding:8px 12px;
  font-size:.875rem;
  outline:none;
  transition:border-color .18s,box-shadow .18s;
  font-family:inherit;
}
.inp:focus{border-color:var(--p);box-shadow:0 0 0 3px var(--p-soft)}
.inp::placeholder{color:var(--t4)}
.sel{
  background:var(--bg-inp);
  border:1px solid var(--b2);
  border-radius:var(--rs);
  color:var(--t1);
  padding:8px 12px;
  font-size:.875rem;
  outline:none;cursor:pointer;
  font-family:inherit;
}
.sel:focus{border-color:var(--p)}
.field-label{font-size:.78rem;font-weight:600;color:var(--t2);margin-bottom:6px;display:block}
.field-hint{font-size:.72rem;color:var(--t4);margin-top:4px}

/* ================================================================
   BUTTONS
================================================================ */
.btn{
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 16px;border-radius:var(--rs);
  font-size:.86rem;font-weight:600;
  border:none;cursor:pointer;
  transition:all .18s;font-family:inherit;
  white-space:nowrap;
}
.btn:hover{transform:translateY(-1px)}
.btn:active{transform:translateY(0)}
.btn-primary{
  background:linear-gradient(135deg,var(--p-dim),var(--p));
  color:#fff;
  box-shadow:0 4px 14px rgba(99,102,241,.35);
}
.btn-primary:hover{box-shadow:0 6px 20px rgba(99,102,241,.5)}
.btn-ghost{background:var(--bg-c2);color:var(--t2);border:1px solid var(--b2)}
.btn-ghost:hover{color:var(--t1);border-color:var(--b3)}
.btn-danger{background:rgba(248,113,113,.1);color:var(--red);border:1px solid rgba(248,113,113,.18)}
.btn-danger:hover{background:rgba(248,113,113,.2)}
.btn-success{background:rgba(52,211,153,.1);color:var(--emerald);border:1px solid rgba(52,211,153,.18)}
.btn-sm{padding:5px 11px;font-size:.78rem}
.btn-xs{padding:3px 8px;font-size:.72rem;border-radius:6px}

/* ================================================================
   BADGE / TYPE
================================================================ */
.badge{
  display:inline-flex;align-items:center;gap:4px;
  padding:3px 9px;border-radius:99px;
  font-size:.75rem;font-weight:700;letter-spacing:.4px;
}
.type-chip{
  display:inline-flex;align-items:center;
  padding:3px 10px;border-radius:8px;
  font-size:.78rem;font-weight:700;letter-spacing:.5px;
  border:1px solid transparent;
}

/* ================================================================
   PAGINATION
================================================================ */
.pages{display:flex;align-items:center;gap:4px;flex-wrap:wrap}
.page-item{
  display:inline-flex;align-items:center;justify-content:center;
  min-width:32px;height:32px;padding:0 6px;
  border-radius:var(--rs);border:1px solid var(--b2);
  background:var(--bg-c2);color:var(--t2);
  font-size:.82rem;cursor:pointer;text-decoration:none;
  transition:all .15s;
}
.page-item:hover{border-color:var(--b3);color:var(--t1)}
.page-item.cur{background:var(--p-dim);border-color:var(--p-dim);color:#fff}
.page-item.dis{opacity:.3;pointer-events:none}

/* ================================================================
   ALERTS
================================================================ */
.alert{
  display:flex;align-items:center;gap:8px;
  padding:12px 16px;border-radius:var(--rs);
  font-size:.875rem;margin-bottom:16px;
}
.alert-ok{background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2);color:var(--emerald)}
.alert-err{background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.2);color:var(--red)}
.alert-info{background:rgba(129,140,248,.08);border:1px solid rgba(129,140,248,.2);color:var(--p)}

/* ================================================================
   PROGRESS
================================================================ */
.prog{height:6px;background:var(--bg-c2);border-radius:4px;overflow:hidden}
.prog-fill{height:100%;border-radius:4px;transition:width .8s cubic-bezier(.25,.46,.45,.94)}

/* ================================================================
   EMPTY STATE
================================================================ */
.empty{
  text-align:center;padding:60px 20px;
  display:flex;flex-direction:column;align-items:center;gap:12px;
}
.empty-icon{
  width:64px;height:64px;border-radius:18px;
  background:var(--bg-c2);
  display:flex;align-items:center;justify-content:center;
  font-size:1.8rem;color:var(--t4);
  margin-bottom:4px;
}
.empty-title{font-size:.95rem;font-weight:600;color:var(--t2)}
.empty-sub{font-size:.82rem;color:var(--t4)}

/* ================================================================
   MISC
================================================================ */
.admin-check{width:15px;height:15px;accent-color:var(--p);cursor:pointer}
.divider{border:none;border-top:1px solid var(--b1);margin:0}
.mono{font-family:'Courier New',monospace;font-size:.82rem;color:var(--t3)}

/* Scrollbar */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--b2);border-radius:4px}
::-webkit-scrollbar-thumb:hover{background:var(--b3)}

/* Spinner */
@keyframes spin{to{transform:rotate(360deg)}}
.spin{display:inline-block;width:16px;height:16px;border:2px solid var(--b2);border-top-color:var(--p);border-radius:50%;animation:spin .7s linear infinite}

/* ================================================================
   RESPONSIVE
================================================================ */
@media(max-width:900px){
  .sidebar{transform:translateX(-100%);transition:transform .3s}
  .sidebar.open{transform:translateX(0)}
  .topbar{left:0}
  .admin-main{margin-left:0}
  .page-wrap{padding:16px}
  .stat-grid{grid-template-columns:repeat(2,1fr)}
}
</style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
  <a href="index.php" class="sidebar-brand">
    <div class="brand-icon">
      <i class="bi bi-puzzle-fill" style="color:#fff;font-size:1.15rem"></i>
    </div>
    <div>
      <div class="brand-name">Bugcool MindMap</div>
      <div class="brand-ver">管理后台 v<?= ADMIN_VERSION ?></div>
    </div>
  </a>

  <nav class="sidebar-nav">
    <div class="nav-section">概览</div>
    <a href="index.php"     class="nav-link <?= $currentFile==='index.php'     ? 'active':'' ?>">
      <i class="bi bi-grid-1x2-fill"></i>仪表盘
    </a>

    <div class="nav-section" style="margin-top:6px">数据管理</div>
    <a href="users.php"     class="nav-link <?= $currentFile==='users.php'     ? 'active':'' ?>">
      <i class="bi bi-people-fill"></i>用户数据
    </a>
    <a href="questions.php" class="nav-link <?= $currentFile==='questions.php' ? 'active':'' ?>">
      <i class="bi bi-card-checklist"></i>题目管理
    </a>

    <div class="nav-section" style="margin-top:6px">分析</div>
    <a href="stats.php"        class="nav-link <?= $currentFile==='stats.php'        ? 'active':'' ?>">
      <i class="bi bi-bar-chart-line-fill"></i>数据分析
    </a>
    <a href="email_settings.php" class="nav-link <?= $currentFile==='email_settings.php' ? 'active':'' ?>">
      <i class="bi bi-envelope-at-fill"></i>邮件设置
    </a>

    <div class="nav-section" style="margin-top:6px">系统</div>
    <a href="../index.php" class="nav-link" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i>前台首页
    </a>
    <a href="logout.php" class="nav-link danger" onclick="return confirm('确认退出登录？')">
      <i class="bi bi-power"></i>退出登录
    </a>
  </nav>

  <div class="sidebar-user">
    <div class="user-card">
      <div class="user-avatar"><?= strtoupper(substr($adminUser,0,1)) ?></div>
      <div>
        <div class="user-name"><?= htmlspecialchars($adminUser) ?></div>
        <div class="user-role">超级管理员</div>
      </div>
    </div>
  </div>
</aside>

<!-- ===== TOPBAR ===== -->
<header class="topbar">
  <div class="topbar-left">
    <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? '管理后台') ?></div>
  </div>
  <div class="topbar-right">
    <a href="../index.php" target="_blank" class="topbar-btn">
      <i class="bi bi-eye"></i>前台预览
    </a>
    <div class="topbar-avatar"><?= strtoupper(substr($adminUser,0,1)) ?></div>
  </div>
</header>

<!-- ===== MAIN ===== -->
<main class="admin-main">
<div class="page-wrap">
<div id="flash-msg" class="alert" style="display:none"></div>
