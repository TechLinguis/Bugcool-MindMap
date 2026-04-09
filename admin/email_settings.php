<?php
/**
 * 邮件设置管理页面
 */
$pageTitle = '邮件设置';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/email.php';

admin_require_login();

// ---- 加载当前配置 ----
EmailSender::ensureTable();
$cfg = EmailSender::getConfig();

// ---- 保存配置 ----
$flash = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if (!csrf_verify($_POST['csrf'] ?? '')) {
        $flash = ['err', 'CSRF 验证失败'];
    } elseif ($_POST['action'] === 'save') {
        $data = [
            'email_enabled'    => $_POST['email_enabled'] ?? '0',
            'smtp_host'        => trim($_POST['smtp_host'] ?? ''),
            'smtp_port'        => trim($_POST['smtp_port'] ?? '465'),
            'smtp_encryption'  => $_POST['smtp_encryption'] ?? 'ssl',
            'smtp_username'    => trim($_POST['smtp_username'] ?? ''),
            'smtp_password'    => $_POST['smtp_password'] ?? '',
            'sender_name'      => trim($_POST['sender_name'] ?? ''),
            'sender_email'     => trim($_POST['sender_email'] ?? ''),
            'email_subject'    => trim($_POST['email_subject'] ?? ''),
            'email_template'   => $_POST['email_template'] ?? '',
        ];
        EmailSender::saveConfig($data);
        $flash = ['ok', '配置已保存'];
        $cfg   = EmailSender::getConfig(); // 重新加载
    } elseif ($_POST['action'] === 'test') {
        $recipient = trim($_POST['test_email'] ?? '');
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $flash = ['err', '请输入有效的邮箱地址'];
        } else {
            $ok = EmailSender::sendTestEmail($recipient);
            $flash = $ok
                ? ['ok', "测试邮件已发送到 {$recipient}，请查收"]
                : ['err', '发送失败，请检查 SMTP 配置和服务器网络'];
        }
    } elseif ($_POST['action'] === 'test_smtp') {
        $result = EmailSender::testSMTPConnection();
        $flash  = $result['ok']
            ? ['ok', $result['msg']]
            : ['err', $result['msg']];
    } elseif ($_POST['action'] === 'reset_template') {
        EmailSender::saveConfig(['email_template' => EmailSender::defaultTemplate()]);
        $flash = ['ok', '模板已恢复默认'];
        $cfg   = EmailSender::getConfig();
    }
}

$csrf    = csrf_token();
$enabled = $cfg['email_enabled'] ?? '0';
$vars    = ['{name}','{mbti_type}','{certificate_no}','{type_name}','{type_nickname}','{E}','{I}','{S}','{N}','{T}','{F}','{J}','{P}','{EI}','{SN}','{TF}','{JP}','{site_name}','{site_url}'];

include __DIR__ . '/header.php';
?>

<!-- ===== 页面标题 ===== -->
<div class="page-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <div>
    <div class="page-title"><i class="bi bi-envelope-at-fill"></i>邮件设置</div>
    <div class="page-sub">配置测试完成后的邮件通知功能</div>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <span class="type-chip" style="background:<?= $enabled === '1' ? 'rgba(52,211,153,.15);color:#34d399;border-color:rgba(52,211,153,.25)' : 'rgba(100,116,139,.15);color:#64748b;border-color:rgba(100,116,139,.2)' ?>">
      <i class="bi bi-<?= $enabled === '1' ? 'check-circle-fill' : 'x-circle' ?>"></i>
      <?= $enabled === '1' ? '邮件功能已启用' : '邮件功能已禁用' ?>
    </span>
  </div>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash[0] ?>" style="margin-bottom:16px">
  <i class="bi bi-<?= $flash[0] === 'ok' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
  <?= htmlspecialchars($flash[1]) ?>
</div>
<?php endif; ?>

<!-- ===== 功能开关 ===== -->
<div class="card" style="margin-bottom:16px">
  <div style="padding:16px 20px">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <div>
        <div style="font-weight:600;font-size:.9rem;color:var(--t1)">启用邮件通知</div>
        <div style="font-size:.78rem;color:var(--t3);margin-top:2px">开启后，用户完成测试将自动收到结果邮件</div>
      </div>
      <label class="toggle-switch">
        <input type="checkbox" id="emailEnabled" value="1"
               <?= $enabled === '1' ? 'checked' : '' ?>
               onchange="this.value = this.checked ? '1' : '0'">
        <span class="toggle-track"></span>
      </label>
    </div>
  </div>
</div>

<form method="post" id="mainForm">
<input type="hidden" name="action" value="save">
<input type="hidden" name="csrf" value="<?= $csrf ?>">
<input type="hidden" name="email_enabled" id="emailEnabledHidden" value="<?= $enabled ?>">

<!-- ===== SMTP 配置 ===== -->
<div class="card" style="margin-bottom:16px">
  <div style="padding:16px 20px;border-bottom:1px solid var(--b1)">
    <div style="display:flex;align-items:center;gap:8px">
      <i class="bi bi-server" style="color:var(--p)"></i>
      <span style="font-weight:600;font-size:.9rem;color:var(--t1)">SMTP 服务器配置</span>
    </div>
  </div>
  <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px">

    <div>
      <div class="field-label">SMTP 主机</div>
      <input class="inp" name="smtp_host" placeholder="smtp.gmail.com / smtp.qq.com"
             value="<?= htmlspecialchars($cfg['smtp_host'] ?? '') ?>">
      <div class="field-hint">留空则使用 PHP mail() 函数发送（不推荐，生产环境请配置 SMTP）</div>
    </div>

    <div>
      <div class="field-label">SMTP 端口</div>
      <input class="inp" name="smtp_port" placeholder="465"
             value="<?= htmlspecialchars($cfg['smtp_port'] ?? '465') ?>">
      <div class="field-hint">SSL 通常 465，TLS/STARTTLS 通常 587</div>
    </div>

    <div>
      <div class="field-label">加密方式</div>
      <select class="sel" name="smtp_encryption">
        <option value="ssl" <?= ($cfg['smtp_encryption'] ?? 'ssl') === 'ssl' ? 'selected' : '' ?>>SSL（端口 465，推荐）</option>
        <option value="tls" <?= ($cfg['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS / STARTTLS（端口 587）</option>
        <option value="" <?= ($cfg['smtp_encryption'] ?? '') === '' ? 'selected' : '' ?>>无加密（仅本地测试）</option>
      </select>
    </div>

    <div>
      <div class="field-label">SMTP 用户名</div>
      <input class="inp" name="smtp_username" placeholder="your@email.com"
             value="<?= htmlspecialchars($cfg['smtp_username'] ?? '') ?>">
    </div>

    <div>
      <div class="field-label">SMTP 密码 / 授权码</div>
      <input class="inp" type="password" name="smtp_password"
             placeholder="<?= !empty($cfg['smtp_password']) ? '已保存密码（留空不修改）' : '请输入密码或授权码' ?>"
             value="">
      <div class="field-hint">QQ 邮箱请使用「授权码」而非 QQ 密码</div>
    </div>

  </div>

  <!-- 连接测试按钮 -->
  <div style="padding:0 20px 16px;display:flex;align-items:center;gap:10px">
    <button type="button" class="btn btn-ghost btn-sm" style="gap:6px"
            onclick="testSMTP()">
      <i class="bi bi-plug"></i>测试连接
    </button>
    <span style="font-size:.75rem;color:var(--t4)">点击测试与 SMTP 服务器的连接</span>
  </div>
</div>

<!-- ===== 发件人配置 ===== -->
<div class="card" style="margin-bottom:16px">
  <div style="padding:16px 20px;border-bottom:1px solid var(--b1)">
    <div style="display:flex;align-items:center;gap:8px">
      <i class="bi bi-person-fill" style="color:var(--p)"></i>
      <span style="font-weight:600;font-size:.9rem;color:var(--t1)">发件人信息</span>
    </div>
  </div>
  <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <div>
      <div class="field-label">发件人名称</div>
      <input class="inp" name="sender_name" placeholder="MBTI 性格测试"
             value="<?= htmlspecialchars($cfg['sender_name'] ?? '') ?>">
    </div>
    <div>
      <div class="field-label">发件人邮箱</div>
      <input class="inp" type="email" name="sender_email" placeholder="noreply@example.com"
             value="<?= htmlspecialchars($cfg['sender_email'] ?? '') ?>">
    </div>
  </div>
</div>

<!-- ===== 邮件内容 ===== -->
<div class="card" style="margin-bottom:16px">
  <div style="padding:16px 20px;border-bottom:1px solid var(--b1)">
    <div style="display:flex;align-items:center;gap:8px">
      <i class="bi bi-mail-markup" style="color:var(--p)"></i>
      <span style="font-weight:600;font-size:.9rem;color:var(--t1)">邮件内容</span>
    </div>
  </div>
  <div style="padding:20px">

    <div style="margin-bottom:14px">
      <div class="field-label">邮件标题</div>
      <input class="inp" name="email_subject" placeholder="你的 MBTI 测试结果：{mbti_type}"
             value="<?= htmlspecialchars($cfg['email_subject'] ?? '') ?>">
      <div class="field-hint">可用变量：<?= implode(' / ', $vars) ?></div>
    </div>

    <div>
      <div class="field-label">邮件模板（HTML）</div>
      <textarea class="inp" name="email_template" rows="16"
                style="font-family:monospace;font-size:.78rem;line-height:1.6"
                placeholder="使用 HTML 编写邮件内容，可用变量见上方"><?= htmlspecialchars($cfg['email_template'] ?? EmailSender::defaultTemplate()) ?></textarea>
      <div class="field-hint">可用变量：<?= implode(' / ', $vars) ?></div>
    </div>

  </div>
</div>

<!-- ===== 操作按钮 ===== -->
<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
  <button type="submit" class="btn btn-primary" style="gap:6px">
    <i class="bi bi-check-lg"></i>保存配置
  </button>
  <button type="button" class="btn btn-ghost" onclick="document.getElementById('test-email-modal').classList.add('show')" style="gap:6px">
    <i class="bi bi-send-check"></i>发送测试邮件
  </button>
  <button type="submit" formmethod="post" onclick="var f=document.getElementById('mainForm');var a=document.createElement('input');a.type='hidden';a.name='action';a.value='reset_template';f.appendChild(a);var c=document.createElement('input');c.type='hidden';c.name='csrf';c.value='<?= $csrf ?>';f.appendChild(c);"
          style="padding:8px 14px;border-radius:8px;border:1px solid var(--b1);background:var(--bg-c);color:var(--t2);cursor:pointer;font-size:.85rem;font-weight:500;display:inline-flex;align-items:center;gap:6px;transition:all .2s"
          onmouseover="this.style.borderColor='var(--b2)'" onmouseout="this.style.borderColor='var(--b1)'">
    <i class="bi bi-arrow-counterclockwise"></i>恢复默认模板
  </button>
</div>

</form>

<!-- ===== 测试邮件弹窗 ===== -->
<div id="test-email-modal" class="modal-overlay">
  <div class="modal-box">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
      <div style="font-weight:600;font-size:1rem">发送测试邮件</div>
      <button onclick="document.getElementById('test-email-modal').classList.remove('show')"
              style="background:none;border:none;color:var(--t3);cursor:pointer;font-size:1.2rem">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <form method="post">
      <input type="hidden" name="action" value="test">
      <input type="hidden" name="csrf" value="<?= $csrf ?>">
      <div class="field-label" style="margin-bottom:8px">收件邮箱</div>
      <input class="inp" type="email" name="test_email" placeholder="your@email.com" style="width:100%;margin-bottom:14px">
      <div style="font-size:.78rem;color:var(--t3);margin-bottom:14px">
        系统将发送一封使用 INTJ 测试类型的邮件到该邮箱。
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;gap:6px">
        <i class="bi bi-send"></i>确认发送
      </button>
    </form>
  </div>
</div>

<!-- SMTP 测试表单（隐藏，用于 AJAX 测试连接） -->
<form method="post" id="testSmtpForm" style="display:none">
  <input type="hidden" name="action" value="test_smtp">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
</form>

<script>
// Toggle 开关同步
document.getElementById('emailEnabled').addEventListener('change', function() {
    document.getElementById('emailEnabledHidden').value = this.checked ? '1' : '0';
});

// 表单提交前确保 toggle 值同步
document.getElementById('mainForm').addEventListener('submit', function() {
    document.getElementById('emailEnabledHidden').value = document.getElementById('emailEnabled').checked ? '1' : '0';
});

// 测试 SMTP 连接
function testSMTP() {
    var form = document.getElementById('testSmtpForm');
    var data = new FormData(form);
    fetch('email_settings.php', {method:'POST', body:data})
        .then(function(r) { return r.text(); })
        .then(function() { location.reload(); });
}
</script>

<?php include __DIR__ . '/footer.php'; ?>

<style>
.toggle-switch { cursor:pointer; display:inline-flex }
.toggle-input  { display:none }
.toggle-track {
    display:inline-block; width:44px; height:24px;
    background:var(--b1); border:1px solid var(--b1); border-radius:12px;
    position:relative; transition:background .25s;
}
.toggle-track::after {
    content:''; position:absolute; top:3px; left:3px;
    width:16px; height:16px; background:var(--t4); border-radius:50%;
    transition:transform .25s, background .25s;
}
.toggle-input:checked + .toggle-track { background:var(--p-dim); border-color:var(--p-dim) }
.toggle-input:checked + .toggle-track::after { transform:translateX(20px); background:#fff }

/* 弹窗 */
.modal-overlay {
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.7); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;
}
.modal-overlay.show { display:flex }
.modal-box {
    background:var(--bg-c); border:1px solid var(--b1); border-radius:var(--rs);
    padding:24px; width:420px; max-width:95vw;
    box-shadow:0 24px 60px rgba(0,0,0,.5);
}
</style>
