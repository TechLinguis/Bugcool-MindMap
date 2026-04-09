<?php
/**
 * MBTI 邮件发送类
 * 支持 SMTP 和 PHP mail() 两种模式
 */

require_once dirname(__DIR__) . '/config.php';

class EmailSender {

    /**
     * 确保数据表存在（首次访问时自动创建）
     */
    public static function ensureTable(): void {
        $db = Database::getInstance()->getConnection();
        $db->exec("CREATE TABLE IF NOT EXISTS email_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(64) NOT NULL UNIQUE,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    // ========== 配置读写 ==========

    /**
     * 从数据库读取邮件配置
     */
    public static function getConfig(): array {
        self::ensureTable();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT setting_key, setting_value FROM email_settings");
        $rows = $stmt->fetchAll();
        $config = [];
        foreach ($rows as $row) {
            $config[$row['setting_key']] = $row['setting_value'];
        }
        return $config;
    }

    /**
     * 保存邮件配置到数据库
     */
    public static function saveConfig(array $config): bool {
        $db = Database::getInstance()->getConnection();
        $allowed = [
            'email_enabled', 'smtp_host', 'smtp_port', 'smtp_encryption',
            'smtp_username', 'smtp_password', 'sender_name', 'sender_email',
            'email_subject', 'email_template', 'test_recipient'
        ];
        foreach ($allowed as $key) {
            $val = $config[$key] ?? '';
            // 密码留空时跳过（不覆盖已保存的密码）
            if ($key === 'smtp_password' && $val === '') continue;
            $stmt = $db->prepare(
                "INSERT INTO email_settings (setting_key, setting_value)
                 VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
            );
            $stmt->execute([$key, $val]);
        }
        return true;
    }

    /**
     * 获取单个配置项
     */
    public static function get(string $key, $default = '') {
        $config = self::getConfig();
        return $config[$key] ?? $default;
    }

    // ========== 发送邮件 ==========

    /**
     * 发送 MBTI 测试结果邮件
     */
    public static function sendResultEmail(array $certData, string $recipientEmail): bool {
        $enabled = self::get('email_enabled', '0');
        if ($enabled !== '1') return false;
        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = self::buildTemplate(self::get('email_subject', '你的 MBTI 测试结果：{mbti_type}'), $certData);
        $body    = self::buildTemplate(self::get('email_template', self::defaultTemplate()), $certData);

        return self::send($recipientEmail, $subject, $body);
    }

    /**
     * 发送测试邮件（管理员测试用）
     */
    public static function sendTestEmail(string $recipient): bool {
        $config = self::getConfig();
        $subject = '📋 MBTI 测试邮件配置成功！';
        $body = self::buildTemplate(self::defaultTemplate(), [
            'name'            => '测试用户',
            'mbti_type'       => 'INTJ',
            'certificate_no'   => 'TEST-20260408-ABC123',
            'type_name'       => '建筑师',
            'type_nickname'   => '战略家',
            'E' => 8, 'I' => 7, 'S' => 5, 'N' => 10,
            'T' => 9, 'F' => 6, 'J' => 8, 'P' => 7,
            'scores' => ['E'=>8,'I'=>7,'S'=>5,'N'=>10,'T'=>9,'F'=>6,'J'=>8,'P'=>7],
            'site_name' => SITE_NAME,
            'site_url'  => rtrim(SITE_URL, '/'),
            'test_mode' => true,
        ]);
        return self::send($recipient, $subject, $body);
    }

    /**
     * 实际发送邮件
     */
    public static function send(string $to, string $subject, string $body): bool {
        $host = self::get('smtp_host', '');

        if (!empty($host)) {
            return self::sendSMTP($to, $subject, $body);
        } else {
            return self::sendMail($to, $subject, $body);
        }
    }

    /**
     * PHP mail() 发送
     */
    private static function sendMail(string $to, string $subject, string $body): bool {
        $senderName  = self::get('sender_name', SITE_NAME);
        $senderEmail = self::get('sender_email', '');

        $headers = [
            'From: ' . $senderName . ' <' . ($senderEmail ?: 'noreply@localhost') . '>',
            'Reply-To: ' . ($senderEmail ?: 'noreply@localhost'),
            'X-Mailer: PHP/' . phpversion(),
            'Content-Type: text/html; charset=UTF-8',
            'MIME-Version: 1.0',
        ];

        return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
    }

    /**
     * SMTP 发送（使用 fsockopen）
     */
    private static function sendSMTP(string $to, string $subject, string $body): bool {
        $host       = self::get('smtp_host');
        $port       = (int)self::get('smtp_port', 465);
        $encryption = self::get('smtp_encryption', 'ssl');
        $username   = self::get('smtp_username');
        $password   = self::get('smtp_password');
        $senderName  = self::get('sender_name', SITE_NAME);
        $senderEmail = self::get('sender_email');

        $eol = "\r\n";

        // 建立连接
        $addr = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
        $socket = @fsockopen($addr, $port, $errno, $errstr, 15);
        if (!$socket) return false;

        $response = fgets($socket, 512);
        if (substr($response, 0, 3) !== '220') { fclose($socket); return false; }

        // EHLO（ESMTP 必须用 EHLO，HELO 是旧协议）
        $localHost = 'localhost';
        fputs($socket, "EHLO $localHost{$eol}");
        $ehloResp = '';
        while (($line = fgets($socket, 512)) !== false) {
            $ehloResp .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }

        // STARTTLS
        if ($encryption === 'tls') {
            if (strpos($ehloResp, 'STARTTLS') === false) {
                fclose($socket);
                return false; // 服务器不支持 STARTTLS
            }
            fputs($socket, "STARTTLS{$eol}");
            $tlsResp = fgets($socket, 512);
            if (substr($tlsResp, 0, 3) !== '220') { fclose($socket); return false; }
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            // STARTTLS 后必须重新 EHLO
            fputs($socket, "EHLO $localHost{$eol}");
            fgets($socket, 512);
        }

        // AUTH LOGIN
        if (!empty($username)) {
            fputs($socket, "AUTH LOGIN{$eol}");
            fgets($socket, 512);
            fputs($socket, base64_encode($username) . $eol);
            fgets($socket, 512);
            fputs($socket, base64_encode($password) . $eol);
            $authResp = fgets($socket, 512);
            if (substr($authResp, 0, 3) !== '235') { fclose($socket); return false; }
        }

        // MAIL FROM
        $from = $senderEmail ?: $username ?: 'noreply@localhost';
        fputs($socket, "MAIL FROM:<$from>{$eol}");
        $resp = fgets($socket, 512);
        if (substr($resp, 0, 3) !== '250') { fclose($socket); return false; }

        // RCPT TO
        fputs($socket, "RCPT TO:<$to>{$eol}");
        $resp = fgets($socket, 512);
        if (substr($resp, 0, 3) !== '250') { fclose($socket); return false; }

        // DATA
        fputs($socket, "DATA{$eol}");
        $resp = fgets($socket, 512);
        if (substr($resp, 0, 3) !== '354') { fclose($socket); return false; }

        // 构建邮件内容
        $fromHeader = "$senderName <$from>";
        $msgId = '<' . bin2hex(random_bytes(8)) . '@' . $host . '>';
        $date = date('r');

        $headers = [
            "From: $fromHeader",
            "To: $to",
            "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
            "Date: $date",
            "Message-ID: $msgId",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
            "X-Mailer: MBTI-PHP/" . phpversion(),
        ];

        fputs($socket, implode($eol, $headers) . $eol . $eol);
        fputs($socket, $body . $eol . '.' . $eol);
        fgets($socket, 512);

        // QUIT
        fputs($socket, "QUIT{$eol}");
        fclose($socket);

        return true;
    }

    // ========== 模板引擎 ==========

    /**
     * 替换模板变量
     */
    public static function buildTemplate(string $template, array $data): string {
        $replacements = [
            '{name}'            => htmlspecialchars($data['name'] ?? '亲爱的用户'),
            '{mbti_type}'       => $data['mbti_type'] ?? '',
            '{certificate_no}'  => $data['certificate_no'] ?? '',
            '{type_name}'       => $data['type_name'] ?? '',
            '{type_nickname}'   => $data['type_nickname'] ?? '',
            '{site_name}'       => $data['site_name'] ?? SITE_NAME,
            '{site_url}'        => $data['site_url'] ?? rtrim(SITE_URL, '/'),
            '{E}'  => $data['E'] ?? '—',
            '{I}'  => $data['I'] ?? '—',
            '{S}'  => $data['S'] ?? '—',
            '{N}'  => $data['N'] ?? '—',
            '{T}'  => $data['T'] ?? '—',
            '{F}'  => $data['F'] ?? '—',
            '{J}'  => $data['J'] ?? '—',
            '{P}'  => $data['P'] ?? '—',
            '{EI}' => ($data['E'] ?? 0) . ' / ' . ($data['I'] ?? 0),
            '{SN}' => ($data['S'] ?? 0) . ' / ' . ($data['N'] ?? 0),
            '{TF}' => ($data['T'] ?? 0) . ' / ' . ($data['F'] ?? 0),
            '{JP}' => ($data['J'] ?? 0) . ' / ' . ($data['P'] ?? 0),
        ];

        // 特殊：测试模式标识
        if (!empty($data['test_mode'])) {
            $replacements['{name}'] = '<span style="color:#f59e0b;font-weight:bold">[测试邮件]</span> 测试用户';
        }

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * 默认邮件模板
     */
    public static function defaultTemplate(): string {
        $siteName = SITE_NAME;
        $siteUrl  = rtrim(SITE_URL, '/');
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MBTI 测试结果</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td align="center" style="padding:40px 16px;">
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

<!-- 头部 -->
<tr>
<td style="background:linear-gradient(135deg,#6366f1,#ec4899);border-radius:16px 16px 0 0;padding:36px 32px;text-align:center;">
    <div style="font-size:2rem;margin-bottom:8px;">🧠</div>
    <h1 style="margin:0;color:#fff;font-size:1.6rem;font-weight:700;">{$siteName}</h1>
    <p style="margin:8px 0 0;color:rgba(255,255,255,0.85);font-size:0.95rem;">你的性格分析报告已生成</p>
</td>
</tr>

<!-- 类型卡片 -->
<tr>
<td style="background:#1e293b;padding:32px;text-align:center;border-left:1px solid #334155;border-right:1px solid #334155;">
    <div style="display:inline-block;background:rgba(129,140,248,0.12);border:1px solid rgba(129,140,248,0.25);border-radius:50px;padding:6px 20px;margin-bottom:16px;">
        <span style="color:#818cf8;font-size:0.8rem;font-weight:600;letter-spacing:1px;">MBTI 类型</span>
    </div>
    <div style="font-size:3.5rem;font-weight:900;letter-spacing:4px;background:linear-gradient(135deg,#818cf8,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;margin-bottom:8px;">{mbti_type}</div>
    <div style="color:#94a3b8;font-size:1.1rem;margin-bottom:4px;">{type_name}</div>
    <div style="color:#64748b;font-size:0.88rem;">{type_nickname}</div>
</td>
</tr>

<!-- 维度得分 -->
<tr>
<td style="background:#0f172a;padding:28px 32px;border-left:1px solid #334155;border-right:1px solid #334155;">
    <h3 style="color:#f1f5f9;font-size:1rem;margin:0 0 16px;font-weight:600;text-align:center;">各维度得分</h3>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:420px;margin:0 auto;">
    <tr>
        <td style="padding:10px 0;border-bottom:1px solid #1e293b;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#818cf8;font-weight:600;font-size:0.9rem;">E / I</span>
                <span style="color:#94a3b8;font-size:0.9rem;">{E} : {I}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0;border-bottom:1px solid #1e293b;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#22d3ee;font-weight:600;font-size:0.9rem;">S / N</span>
                <span style="color:#94a3b8;font-size:0.9rem;">{S} : {N}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0;border-bottom:1px solid #1e293b;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#f472b6;font-weight:600;font-size:0.9rem;">T / F</span>
                <span style="color:#94a3b8;font-size:0.9rem;">{T} : {F}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#fbbf24;font-weight:600;font-size:0.9rem;">J / P</span>
                <span style="color:#94a3b8;font-size:0.9rem;">{J} : {P}</span>
            </div>
        </td>
    </tr>
    </table>
</td>
</tr>

<!-- 证书编号 -->
<tr>
<td style="background:#0f172a;padding:20px 32px;border-left:1px solid #334155;border-right:1px solid #334155;text-align:center;">
    <p style="color:#64748b;font-size:0.8rem;margin:0 0 6px;">证书编号</p>
    <p style="color:#818cf8;font-size:1.05rem;font-weight:700;margin:0;font-family:monospace;letter-spacing:1px;">{certificate_no}</p>
</td>
</tr>

<!-- CTA 按钮 -->
<tr>
<td style="background:#1e293b;padding:24px 32px;border-left:1px solid #334155;border-right:1px solid #334155;text-align:center;">
    <a href="{site_url}/result.php?cert={certificate_no}" style="display:inline-block;background:linear-gradient(135deg,#6366f1,#ec4899);color:#fff;text-decoration:none;padding:14px 36px;border-radius:50px;font-weight:600;font-size:1rem;box-shadow:0 8px 24px rgba(99,102,241,0.4);">
        🌟 查看完整报告
    </a>
    <p style="color:#475569;font-size:0.8rem;margin:12px 0 0;">点击查看详细性格分析、职业建议等</p>
</td>
</tr>

<!-- 页脚 -->
<tr>
<td style="background:#0f172a;border-radius:0 0 16px 16px;padding:24px 32px;border:1px solid #334155;border-top:none;text-align:center;">
    <p style="color:#475569;font-size:0.8rem;margin:0 0 4px;">{$siteName} · 基于荣格心理类型理论</p>
    <p style="color:#334155;font-size:0.75rem;margin:0;">本邮件由系统自动发送，请勿回复</p>
</td>
</tr>

</table>
</td>
</tr>
</table>
</body>
</html>
HTML;
    }

    /**
     * 验证 SMTP 连接（ping）
     */
    public static function testSMTPConnection(): array {
        $host       = self::get('smtp_host');
        $port       = (int)self::get('smtp_port', 465);
        $encryption = self::get('smtp_encryption', 'ssl');

        if (empty($host)) return ['ok' => false, 'msg' => 'SMTP 主机未配置'];

        $addr = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
        $socket = @fsockopen($addr, $port, $errno, $errstr, 10);

        if (!$socket) {
            return ['ok' => false, 'msg' => "无法连接到 {$host}:{$port}（错误 {$errno}: {$errstr}）"];
        }

        $response = fgets($socket, 512);
        if (substr($response, 0, 3) !== '220') {
            fclose($socket);
            return ['ok' => false, 'msg' => "SMTP 响应异常: " . trim($response)];
        }

        // 发送 EHLO
        fputs($socket, "EHLO localhost\r\n");
        $ehloResp = '';
        while (($line = fgets($socket, 512)) !== false) {
            $ehloResp .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        fclose($socket);

        if (substr($ehloResp, 0, 3) === '250') {
            return ['ok' => true, 'msg' => "SMTP 连接成功，服务器支持 EHLO（{$host}:{$port}）"];
        }

        return ['ok' => false, 'msg' => "SMTP EHLO 失败: " . trim($ehloResp)];
    }
}
