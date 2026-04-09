    </main>

    <!-- Footer -->
    <footer class="footer-mbti">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title mb-3">
                        <i class="bi bi-puzzle-fill me-2" style="color: var(--primary);"></i>Bugcool MindMap
                    </h5>

                    <p class="mb-0" style="font-size: 0.9rem; line-height: 1.8;">
                        基于荣格心理类型理论的性格测试系统，<br>
                        帮助你探索真实自我，发现无限可能。
                    </p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="footer-title mb-3">快速链接</h6>
                    <ul class="list-unstyled" style="font-size: 0.9rem;">
                        <li class="mb-2"><a href="index.php">首页</a></li>
                        <li class="mb-2"><a href="test.php">开始测试</a></li>
                        <li class="mb-2"><a href="query.php">证书查询</a></li>
                        <li class="mb-2"><a href="encyclopedia.php">MBTI 百科</a></li>
                        <li class="mb-2"><a href="about.php">关于本站</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h6 class="footer-title mb-3">MBTI 维度</h6>
                    <ul class="list-unstyled" style="font-size: 0.9rem;">
                        <li class="mb-2">E/I — 外向 / 内向</li>
                        <li class="mb-2">S/N — 感觉 / 直觉</li>
                        <li class="mb-2">T/F — 思维 / 情感</li>
                        <li class="mb-2">J/P — 判断 / 感知</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h6 class="footer-title mb-3">关于测试</h6>
                    <p style="font-size: 0.9rem; line-height: 1.8;">
                        MBTI（迈尔斯-布里格斯类型指标）是全球最流行的性格测试工具之一，已帮助数亿人了解自己的性格类型。
                    </p>
                </div>
            </div>
            <hr style="border-color: var(--border-2);">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0" style="font-size: 0.85rem; line-height: 1.75;">Copyright 
                        &copy; <?= date('Y') ?> <a href="https://github.com/TechLinguis/Bugcool-MindMap" target="_blank" rel="noopener noreferrer">Bugcool MindMap</a>. All rights reserved.<br>

                        <span style="color: var(--text-4);">项目作者：<a href="https://space.bilibili.com/3546643693570855" target="_blank" rel="noopener noreferrer">科技语者</a> · 辅助工具：<a href="https://copilot.tencent.com/work/" target="_blank" rel="noopener noreferrer">WorkBuddy</a></span>

                    </p>
                </div>

                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <p class="mb-0" style="font-size: 0.85rem;">
                        仅供娱乐参考，不作为专业心理评估依据
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS — 仅在需要 BS JS 特性的页面加载（defer 不阻塞渲染） -->
    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <?php if (!in_array($currentPage, ['index.php', 'encyclopedia.php', 'about.php'])): ?>
    <script src="assets/js/bootstrap.bundle.min.js" defer></script>
    <?php endif; ?>

    <!-- Vanilla JS navbar toggle（所有页面通用，比 BS JS 先就位） -->
    <script>(function(){var btn=document.querySelector('.navbar-toggler[data-bs-toggle="collapse"]');var tgt=btn&&document.querySelector(btn.getAttribute('data-bs-target'));if(btn&&tgt){btn.addEventListener('click',function(){tgt.classList.toggle('show');btn.setAttribute('aria-expanded',btn.getAttribute('aria-expanded')!=='true'})})})();</script>

    <script>
    (function() {
        'use strict';

        if (!window.__bugcoolProjectLogged) {
            window.__bugcoolProjectLogged = true;
            console.log('%cBugcool MindMap%c V1.0.0', 'padding:4px 10px;border-radius:999px;background:linear-gradient(135deg,#818cf8,#db2777);color:#fff;font-weight:700;', 'color:#94a3b8;font-weight:600;margin-left:6px;');
            console.log('GitHub: https://github.com/TechLinguis/Bugcool-MindMap');
        }

        const THEME_STORAGE_KEY = 'bugcool-theme-preference';
        const themeTrigger = document.querySelector('[data-theme-trigger]');
        const themePanel = document.querySelector('[data-theme-panel]');
        const themeLabel = document.querySelector('[data-theme-label]');
        const themeIcon = document.querySelector('[data-theme-icon]');
        const themeOptions = Array.from(document.querySelectorAll('[data-theme-option]'));
        const systemThemeMatcher = window.matchMedia('(prefers-color-scheme: dark)');
        const themeMeta = {
            dark: { label: '深色', icon: 'bi-moon-stars-fill' },
            light: { label: '浅色', icon: 'bi-sun-fill' },
            system: { label: '跟随系统', icon: 'bi-display' }
        };

        function getStoredTheme() {
            try {
                const storedTheme = localStorage.getItem(THEME_STORAGE_KEY);
                return ['dark', 'light', 'system'].includes(storedTheme) ? storedTheme : 'system';
            } catch (error) {
                return 'system';
            }
        }


        function closeThemePanel() {
            if (!themePanel || !themeTrigger) return;
            themePanel.classList.remove('is-open');
            themeTrigger.setAttribute('aria-expanded', 'false');
        }

        function applyTheme(mode, persist = true) {
            const resolvedTheme = mode === 'system'
                ? (systemThemeMatcher.matches ? 'dark' : 'light')
                : mode;

            document.documentElement.setAttribute('data-theme', resolvedTheme);
            document.documentElement.setAttribute('data-theme-mode', mode);
            document.documentElement.style.colorScheme = resolvedTheme;

            if (persist) {
                try {
                    localStorage.setItem(THEME_STORAGE_KEY, mode);
                } catch (error) {
                    // 忽略隐私模式或受限环境下的存储异常
                }
            }

            const meta = themeMeta[mode];
            if (themeLabel) themeLabel.textContent = meta.label;
            if (themeIcon) themeIcon.className = `bi ${meta.icon}`;

            themeOptions.forEach((option) => {
                const isActive = option.dataset.themeOption === mode;
                option.classList.toggle('active', isActive);
                option.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            window.dispatchEvent(new CustomEvent('bugcool:themechange', {
                detail: { mode, resolvedTheme }
            }));
        }


        applyTheme(getStoredTheme(), false);

        if (themeTrigger && themePanel) {
            themeTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                const willOpen = !themePanel.classList.contains('is-open');
                themePanel.classList.toggle('is-open', willOpen);
                themeTrigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });

            themePanel.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            document.addEventListener('click', closeThemePanel);
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeThemePanel();
                }
            });
        }

        themeOptions.forEach((option) => {
            option.addEventListener('click', function() {
                applyTheme(this.dataset.themeOption);
                closeThemePanel();
            });
        });

        const handleSystemThemeChange = () => {
            if (getStoredTheme() === 'system') {
                applyTheme('system', false);
            }
        };

        if (typeof systemThemeMatcher.addEventListener === 'function') {
            systemThemeMatcher.addEventListener('change', handleSystemThemeChange);
        } else if (typeof systemThemeMatcher.addListener === 'function') {
            systemThemeMatcher.addListener(handleSystemThemeChange);
        }

        requestAnimationFrame(() => document.documentElement.classList.add('theme-ready'));

        /* ===== 导航栏滚动效果 ===== */


        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-mbti');
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        /* ===== Toast 提示函数 ===== */
        window.showToast = function(message, type = 'info', duration = 3000) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast-mbti ' + type;
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, duration);
        };

        /* ===== 滚动动画系统 ===== */
        const observerOptions = { threshold: 0.08, rootMargin: '0px 0px -40px 0px' };
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    scrollObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        function initScrollAnimations() {
            document.querySelectorAll('.animate-on-scroll').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.7s cubic-bezier(0.4, 0, 0.2, 1)';
                el.style.transitionDelay = (i % 4) * 0.1 + 's';
                scrollObserver.observe(el);
            });

            if (!document.getElementById('scrollAnimStyle')) {
                const style = document.createElement('style');
                style.id = 'scrollAnimStyle';
                style.textContent = `
                    .animate-on-scroll.is-visible {
                        opacity: 1 !important;
                        transform: translateY(0) !important;
                    }
                `;
                document.head.appendChild(style);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initScrollAnimations);
        } else {
            initScrollAnimations();
        }

        /* ===== 鼠标跟踪光晕（仅桌面端）— 性能优化版 ===== */
        if (window.matchMedia('(pointer: fine)').matches && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const cursorGlow = document.createElement('div');
            cursorGlow.style.cssText = `
                position: fixed;
                width: 400px; height: 400px;
                border-radius: 50%;
                background: radial-gradient(circle, var(--primary-glow), transparent 70%);
                pointer-events: none;
                z-index: 0;
                transform: translate(-50%, -50%);
                opacity: 0;
                transition: opacity 0.3s ease;
                mix-blend-mode: var(--cursor-glow-blend);
                will-change: transform, opacity;

                contain: strict; layout style paint;
            `;
            document.body.appendChild(cursorGlow);

            let glowX = 0, glowY = 0, currentX = 0, currentY = 0;
            let rafId = null;

            document.addEventListener('mousemove', (e) => {
                glowX = e.clientX;
                glowY = e.clientY;
                cursorGlow.style.opacity = getComputedStyle(document.documentElement).getPropertyValue('--cursor-glow-opacity').trim() || '0.35';

            }, { passive: true });
            document.addEventListener('mouseleave', () => {
                cursorGlow.style.opacity = '0';
            }, { passive: true });

            function animateGlow() {
                // 使用 translate3d 强制 GPU 加速
                currentX += (glowX - currentX) * 0.08;
                currentY += (glowY - currentY) * 0.08;
                cursorGlow.style.transform = `translate3d(${currentX - 200}px, ${currentY - 200}px, 0)`;
                rafId = requestAnimationFrame(animateGlow);
            }
            rafId = requestAnimationFrame(animateGlow);

            // 页面不可见时暂停动画
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                } else if (!rafId) {
                    rafId = requestAnimationFrame(animateGlow);
                }
            });
        }

        /* ===== 平滑滚动 ===== */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

    })();
    </script>
</body>
</html>
