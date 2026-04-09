<?php require_once dirname(__DIR__) . '/config.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= SITE_NAME ?></title>
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">

    <script>
        (function() {
            const storageKey = 'bugcool-theme-preference';
            try {
                const storedTheme = localStorage.getItem(storageKey);
                const selectedTheme = ['dark', 'light', 'system'].includes(storedTheme) ? storedTheme : 'system';
                const resolvedTheme = selectedTheme === 'system'
                    ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                    : selectedTheme;
                document.documentElement.setAttribute('data-theme', resolvedTheme);
                document.documentElement.setAttribute('data-theme-mode', selectedTheme);
                document.documentElement.style.colorScheme = resolvedTheme;
            } catch (error) {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.setAttribute('data-theme-mode', 'system');
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>

    <!-- ===== 性能优化：关键资源预加载 ===== -->

    <link rel="preload" href="ttf/DouyinSansBold-subset.woff2" as="font" type="font/woff2" crossorigin fetchpriority="high">

    
    <!-- Bootstrap CSS — 延迟加载，不阻塞渲染（print trick） -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/bootstrap.min.css"></noscript>
    
    <!-- Bootstrap Icons — CDN 加载，支持跨页面缓存 -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>


    <!-- 本站字体策略：优先命中更完整的首屏抖音美好体子集，再按需补完整字库，不显式回退到系统字体 -->
    <style>
        @font-face {
            font-family: 'DouyinSansSubset';
            src: url('ttf/DouyinSansBold-subset.woff2') format('woff2');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'DouyinSansFull';
            src: url('ttf/DouyinSansBold.ttf') format('truetype');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
        }
    </style>



    
    <style>
        /* ============================================
           设计令牌 — 深 / 浅主题
           ============================================ */
        :root {
            color-scheme: dark;

            /* 主色调：靛蓝 → 品红渐变系 */
            --primary: #818CF8;
            --primary-dim: #6366F1;
            --primary-bright: #A5B4FC;
            --primary-glow: rgba(129, 140, 248, 0.25);
            --primary-glass: rgba(129, 140, 248, 0.08);
            --primary-surface: rgba(129, 140, 248, 0.05);

            /* 辅助色 */
            --rose: #F472B6;
            --rose-glow: rgba(244, 114, 182, 0.2);
            --cyan: #22D3EE;
            --cyan-glow: rgba(34, 211, 238, 0.18);
            --amber: #FBBF24;
            --amber-glow: rgba(251, 191, 36, 0.18);
            --emerald: #34D399;
            --emerald-glow: rgba(52, 211, 153, 0.18);

            /* 背景层级 */
            --bg-0: #050508;
            --bg-1: #0C0C14;
            --bg-2: #12121E;
            --bg-3: #1A1A2E;
            --bg-4: #222240;
            --bg-card: #111120;
            --bg-card-hover: #16162A;
            --bg-input: #0E0E1A;

            /* 文字层级 */
            --text-1: #EEEEF5;
            --text-2: #A1A1B8;
            --text-3: #6B6B85;
            --text-4: #45455A;

            /* 边框 */
            --border-1: rgba(255,255,255,0.04);
            --border-2: rgba(255,255,255,0.08);
            --border-3: rgba(255,255,255,0.12);

            /* 阴影 */
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.4);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.5);
            --shadow-xl: 0 16px 64px rgba(0,0,0,0.6);
            --shadow-glow: 0 0 30px var(--primary-glow);

            /* 导航栏 */
            --nav-bg: rgba(5, 5, 8, 0.82);
            --nav-bg-scrolled: rgba(5, 5, 8, 0.95);

            /* 进度条 / 代码 */
            --progress-bg: rgba(129, 140, 248, 0.08);
            --code-bg: #1A1A2E;

            /* 装饰 */
            --grid-dot: rgba(129, 140, 248, 0.035);
            --surface-hero: linear-gradient(160deg, #080818 0%, #0C0C20 30%, #12102E 60%, #0A0A18 100%);
            --surface-cta: linear-gradient(160deg, #080816 0%, #0F0F28 45%, #0C0C1E 100%);
            --surface-footer: var(--bg-0);
            --surface-spotlight: linear-gradient(135deg, rgba(99,102,241,0.05), rgba(15,15,30,0.98));
            --surface-step-primary: linear-gradient(170deg, rgba(129,140,248,0.06) 0%, rgba(15,15,30,0.95) 100%);
            --surface-step-emerald: linear-gradient(170deg, rgba(52,211,153,0.05) 0%, rgba(15,15,30,0.95) 100%);
            --surface-step-rose: linear-gradient(170deg, rgba(244,114,182,0.05) 0%, rgba(15,15,30,0.95) 100%);
            --surface-step-core-primary: linear-gradient(145deg, #1E1B4B, #12122A);
            --surface-step-core-emerald: linear-gradient(145deg, #064E3B, #111A18);
            --surface-step-core-rose: linear-gradient(145deg, #500724, #16101D);
            --surface-floating-pill: rgba(18, 18, 30, 0.6);
            --surface-floating-pill-soft: rgba(18, 18, 30, 0.5);
            --surface-result-panel: linear-gradient(180deg, rgba(16,16,28,0.94) 0%, rgba(18,18,32,0.92) 100%);
            --surface-result-subtle: rgba(255,255,255,0.04);
            --surface-result-subtle-2: rgba(255,255,255,0.03);
            --surface-result-outline: rgba(255,255,255,0.06);
            --surface-result-outline-soft: rgba(255,255,255,0.05);
            --surface-result-divider: rgba(255,255,255,0.08);
            --surface-result-rank: rgba(255,255,255,0.06);
            --surface-certificate-shell: linear-gradient(165deg, #10101C 0%, #181830 40%, #12121F 100%);
            --surface-certificate-frame: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 30%, rgba(129,140,248,0.06) 50%, rgba(255,255,255,0.02) 70%, rgba(255,255,255,0.06) 100%);
            --surface-certificate-inner: linear-gradient(170deg, rgba(20,20,36,0.95) 0%, rgba(14,14,26,1) 100%);
            --surface-certificate-soft: rgba(255,255,255,0.02);
            --cursor-glow-opacity: 0.35;
            --cursor-glow-blend: screen;

            /* 玻璃拟态 */
            --glass-bg: rgba(18, 18, 30, 0.6);


            /* 圆角 */
            --radius-sm: 8px;
            --radius: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
        }

        :root[data-theme="light"] {
            color-scheme: light;
            --primary: #6D6AF8;
            --primary-dim: #5651F2;
            --primary-bright: #8E8BFF;
            --primary-glow: rgba(109, 106, 248, 0.18);
            --primary-glass: rgba(109, 106, 248, 0.12);
            --primary-surface: rgba(109, 106, 248, 0.08);

            --rose: #EC4899;
            --rose-glow: rgba(236, 72, 153, 0.14);
            --cyan: #0891B2;
            --cyan-glow: rgba(8, 145, 178, 0.14);
            --amber: #D97706;
            --amber-glow: rgba(217, 119, 6, 0.14);
            --emerald: #059669;
            --emerald-glow: rgba(5, 150, 105, 0.14);

            --bg-0: #F8FAFF;
            --bg-1: #EEF2FF;
            --bg-2: #FFFFFF;
            --bg-3: #F3F5FF;
            --bg-4: #E4E9F7;
            --bg-card: rgba(255, 255, 255, 0.88);
            --bg-card-hover: #FFFFFF;
            --bg-input: #FFFFFF;

            --text-1: #16182E;
            --text-2: #4F5875;
            --text-3: #7C86A2;
            --text-4: #98A2B8;

            --border-1: rgba(15, 23, 42, 0.06);
            --border-2: rgba(99, 102, 241, 0.12);
            --border-3: rgba(99, 102, 241, 0.2);

            --shadow-sm: 0 12px 32px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 20px 46px rgba(99, 102, 241, 0.12);
            --shadow-lg: 0 28px 72px rgba(99, 102, 241, 0.15);
            --shadow-xl: 0 34px 92px rgba(15, 23, 42, 0.18);
            --shadow-glow: 0 0 30px rgba(109, 106, 248, 0.14);

            --nav-bg: rgba(255, 255, 255, 0.72);
            --nav-bg-scrolled: rgba(255, 255, 255, 0.92);

            --progress-bg: rgba(109, 106, 248, 0.12);
            --code-bg: #EEF2FF;

            --grid-dot: rgba(109, 106, 248, 0.08);
            --surface-hero: linear-gradient(160deg, #F9FAFF 0%, #EEF2FF 32%, #FDF2F8 68%, #F0F9FF 100%);
            --surface-cta: linear-gradient(160deg, #F8FAFF 0%, #EEF2FF 42%, #FDF2F8 100%);
            --surface-footer: linear-gradient(180deg, #EEF2FF 0%, #FFFFFF 100%);
            --surface-spotlight: linear-gradient(135deg, rgba(109,106,248,0.08), rgba(255,255,255,0.94));
            --surface-step-primary: linear-gradient(170deg, rgba(109,106,248,0.12) 0%, rgba(255,255,255,0.96) 100%);
            --surface-step-emerald: linear-gradient(170deg, rgba(5,150,105,0.10) 0%, rgba(255,255,255,0.96) 100%);
            --surface-step-rose: linear-gradient(170deg, rgba(236,72,153,0.10) 0%, rgba(255,255,255,0.96) 100%);
            --surface-step-core-primary: linear-gradient(145deg, #DDE2FF, #FFFFFF);
            --surface-step-core-emerald: linear-gradient(145deg, #DFF7EE, #FFFFFF);
            --surface-step-core-rose: linear-gradient(145deg, #FFE4F1, #FFFFFF);
            --surface-floating-pill: rgba(255, 255, 255, 0.82);
            --surface-floating-pill-soft: rgba(255, 255, 255, 0.72);
            --surface-result-panel: linear-gradient(180deg, rgba(255,255,255,0.96) 0%, rgba(244,246,255,0.96) 100%);
            --surface-result-subtle: rgba(109,106,248,0.08);
            --surface-result-subtle-2: rgba(109,106,248,0.06);
            --surface-result-outline: rgba(109,106,248,0.14);
            --surface-result-outline-soft: rgba(15,23,42,0.08);
            --surface-result-divider: rgba(109,106,248,0.16);
            --surface-result-rank: rgba(109,106,248,0.10);
            --surface-certificate-shell: linear-gradient(165deg, #FFFFFF 0%, #F4F6FF 42%, #FFF8FC 100%);
            --surface-certificate-frame: linear-gradient(135deg, rgba(109,106,248,0.14) 0%, rgba(255,255,255,0.96) 38%, rgba(236,72,153,0.08) 68%, rgba(255,255,255,0.96) 100%);
            --surface-certificate-inner: linear-gradient(170deg, rgba(255,255,255,0.98) 0%, rgba(245,247,255,1) 100%);
            --surface-certificate-soft: rgba(109,106,248,0.06);
            --cursor-glow-opacity: 0.2;
            --cursor-glow-blend: multiply;

            /* 玻璃拟态 */
            --glass-bg: rgba(255, 255, 255, 0.72);

        }


        /* ============================================
           基础重置
           ============================================ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DouyinSansSubset', 'DouyinSansFull';
            color: var(--text-1);
            background:
                radial-gradient(circle at top, var(--primary-glass), transparent 40%),
                var(--bg-0);
            line-height: 1.75;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            transition: background 0.35s ease, color 0.35s ease;
        }




        /* ============================================
           导航栏
           ============================================ */
        .navbar-mbti {
            background: var(--nav-bg);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid var(--border-1);
            padding: 0.65rem 0;
            transition: background 0.4s ease, box-shadow 0.3s ease;
            z-index: 1050;
        }
        .navbar-mbti.scrolled {
            background: var(--nav-bg-scrolled);
            box-shadow: 0 1px 20px rgba(0,0,0,0.4);
        }
        .navbar-brand-mbti {
            font-family: 'DouyinSansSubset', 'DouyinSansFull';
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary) !important;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary-dim), var(--rose));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 2px 12px var(--primary-glow);
        }
        .nav-link-mbti {
            font-weight: 500;
            color: var(--text-2) !important;
            padding: 8px 18px !important;
            border-radius: 10px;
            transition: all 0.25s ease;
            font-size: 0.92rem;
            position: relative;
        }
        .nav-link-mbti:hover {
            color: var(--primary) !important;
            background: var(--primary-glass);
        }
        .nav-link-mbti.active {
            color: var(--primary) !important;
            background: var(--primary-glass);
            font-weight: 600;
        }
        .nav-link-mbti.active::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 50%;
            transform: translateX(-50%);
            width: 16px; height: 3px;
            border-radius: 3px;
            background: var(--primary);
        }
        .navbar-toggler {
            border-color: var(--border-2) !important;
        }
        .navbar-toggler-icon {
            filter: invert(0.8);
        }
        :root[data-theme="light"] .navbar-toggler-icon {
            filter: invert(0.18);
        }

        .theme-switcher {
            position: relative;
        }
        .theme-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 132px;
            justify-content: space-between;
            padding: 0.72rem 0.95rem;
            border-radius: 999px;
            border: 1px solid var(--border-2);
            background: var(--bg-2);

            color: var(--text-2);
            font-size: 0.86rem;
            font-weight: 600;
            line-height: 1;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;

        }
        .theme-trigger:hover {
            color: var(--text-1);
            border-color: var(--border-3);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .theme-trigger-main {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }
        .theme-trigger-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-glass);
            box-shadow: inset 0 0 0 1px var(--border-1);
        }
        .theme-trigger-caret {
            color: var(--text-4);
            font-size: 0.72rem;
            transition: transform 0.25s ease;
        }
        .theme-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            width: 220px;
            padding: 0.7rem;
            border-radius: 20px;
            border: 1px solid var(--border-2);
            background: var(--bg-2);
            backdrop-filter: blur(22px);

            -webkit-backdrop-filter: blur(22px);
            box-shadow: var(--shadow-lg);
            opacity: 0;
            transform: translateY(10px) scale(0.98);
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
            z-index: 1200;
        }
        .theme-panel.is-open {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .theme-panel.is-open + .theme-trigger .theme-trigger-caret,
        .theme-trigger[aria-expanded="true"] .theme-trigger-caret {
            transform: rotate(180deg);
        }
        .theme-panel-title {
            display: block;
            padding: 0.3rem 0.5rem 0.55rem;
            color: var(--text-3);
            font-size: 0.74rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .theme-option {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            padding: 0.85rem 0.9rem;
            border: 1px solid transparent;
            border-radius: 16px;
            background: transparent;
            color: var(--text-2);
            cursor: pointer;
            text-align: left;
            transition: background 0.25s ease, color 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .theme-option:hover {
            background: var(--primary-surface);
            color: var(--text-1);
            border-color: var(--border-1);
            transform: translateX(2px);
        }
        .theme-option.active {
            background: linear-gradient(135deg, var(--primary-glass), rgba(244, 114, 182, 0.08));
            border-color: var(--border-2);
            color: var(--text-1);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
        }
        .theme-option-main {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
        }
        .theme-option-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-glass);
        }
        .theme-option small {
            display: block;
            margin-top: 0.1rem;
            color: var(--text-4);
        }
        .theme-option-check {
            color: var(--primary);
            opacity: 0;
            transform: scale(0.85);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .theme-option.active .theme-option-check {
            opacity: 1;
            transform: scale(1);
        }

        /* ============================================
           按钮系统
           ============================================ */

        .btn-primary-mbti {
            background: linear-gradient(135deg, var(--primary-dim) 0%, var(--primary) 50%, var(--rose) 100%);
            background-size: 200% auto;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 14px 36px;
            border-radius: var(--radius);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px var(--primary-glow);
            font-size: 1.02rem;
            letter-spacing: 0.2px;
            position: relative;
            overflow: hidden;
        }
        .btn-primary-mbti::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            transition: left 0.5s ease;
        }
        .btn-primary-mbti:hover::before { left: 100%; }
        .btn-primary-mbti:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px var(--primary-glow);
            background-position: right center;
            color: #fff;
        }
        .btn-primary-mbti:active { transform: translateY(-1px); }

        .btn-secondary-mbti {
            background: var(--bg-2);
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }
        .btn-secondary-mbti:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: var(--shadow-glow);
        }
        .btn-outline-mbti {
            border: 1px solid var(--border-3);
            color: var(--text-2);
            font-weight: 500;
            padding: 10px 22px;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            background: var(--bg-2);
        }
        .btn-outline-mbti:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-glass);
            transform: translateY(-2px);
        }

        /* ============================================
           卡片系统
           ============================================ */
        .card-mbti {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-1);
            box-shadow: var(--shadow-sm);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-mbti:hover {
            background: var(--bg-card-hover);
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
            border-color: var(--border-2);
        }

        .section-title {
            font-weight: 800;
            color: var(--text-1);
            margin-bottom: 0.5rem;
            letter-spacing: -0.3px;
        }
        .section-subtitle { color: var(--text-2); font-size: 1.05rem; }

        .badge-mbti {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.82rem;
            backdrop-filter: blur(8px);
        }

        /* ============================================
           装饰元素
           ============================================ */
        .grid-bg {
            background-image: radial-gradient(circle, var(--grid-dot) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* ============================================
           动画系统
           ============================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.92); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-12px) rotate(1deg); }
            66% { transform: translateY(-6px) rotate(-1deg); }
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-18px) scale(1.02); }
        }
        @keyframes floatHorizontal {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(20px); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes gradientMove {
            0%,100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px var(--primary-glow); }
            50% { box-shadow: 0 0 40px var(--primary-glow), 0 0 60px rgba(129,140,248,0.08); }
        }
        @keyframes morphBlob {
            0%, 100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; }
            34% { border-radius: 70% 30% 46% 54% / 30% 29% 71% 70%; }
            67% { border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%; }
        }
        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes heroGlow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.12); opacity: 0.8; }
        }

        .animate-fadeInUp { animation: fadeInUp 0.7s cubic-bezier(0.4,0,0.2,1) both; }
        .animate-fadeIn { animation: fadeIn 0.5s ease both; }
        .animate-fadeInScale { animation: fadeInScale 0.6s cubic-bezier(0.4,0,0.2,1) both; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-float-slow { animation: floatSlow 6s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-morph { animation: morphBlob 8s ease-in-out infinite; }
        .animate-rotate-slow { animation: rotate-slow 20s linear infinite; }

        /* 全局浮动光球 */
        .hero-gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.35;
        }

        /* ============================================
           加载动画
           ============================================ */
        .loading-spinner {
            display: inline-block;
            width: 44px; height: 44px;
            border: 3px solid var(--primary-glass);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        /* ============================================
           Footer
           ============================================ */
        .footer-mbti {
            background: var(--bg-0);
            color: var(--text-3);
            padding: 4rem 0 2rem;
            margin-top: 4rem;
            position: relative;
            border-top: 1px solid var(--border-1);
        }
        .footer-mbti::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-glow), transparent);
        }
        .footer-mbti a {
            color: var(--text-3);
            text-decoration: none;
            transition: color 0.25s;
        }
        .footer-mbti a:hover { color: var(--text-1); }
        .footer-title {
            font-family: 'DouyinSansSubset', 'DouyinSansFull';
            color: var(--text-1);
            font-weight: 700;
        }



        /* ============================================
           Toast
           ============================================ */
        .toast-mbti {
            position: fixed;
            top: 84px; right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 16px 22px;
            border-radius: var(--radius);
            color: #fff;
            font-weight: 500;
            box-shadow: var(--shadow-xl);
            animation: slideInRight 0.4s cubic-bezier(0.4,0,0.2,1);
            display: none;
            backdrop-filter: blur(12px);
            font-size: 0.95rem;
        }
        .toast-mbti.success { background: linear-gradient(135deg, #34D399, #059669); }
        .toast-mbti.error { background: linear-gradient(135deg, #F472B6, #DB2777); }
        .toast-mbti.info { background: linear-gradient(135deg, var(--primary-dim), var(--primary)); }

        /* ============================================
           玻璃拟态
           ============================================ */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-2);
            transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        }

        .navbar-mbti,
        .card-mbti,
        .footer-mbti,
        .form-control,
        .form-select,
        .input-group-text {
            transition: background 0.35s ease, color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        }

        .hero-home {
            background: var(--surface-hero) !important;
            transition: background 0.35s ease;
        }

        :root[data-theme="light"] .glass {
            background: rgba(255, 255, 255, 0.72);
            box-shadow: 0 18px 42px rgba(109, 106, 248, 0.12);
        }
        :root[data-theme="light"] .hero-gradient-orb {
            filter: blur(80px);
            opacity: 0.18;
        }
        :root[data-theme="light"] .brand-icon {
            box-shadow: 0 10px 30px rgba(109, 106, 248, 0.22);
        }
        :root[data-theme="light"] .footer-mbti {
            background: var(--surface-footer);
        }
        :root[data-theme="light"] .theme-trigger,
        :root[data-theme="light"] .theme-panel {
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12);
        }
        :root[data-theme="light"] .theme-option.active {
            box-shadow: inset 0 0 0 1px rgba(109, 106, 248, 0.08);
        }

        /* ============================================
           渐变文字
           ============================================ */

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--rose));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ============================================
           噪点纹理
           ============================================ */
        .noise::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.03;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            pointer-events: none;
            border-radius: inherit;
        }

        /* ============================================
           全局覆盖 Bootstrap 暗色不适配
           ============================================ */
        .modal-content {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-2) !important;
            color: var(--text-1);
        }
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .form-control, .form-select {
            background-color: var(--bg-input) !important;
            border-color: var(--border-2) !important;
            color: var(--text-1) !important;
        }
        .form-control::placeholder { color: var(--text-4) !important; }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-glow) !important;
        }
        .form-label { color: var(--text-2); }
        .text-muted { color: var(--text-3) !important; }

        /* ============================================
           响应式 + 性能优化
           ============================================ */
        /* GPU 加速层 — 防止闪烁和提升动画性能 */
        .navbar-mbti, .card-mbti, .btn-primary-mbti,
        .hero-gradient-orb, .animate-float, .animate-float-slow,
        .animate-pulse-glow, .animate-morph {
            will-change: transform;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        /* 滚动容器隔离 */
        main { contain: content; }

        @media (max-width: 991.98px) {
            .theme-switcher {
                width: 100%;
            }
            .theme-trigger,
            .theme-panel {
                width: 100%;
            }
            .theme-panel {
                position: static;
                margin-bottom: 0.75rem;
                display: none;
                transform: none;
            }
            .theme-panel.is-open {
                display: block;
            }
        }
        @media (max-width: 768px) {
            .section-title { font-size: 1.5rem !important; }
            .btn-primary-mbti { padding: 12px 26px; font-size: 0.98rem; }
            .toast-mbti { left: 16px; right: 16px; min-width: auto; }
        }

        @media (max-width: 576px) {
            .section-title { font-size: 1.3rem !important; }
            .navbar-brand-mbti { font-size: 1.15rem; }
            .brand-icon { width: 32px; height: 32px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-mbti fixed-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-mbti" href="index.php">
                <span class="brand-icon"><i class="bi bi-puzzle"></i></span>
                <span>Bugcool MindMap</span>

            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto gap-1 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-mbti <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>" href="index.php">
                            <i class="bi bi-house-door me-1" style="font-size:0.85rem;"></i>首页
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-mbti <?= (basename($_SERVER['PHP_SELF']) == 'test.php') ? 'active' : '' ?>" href="test.php">
                            <i class="bi bi-lightning-charge me-1" style="font-size:0.85rem;"></i>开始测试


                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-mbti <?= (basename($_SERVER['PHP_SELF']) == 'query.php') ? 'active' : '' ?>" href="query.php">
                            <i class="bi bi-search me-1" style="font-size:0.85rem;"></i>证书查询
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-mbti <?= (basename($_SERVER['PHP_SELF']) == 'encyclopedia.php') ? 'active' : '' ?>" href="encyclopedia.php">
                            <i class="bi bi-book me-1" style="font-size:0.85rem;"></i>百科
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-mbti <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : '' ?>" href="about.php">
                            <i class="bi bi-info-circle me-1" style="font-size:0.85rem;"></i>关于
                        </a>
                    </li>
                </ul>
                <div class="theme-switcher ms-lg-3 mt-3 mt-lg-0">
                    <div class="theme-panel" data-theme-panel>
                        <span class="theme-panel-title">界面主题</span>
                        <button type="button" class="theme-option" data-theme-option="dark">
                            <span class="theme-option-main">
                                <span class="theme-option-icon"><i class="bi bi-moon-stars-fill"></i></span>
                                <span>
                                    <strong class="d-block" style="font-size:0.9rem;">深色</strong>
                                    <small>更沉浸，适合夜间</small>
                                </span>
                            </span>
                            <i class="bi bi-check2 theme-option-check"></i>
                        </button>
                        <button type="button" class="theme-option" data-theme-option="light">
                            <span class="theme-option-main">
                                <span class="theme-option-icon"><i class="bi bi-sun-fill"></i></span>
                                <span>
                                    <strong class="d-block" style="font-size:0.9rem;">浅色</strong>
                                    <small>更通透，适合白天</small>
                                </span>
                            </span>
                            <i class="bi bi-check2 theme-option-check"></i>
                        </button>
                        <button type="button" class="theme-option" data-theme-option="system">
                            <span class="theme-option-main">
                                <span class="theme-option-icon"><i class="bi bi-display"></i></span>
                                <span>
                                    <strong class="d-block" style="font-size:0.9rem;">跟随系统</strong>
                                    <small>自动匹配设备偏好</small>
                                </span>
                            </span>
                            <i class="bi bi-check2 theme-option-check"></i>
                        </button>
                    </div>
                    <button type="button" class="theme-trigger" data-theme-trigger aria-expanded="false" aria-label="切换站点主题">
                        <span class="theme-trigger-main">
                            <span class="theme-trigger-icon"><i class="bi bi-moon-stars-fill" data-theme-icon></i></span>
                            <span data-theme-label>深色</span>
                        </span>
                        <i class="bi bi-chevron-down theme-trigger-caret"></i>
                    </button>
                </div>
            </div>

        </div>
    </nav>

    <!-- Toast 提示 -->
    <div id="toast" class="toast-mbti"></div>

    <!-- 主内容区 -->
    <main style="padding-top: 72px;">
