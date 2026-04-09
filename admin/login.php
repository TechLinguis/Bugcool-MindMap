<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// 已登录则直接跳转
if (admin_is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error    = '';
$redirect = preg_replace('/[^a-zA-Z0-9_.\/\-]/', '', $_GET['redirect'] ?? 'index.php');
if (strpos($redirect, '//') !== false || strpos($redirect, ':') !== false) $redirect = 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (admin_login($username, $password)) {
        header('Location: ' . $redirect);
        exit;
    }
    sleep(1);
    $error = '用户名或密码错误';
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理员登录 · Bugcool MindMap</title>
<link rel="stylesheet" href="../assets/css/bootstrap-icons.min.css">
<style>
@font-face{font-family:'DouyinSansSubset';src:url('../ttf/DouyinSansBold-subset.woff2') format('woff2');font-weight:100 900;font-display:fallback}
@font-face{font-family:'DouyinSansFull';src:url('../ttf/DouyinSansBold.ttf') format('truetype');font-weight:100 900;font-display:fallback}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --p:#818CF8; --pd:#6366F1; --rose:#F472B6;
  --bg:#070711; --bg2:#0C0C1E;
  --t1:#EEEEF6; --t2:#9898B4; --t3:#5C5C78; --t4:#3A3A58;
  --b1:rgba(255,255,255,.06); --b2:rgba(255,255,255,.10); --b3:rgba(129,140,248,.35);
}

html,body{height:100%}

body{
  font-family:'DouyinSansFull','DouyinSansSubset',-apple-system,'PingFang SC',sans-serif;
  background:var(--bg);
  color:var(--t1);
  display:flex; align-items:center; justify-content:center;
  min-height:100vh;
  overflow:hidden;
}

/* ── 背景动画 ── */
.bg-orb{
  position:fixed;border-radius:50%;filter:blur(80px);
  animation:float 8s ease-in-out infinite;
  pointer-events:none;z-index:0;
}
.bg-orb-1{width:600px;height:600px;top:-200px;left:-200px;background:rgba(99,102,241,.18);animation-delay:0s}
.bg-orb-2{width:500px;height:500px;bottom:-150px;right:-150px;background:rgba(244,114,182,.12);animation-delay:-3s}
.bg-orb-3{width:350px;height:350px;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(129,140,248,.06);animation-delay:-5s}

@keyframes float{
  0%,100%{transform:translateY(0) scale(1)}
  50%{transform:translateY(-30px) scale(1.05)}
}
.bg-orb-3{animation:floatC 10s ease-in-out infinite}
@keyframes floatC{0%,100%{transform:translate(-50%,-50%) scale(1)}50%{transform:translate(-50%,-55%) scale(1.08)}}

/* ── 卡片 ── */
.login-wrap{
  position:relative;z-index:1;
  width:100%;max-width:420px;
  padding:0 20px;
}

.login-card{
  background:rgba(12,12,30,.75);
  border:1px solid var(--b1);
  border-radius:20px;
  padding:40px 36px;
  backdrop-filter:blur(24px) saturate(180%);
  -webkit-backdrop-filter:blur(24px) saturate(180%);
  box-shadow:
    0 0 0 1px rgba(255,255,255,.04),
    0 32px 80px rgba(0,0,0,.6),
    inset 0 1px 0 rgba(255,255,255,.07);
}

/* ── Logo ── */
.login-logo{
  display:flex;flex-direction:column;align-items:center;
  margin-bottom:32px;gap:12px;
}
.logo-icon{
  width:56px;height:56px;border-radius:18px;
  background:linear-gradient(135deg,#6366F1 0%,#F472B6 100%);
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 8px 30px rgba(99,102,241,.5);
  font-size:1.6rem;
}
.logo-title{font-size:1.15rem;font-weight:800;color:var(--t1);letter-spacing:.3px}
.logo-sub{font-size:.78rem;color:var(--t3);margin-top:-6px}

/* ── 表单 ── */
.form-group{margin-bottom:18px}
.form-label{
  display:block;
  font-size:.78rem;font-weight:600;color:var(--t2);
  margin-bottom:7px;letter-spacing:.3px;
}
.form-field{
  position:relative;
}
.form-inp{
  width:100%;
  background:rgba(7,7,17,.6);
  border:1px solid var(--b2);
  border-radius:11px;
  color:var(--t1);
  padding:11px 42px 11px 42px;
  font-size:.94rem;
  outline:none;
  transition:border-color .2s,box-shadow .2s;
  font-family:inherit;
}
.form-inp:focus{
  border-color:var(--p);
  box-shadow:0 0 0 3px rgba(129,140,248,.18);
}
.form-inp::placeholder{color:var(--t4)}
.field-icon{
  position:absolute;left:13px;top:50%;transform:translateY(-50%);
  color:var(--t3);font-size:1rem;pointer-events:none;
}
.eye-btn{
  position:absolute;right:12px;top:50%;transform:translateY(-50%);
  color:var(--t3);background:none;border:none;cursor:pointer;
  font-size:1rem;padding:2px;transition:color .15s;
}
.eye-btn:hover{color:var(--t1)}

/* ── 错误提示 ── */
.err-box{
  background:rgba(248,113,113,.08);
  border:1px solid rgba(248,113,113,.22);
  border-radius:9px;
  padding:10px 14px;
  font-size:.84rem;
  color:#F87171;
  display:flex;align-items:center;gap:8px;
  margin-bottom:18px;
}

/* ── 提交按钮 ── */
.btn-login{
  width:100%;
  padding:13px;
  border-radius:12px;
  border:none;cursor:pointer;
  background:linear-gradient(135deg,#6366F1 0%,#818CF8 100%);
  color:#fff;
  font-size:.96rem;font-weight:700;
  font-family:inherit;
  letter-spacing:.3px;
  transition:all .2s;
  box-shadow:0 6px 22px rgba(99,102,241,.4);
  position:relative;overflow:hidden;
}
.btn-login::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);
  opacity:0;transition:opacity .2s;
}
.btn-login:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(99,102,241,.55)}
.btn-login:hover::after{opacity:1}
.btn-login:active{transform:translateY(0)}

/* ── 默认账号提示 ── */
.hint{
  margin-top:22px;
  padding:12px 14px;
  background:rgba(129,140,248,.06);
  border:1px solid rgba(129,140,248,.12);
  border-radius:10px;
  font-size:.78rem;color:var(--t3);
  display:flex;align-items:center;gap:8px;
}
.hint strong{color:var(--p)}

/* ── 分隔线 ── */
.login-divider{
  border:none;border-top:1px solid var(--b1);
  margin:20px 0;
}
</style>
</head>
<body>

<!-- 背景光晕 -->
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>

<div class="login-wrap">
  <div class="login-card">

    <!-- Logo -->
    <div class="login-logo">
      <div class="logo-icon"><i class="bi bi-puzzle-fill" style="color:#fff"></i></div>
      <div class="logo-title">Bugcool MindMap</div>
      <div class="logo-sub">管理员登录</div>
    </div>

    <!-- 错误提示 -->
    <?php if ($error): ?>
    <div class="err-box">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- 登录表单 -->
    <form method="post" action="login.php<?= $redirect !== 'index.php' ? '?redirect=' . urlencode($redirect) : '' ?>">


      <div class="form-group">
        <label class="form-label">用户名</label>
        <div class="form-field">
          <i class="bi bi-person-fill field-icon"></i>
          <input class="form-inp" type="text" name="username" placeholder="请输入用户名"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                 autocomplete="username" autofocus>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">密码</label>
        <div class="form-field">
          <i class="bi bi-lock-fill field-icon"></i>
          <input class="form-inp" type="password" name="password" id="pwd-inp"
                 placeholder="请输入密码" autocomplete="current-password">
          <button type="button" class="eye-btn" onclick="togglePwd()" title="显示/隐藏密码">
            <i class="bi bi-eye" id="eye-icon"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right" style="margin-right:6px"></i>登录后台
      </button>
    </form>

    <!-- 默认账号提示 -->
    <div class="hint">
      <i class="bi bi-info-circle" style="color:var(--p);flex-shrink:0"></i>
      默认账号 <strong>admin</strong> · 默认密码 <strong>admin123</strong>，登录后请及时修改
    </div>

  </div>
</div>

<script>
function togglePwd(){
  const inp = document.getElementById('pwd-inp');
  const ico = document.getElementById('eye-icon');
  if(inp.type === 'password'){
    inp.type = 'text';
    ico.className = 'bi bi-eye-slash';
  } else {
    inp.type = 'password';
    ico.className = 'bi bi-eye';
  }
}
// Enter 提交
document.addEventListener('keydown', e => {
  if(e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
    document.querySelector('form').submit();
  }
});
</script>
</body>
</html>
