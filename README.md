# Bugcool MindMap - MBTI 性格测试系统

> 一个基于科学 MBTI 理论的在线性格测试平台，支持完整的 16 型人格分析、专属电子证书生成与下载、证书查询分享等完整功能。

**在线访问**: [https://mbti.bugcool.cn](https://mbti.bugcool.cn)

---

## 🎯 功能特性

### 核心测试
- **80 道标准测评题**：覆盖 E/I、S/N、T/F、J/P 四个维度，每维度 20 题
- **荣格心理类型理论支撑**：基于 70+ 年实践验证的经典 MBTI 框架
- **即时评分计算**：完成后立刻呈现完整性格分析报告

### 结果分析
- **四维百分比雷达**：直观展示各维度倾向程度
- **性格特征解读**：详细描述你的核心特质、工作风格、沟通方式
- **优势与成长空间**：SWOT 视角的自我认知
- **理想职业推荐**：结合性格特点的职场方向建议
- **代表人物参考**：同类型名人榜样

### 证书系统
- **自动生成专属证书**：包含唯一证书编号、测试日期、MBTI 类型
- **一键下载 PNG**：基于 html2canvas 的前端截图方案
- **多平台分享**：微信、微博、QQ 分享 + 复制链接
- **永久可查**：支持按证书编号精确查询和按姓名模糊查询

### 实时数据
- **类型热度排行榜**：展示最常见的 5 种人格类型
- **累计测试人数**：实时统计站点总测试量

### 用户体验
- **深色 / 浅色主题切换**：支持手动选择或跟随系统
- **全平台响应式设计**：完美适配手机、平板、桌面端
- **零注册门槛**：无需登录，直接开始测试
- **首屏性能优化**：字库子集 + 字体预加载 + 资源延迟加载

---

## 🗂️ 项目结构

```
mbti-php/
├── config.php              # 数据库连接与站点配置
├── api.php                 # 统一 API 接口（统计、查询等 AJAX 请求）
├── index.php               # 首页（介绍 + 排行榜 + CTA）
├── test.php                # 测试页面（80 道题目）
├── result.php              # 结果分析 + 证书展示 + 下载分享
├── query.php               # 证书查询页面
├── encyclopedia.php        # MBTI 百科（16 型人格详解）
├── about.php               # 关于本站
│
├── admin/                  # 后台管理（数据管理、类型编辑）
│   ├── index.php           # 管理后台首页
│   ├── footer.php
│   └── ...
│
├── assets/
│   ├── css/
│   │   └── bootstrap.min.css
│   ├── js/
│   │   ├── bootstrap.bundle.min.js
│   │   ├── html2canvas.min.js      # 证书截图
│   │   └── qrcode.min.js           # 二维码生成
│   └── fonts/
│       └── bootstrap-icons.woff*   # 图标字体
│
├── includes/
│   ├── header.php          # 公共头部（导航栏 + 主题切换 + 全局样式）
│   └── footer.php          # 公共底部（链接 + 版权）
│
├── database/
│   └── mbti_database.sql   # 完整数据库初始化脚本
│
└── ttf/
    ├── DouyinSansBold.ttf          # 抖音美好体完整版
    ├── DouyinSansBold-subset.woff2 # 首屏优化子集
    └── OFL.txt                     # 字体版权说明
```

---

## 🛠️ 环境要求

| 项目 | 要求 |
|------|------|
| PHP | 7.4+（推荐 8.0+） |
| MySQL | 5.7+ / MariaDB 10.3+ |
| PHP 扩展 | PDO + PDO_mysql |
| Web 服务器 | Apache / Nginx / IIS（任意支持 PHP 的均可） |

---

## 🚀 部署指南

### 1. 导入数据库

在 MySQL 中创建一个新数据库（名称需与 `config.php` 中的 `DB_NAME` 一致），然后导入数据库脚本：

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS your_db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p your_db_name < database/mbti_database.sql
```

数据库脚本包含：
- `questions` 表 — 80 道标准测试题
- `mbti_types` 表 — 16 型人格完整描述数据
- `certificates` 表 — 证书记录
- `statistics` 表 — 每日类型统计

### 2. 修改配置

编辑 `config.php`，填入你的实际数据库信息：

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// 站点 URL（用于分享链接和证书二维码）
define('SITE_URL', 'https://your-domain.com/');
```

### 3. 上传文件

将整个 `mbti-php` 目录上传到你的 Web 服务器对应路径下。

### 4. 配置 Web 服务器

#### Apache（已包含 .htaccess 伪静态规则）

确保开启了 `mod_rewrite` 模块。

#### Nginx 配置参考

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/mbti-php;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. 设置目录权限

```bash
chmod -R 755 /path/to/mbti-php
chown -R www-data:www-data /path/to/mbti-php  # Linux 环境
```

### 6. 访问测试

打开浏览器访问：`https://your-domain.com/`

---

## 🎨 自定义配置

### 修改主题色

编辑 `includes/header.php` 中的 CSS 变量：

```css
:root {
    --primary: #818CF8;     /* 主色调：靛蓝 */
    --rose: #F472B6;        /* 辅助色：品红 */
    --cyan: #22D3EE;        /* 强调色：青色 */
    --amber: #FBBF24;       /* 警示色：琥珀 */
    --emerald: #34D399;     /* 成功色：翠绿 */
}
```

### 修改站点名称

编辑 `config.php`：

```php
define('SITE_NAME', '你的站点名称');
define('SITE_URL', 'https://your-domain.com/');
define('SITE_DESCRIPTION', '站点描述 SEO 信息');
```

### 添加 / 修改测试题目

直接操作 MySQL 中的 `questions` 表。每道题目结构：

| 字段 | 说明 |
|------|------|
| id | 题目编号 |
| dimension | 维度（EI / SN / TF / JP） |
| question_text | 题目内容 |
| option_a / option_b | 两个选项（分别代表维度两极） |
| weight_a / weight_b | 选项权重 |

### 修改人格类型描述

操作 `mbti_types` 表，可修改各类型的完整描述。

---

## 🔒 安全特性

- **PDO 预处理语句**：所有数据库操作使用参数化查询，防止 SQL 注入
- **XSS 防护**：输出内容使用 `htmlspecialchars()` 转义
- **错误处理**：生产环境关闭错误显示，避免信息泄露
- **无账号体系**：无需注册，无敏感个人信息存储

---

## 📦 第三方依赖

| 库 / 资源 | 版本 | 用途 | 许可证 |
|-----------|------|------|--------|
| Bootstrap | 5.3.x | 响应式 CSS 框架 | MIT |
| Bootstrap Icons | 1.11.x | 图标库 | MIT |
| html2canvas | 1.4.x | 证书截图 | MIT |
| qrcode.js | - | 二维码生成 | MIT |
| 抖音美好体 | - | 中文字体 | [OFL.txt](ttf/OFL.txt) |

---

## 📄 开源协议

本项目基于 [MIT License](LICENSE) 开源，你可以自由使用、修改和商业化，但请保留原作者版权声明。

---

## 🙏 致谢

- MBTI 理论基于 Katharine Cook Briggs 与 Isabel Briggs Myers 的研究成果
- 图标来自 [Bootstrap Icons](https://icons.bootcss.com/)
- 字体使用字节跳动「抖音美好体」

---

**Bugcool MindMap** — 帮助你更好地认识自己。
