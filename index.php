<?php $pageTitle = '首页'; include_once __DIR__ . '/includes/header.php'; ?>

<style>
    /* ===== 首页专属动画 ===== */
    @keyframes heroGlow {
        0%, 100% { transform: scale(1); opacity: 0.35; }
        50% { transform: scale(1.15); opacity: 0.6; }
    }
    @keyframes particleDrift {
        0%, 100% { transform: translateY(0) translateX(0); }
        25% { transform: translateY(-15px) translateX(8px); }
        50% { transform: translateY(-5px) translateX(-4px); }
        75% { transform: translateY(-25px) translateX(12px); }
    }
    @keyframes gridLineFloat {
        0%, 100% { opacity: 0.03; }
        50% { opacity: 0.06; }
    }
    @keyframes typeGlow {
        0%, 100% { box-shadow: 0 0 0 rgba(129,140,248,0); }
        50% { box-shadow: 0 0 20px rgba(129,140,248,0.15); }
    }
    /* ===== 统计卡片数字动画 ===== */
    @keyframes statIconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .stat-card {
        transition: all 0.35s cubic-bezier(.4,0,.2,1);
    }
    .stat-card:hover {
        transform: translateY(-6px);
        border-color: var(--border-3) !important;
        box-shadow: 0 12px 40px rgba(129,140,248,0.12);
    }
    .stat-icon-box {
        transition: transform 0.3s ease;
    }
    .stat-card:hover .stat-icon-box {
        animation: statIconPulse 0.6s ease;
    }
    /* ===== 排行榜进度条动画 ===== */
    .rank-bar-fill {
        transition: width 1.2s cubic-bezier(.25,.46,.45,.94);
    }
    .rank-row {
        transition: all 0.3s cubic-bezier(.4,0,.2,1);
    }
    .rank-row:hover {
        transform: translateY(-3px);
        border-color: var(--border-3) !important;
        background: var(--bg-card) !important;
    }
    /* ===== 计数动画淡入 ===== */
    .stat-number {
        font-variant-numeric: tabular-nums;
    }

    /* ===== 浅色主题补齐 ===== */
    .home-process-grid {
        background-image: radial-gradient(circle at 1px 1px, var(--grid-dot) 1px, transparent 0) !important;
    }
    .step-core-primary {
        background: var(--surface-step-core-primary) !important;
    }
    .step-core-emerald {
        background: var(--surface-step-core-emerald) !important;
    }
    .step-core-rose {
        background: var(--surface-step-core-rose) !important;
    }
    :root[data-theme="light"] .step-card.step-card-primary {
        background: var(--surface-step-primary) !important;
    }
    :root[data-theme="light"] .step-card.step-card-emerald {
        background: var(--surface-step-emerald) !important;
    }
    :root[data-theme="light"] .step-card.step-card-rose {
        background: var(--surface-step-rose) !important;
    }
    .home-spotlight-panel {
        background: var(--surface-spotlight) !important;
        border-color: var(--border-2) !important;
    }
    .home-cta-section {
        background: var(--surface-cta) !important;
    }
    :root[data-theme="light"] .step-card {
        box-shadow: 0 18px 42px rgba(109,106,248,0.08);
    }
    :root[data-theme="light"] .step-card:hover {
        box-shadow: 0 24px 56px rgba(109,106,248,0.14) !important;
    }
    :root[data-theme="light"] .home-spotlight-panel {
        box-shadow: 0 22px 56px rgba(109,106,248,0.12);
    }
</style>


<!-- ==================== Hero 区域 ==================== -->
<section class="hero-home" style="
    min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    background: var(--surface-hero);
">

    <!-- 动态渐变光球 -->
    <div class="hero-gradient-orb animate-morph" style="top:5%;left:-5%;width:500px;height:500px;background:rgba(99,102,241,0.25);animation:heroGlow 8s ease-in-out infinite, morphBlob 10s ease-in-out infinite;"></div>
    <div class="hero-gradient-orb animate-morph" style="bottom:-10%;right:-8%;width:600px;height:600px;background:rgba(244,114,182,0.18);animation:heroGlow 10s ease-in-out infinite -3s, morphBlob 12s ease-in-out infinite -4s;"></div>
    <div class="hero-gradient-orb animate-morph" style="top:40%;left:50%;width:350px;height:350px;background:rgba(34,211,238,0.12);animation:heroGlow 9s ease-in-out infinite -5s, morphBlob 11s ease-in-out infinite -2s;"></div>

    <!-- 动态粒子装饰 -->
    <div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
        <div style="position:absolute;top:8%;left:5%;width:100px;height:100px;background:rgba(129,140,248,0.06);border-radius:50%;filter:blur(2px);animation:particleDrift 6s ease-in-out infinite;"></div>
        <div style="position:absolute;top:55%;right:8%;width:140px;height:140px;background:rgba(244,114,182,0.05);border-radius:50%;animation:particleDrift 8s ease-in-out infinite -2s;filter:blur(3px);"></div>
        <div style="position:absolute;bottom:10%;left:20%;width:70px;height:70px;background:rgba(34,211,238,0.07);border-radius:50%;animation:particleDrift 7s ease-in-out infinite -4s;"></div>
        <div style="position:absolute;top:25%;right:25%;width:50px;height:50px;background:rgba(251,191,36,0.06);border-radius:20px;animation:particleDrift 9s ease-in-out infinite -1s;transform:rotate(45deg);"></div>
        <div style="position:absolute;top:70%;left:45%;width:90px;height:90px;background:rgba(52,211,153,0.04);border-radius:50%;animation:particleDrift 10s ease-in-out infinite -6s;filter:blur(4px);"></div>
        <!-- 微粒 -->
        <div style="position:absolute;top:15%;left:35%;width:4px;height:4px;border-radius:50%;background:var(--primary);animation:particleDrift 5s ease-in-out infinite -1s;"></div>
        <div style="position:absolute;top:45%;right:15%;width:3px;height:3px;border-radius:50%;background:var(--rose);animation:particleDrift 7s ease-in-out infinite -3s;"></div>
        <div style="position:absolute;bottom:25%;left:12%;width:5px;height:5px;border-radius:50%;background:var(--cyan);animation:particleDrift 6s ease-in-out infinite -5s;"></div>
        <div style="position:absolute;top:80%;right:30%;width:3px;height:3px;border-radius:50%;background:var(--amber);animation:particleDrift 8s ease-in-out infinite -2s;"></div>
        <!-- 几何线条 -->
        <svg style="position:absolute;top:0;left:0;width:100%;height:100%;animation:gridLineFloat 6s ease-in-out infinite;" viewBox="0 0 1440 900">
            <circle cx="200" cy="150" r="80" fill="none" stroke="rgba(129,140,248,0.06)" stroke-width="1"/>
            <circle cx="1200" cy="700" r="120" fill="none" stroke="rgba(244,114,182,0.04)" stroke-width="1"/>
            <circle cx="700" cy="400" r="200" fill="none" stroke="rgba(34,211,238,0.03)" stroke-width="0.5"/>
            <line x1="100" y1="0" x2="500" y2="900" stroke="rgba(129,140,248,0.03)" stroke-width="0.5"/>
            <line x1="900" y1="0" x2="1400" y2="900" stroke="rgba(244,114,182,0.02)" stroke-width="0.5"/>
        </svg>
    </div>

    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 animate-fadeInUp">
                <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4 glass" style="font-size:0.88rem;letter-spacing:0.3px;">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--emerald);box-shadow:0 0 8px var(--emerald);"></span>
                    <span style="color:var(--text-2);">免费测评 · 无需注册 · 10 分钟搞定</span>
                </div>
                <h1 class="fw-bold mb-4" style="font-size:clamp(2.2rem, 5.5vw, 3.5rem);line-height:1.15;letter-spacing:-0.5px;color:var(--text-1);">
                    10 分钟，<br>
                    <span style="opacity:0.75;font-weight:400;">认识真实的 <span class="gradient-text">你自己</span></span>
                </h1>
                <p class="mb-4" style="font-size:1.1rem;color:var(--text-2);max-width:460px;line-height:1.9;font-weight:300;">
                    80 道标准题 · 荣格理论支撑 · 即时生成专属证书<br>
                    完全免费，无需注册，随时可查。
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="test.php" class="btn btn-lg px-5 py-3 fw-semibold" style="background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;border-radius:50px;box-shadow:0 8px 30px var(--primary-glow);transition:all 0.3s;letter-spacing:0.3px;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(129,140,248,0.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 8px 30px var(--primary-glow)'">
                        <i class="bi bi-lightning-charge-fill me-2"></i>立即测试
                    </a>
                    <a href="query.php" class="btn btn-lg px-5 py-3 fw-semibold" style="background:var(--primary-surface);color:var(--text-2);border:1px solid var(--border-2);border-radius:50px;backdrop-filter:blur(8px);transition:all 0.3s;" onmouseover="this.style.background='var(--primary-glass)';this.style.borderColor='var(--border-3)';this.style.color='var(--text-1)';this.style.transform='translateY(-4px)'" onmouseout="this.style.background='var(--primary-surface)';this.style.borderColor='var(--border-2)';this.style.color='var(--text-2)';this.style.transform=''">
                        <i class="bi bi-search me-2"></i>查询证书
                    </a>
                </div>
                <!-- 统计数据 -->
                <div class="d-flex gap-3 flex-wrap" style="font-size:0.88rem;">
                    <div class="d-flex align-items-center gap-2 px-3 py-2 glass" style="border-radius:var(--radius-sm);">
                        <i class="bi bi-clock-history" style="color:var(--amber);"></i>
                        <span style="color:var(--text-2);">约 10 分钟</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 glass" style="border-radius:var(--radius-sm);">
                        <i class="bi bi-shield-lock-fill" style="color:var(--emerald);"></i>
                        <span style="color:var(--text-2);">免费安全</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 glass" style="border-radius:var(--radius-sm);">
                        <i class="bi bi-patch-check-fill" style="color:var(--primary);"></i>
                        <span style="color:var(--text-2);">专属证书</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center animate-fadeIn" style="animation-delay:0.3s;">
                <div class="position-relative d-inline-block">
                    <!-- 16型MBTI展示 -->
                    <div class="p-4 p-lg-5 glass" style="border-radius:var(--radius-xl);">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                            <div style="width:28px;height:3px;border-radius:2px;background:var(--border-3);"></div>
                            <h5 class="fw-bold mb-0" style="font-size:1rem;letter-spacing:1px;text-transform:uppercase;color:var(--text-2);">16 Personalities</h5>
                            <div style="width:28px;height:3px;border-radius:2px;background:var(--border-3);"></div>
                        </div>
                        <div class="row g-2" id="mbtiTypeGrid"></div>
                    </div>
                    <!-- 装饰浮动卡片 -->
                    <div class="animate-float d-none d-lg-block glass" style="position:absolute;top:-15px;right:-20px;padding:10px 16px;border-radius:var(--radius);animation-delay:1s;">
                        <span style="font-size:1.5rem;">🧠</span>
                        <div class="fw-bold" style="font-size:0.75rem;color:var(--text-1);">INFJ</div>
                        <div style="font-size:0.6rem;color:var(--text-3);">提倡者</div>
                    </div>
                    <div class="animate-float d-none d-lg-block glass" style="position:absolute;bottom:-10px;left:-15px;padding:10px 16px;border-radius:var(--radius);animation-delay:2s;">
                        <span style="font-size:1.5rem;">⚔️</span>
                        <div class="fw-bold" style="font-size:0.75rem;color:var(--text-1);">ENTJ</div>
                        <div style="font-size:0.6rem;color:var(--text-3);">指挥官</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- ==================== 介绍区域 ==================== -->
<section class="py-5 py-lg-6" style="background:var(--bg-1);">
    <div class="container py-4">
        <div class="text-center mb-5 animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background:var(--primary-glass);border:1px solid var(--border-1);">
                <i class="bi bi-lightbulb" style="color:var(--primary);"></i>
                <span style="color:var(--primary);font-size:0.85rem;font-weight:600;">关于 MBTI</span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.4rem);">4 个维度，16 种人格，一个真实的你</h2>
            <p class="section-subtitle mx-auto" style="max-width:560px;font-weight:300;">
                MBTI 把人的性格归纳为四个维度，每个维度两极，组合出 16 种独特人格
            </p>
        </div>

        <div class="row g-4">
            <!-- E/I -->
            <div class="col-md-6 col-lg-3 animate-on-scroll">
                <div class="card-mbti p-4 h-100 text-center position-relative overflow-hidden">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary-glow),transparent);"></div>
                    <div class="mb-3 position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,var(--primary-dim),var(--primary));box-shadow:0 8px 24px var(--primary-glow);">
                            <i class="bi bi-chat-dots-fill text-white" style="font-size:1.6rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:var(--text-1);">E / I</h5>
                    <p class="mb-2" style="color:var(--primary);font-size:0.82rem;font-weight:600;letter-spacing:0.5px;">能量方向</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--primary-glass);color:var(--primary);font-size:0.78rem;font-weight:500;border:1px solid var(--border-1);">E 外向</span>
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--rose-glow);color:var(--rose);font-size:0.78rem;font-weight:500;border:1px solid rgba(244,114,182,0.1);">I 内向</span>
                    </div>
                    <p class="mb-0" style="font-size:0.84rem;color:var(--text-2);line-height:1.7;">你从哪里获取能量？是与他人互动还是独处思考？</p>
                </div>
            </div>
            <!-- S/N -->
            <div class="col-md-6 col-lg-3 animate-on-scroll">
                <div class="card-mbti p-4 h-100 text-center position-relative overflow-hidden">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--cyan-glow),transparent);"></div>
                    <div class="mb-3 position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#06B6D4,var(--cyan));box-shadow:0 8px 24px var(--cyan-glow);">
                            <i class="bi bi-eye-fill text-white" style="font-size:1.6rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:var(--text-1);">S / N</h5>
                    <p class="mb-2" style="color:var(--cyan);font-size:0.82rem;font-weight:600;letter-spacing:0.5px;">信息获取</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--cyan-glow);color:var(--cyan);font-size:0.78rem;font-weight:500;border:1px solid rgba(34,211,238,0.1);">S 感觉</span>
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--primary-glass);color:var(--primary);font-size:0.78rem;font-weight:500;border:1px solid var(--border-1);">N 直觉</span>
                    </div>
                    <p class="mb-0" style="font-size:0.84rem;color:var(--text-2);line-height:1.7;">你更关注具体事实和细节，还是整体模式和可能性？</p>
                </div>
            </div>
            <!-- T/F -->
            <div class="col-md-6 col-lg-3 animate-on-scroll">
                <div class="card-mbti p-4 h-100 text-center position-relative overflow-hidden">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--rose-glow),transparent);"></div>
                    <div class="mb-3 position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#EC4899,var(--rose));box-shadow:0 8px 24px var(--rose-glow);">
                            <i class="bi bi-heart-pulse-fill text-white" style="font-size:1.6rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:var(--text-1);">T / F</h5>
                    <p class="mb-2" style="color:var(--rose);font-size:0.82rem;font-weight:600;letter-spacing:0.5px;">决策方式</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--rose-glow);color:var(--rose);font-size:0.78rem;font-weight:500;border:1px solid rgba(244,114,182,0.1);">T 思维</span>
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--cyan-glow);color:var(--cyan);font-size:0.78rem;font-weight:500;border:1px solid rgba(34,211,238,0.1);">F 情感</span>
                    </div>
                    <p class="mb-0" style="font-size:0.84rem;color:var(--text-2);line-height:1.7;">做决定时你更依赖逻辑分析，还是价值观和感受？</p>
                </div>
            </div>
            <!-- J/P -->
            <div class="col-md-6 col-lg-3 animate-on-scroll">
                <div class="card-mbti p-4 h-100 text-center position-relative overflow-hidden">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--amber-glow),transparent);"></div>
                    <div class="mb-3 position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#F59E0B,var(--amber));box-shadow:0 8px 24px var(--amber-glow);">
                            <i class="bi bi-compass-fill text-white" style="font-size:1.6rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:var(--text-1);">J / P</h5>
                    <p class="mb-2" style="color:var(--amber);font-size:0.82rem;font-weight:600;letter-spacing:0.5px;">生活方式</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--amber-glow);color:var(--amber);font-size:0.78rem;font-weight:500;border:1px solid rgba(251,191,36,0.1);">J 判断</span>
                        <span class="badge rounded-pill px-3 py-1.5" style="background:var(--primary-glass);color:var(--primary);font-size:0.78rem;font-weight:500;border:1px solid var(--border-1);">P 感知</span>
                    </div>
                    <p class="mb-0" style="font-size:0.84rem;color:var(--text-2);line-height:1.7;">你喜欢有计划有秩序，还是灵活随性、随机应变？</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 实时统计区域 ==================== -->
<section class="py-5 py-lg-6" style="background:var(--bg-2);">
    <div class="container py-4">
        <div class="text-center mb-5 animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background:var(--primary-glass);border:1px solid var(--border-1);">
                <i class="bi bi-bar-chart-line-fill" style="color:var(--primary);"></i>
                <span style="color:var(--primary);font-size:0.85rem;font-weight:600;">实时数据</span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.4rem);">你将获得什么</h2>
            <p class="section-subtitle mx-auto" style="max-width:480px;font-weight:300;">
                做完测试，这些都会属于你
            </p>
        </div>

        <div class="row g-3 g-lg-4 justify-content-center">
            <!-- 已测试总人数 -->
            <div class="col-6 col-lg-3 animate-on-scroll">
                <div class="stat-card card-mbti p-4 p-lg-5 text-center h-100 position-relative overflow-hidden">
                    <div style="position:absolute;top:-15px;right:-15px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,var(--primary-glow),transparent);"></div>
                    <div class="stat-icon-box d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,var(--primary-dim),var(--primary));box-shadow:0 8px 24px var(--primary-glow);">
                        <i class="bi bi-people-fill text-white" style="font-size:1.4rem;"></i>
                    </div>
                    <div class="stat-number fw-bold mb-1" style="font-size:clamp(1.8rem,3.5vw,2.6rem);color:var(--text-1);line-height:1;">完整</div>
                    <div style="font-size:0.88rem;color:var(--text-2);font-weight:500;">性格分析报告</div>
                </div>
            </div>
            <!-- 测试题目数 -->
            <div class="col-6 col-lg-3 animate-on-scroll">
                <div class="stat-card card-mbti p-4 p-lg-5 text-center h-100 position-relative overflow-hidden">
                    <div style="position:absolute;top:-15px;right:-15px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,var(--cyan-glow),transparent);"></div>
                    <div class="stat-icon-box d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#06B6D4,var(--cyan));box-shadow:0 8px 24px var(--cyan-glow);">
                        <i class="bi bi-list-task text-white" style="font-size:1.4rem;"></i>
                    </div>
                    <div class="stat-number fw-bold mb-1" style="font-size:clamp(1.8rem,3.5vw,2.6rem);color:var(--text-1);line-height:1;">80 题</div>
                    <div style="font-size:0.88rem;color:var(--text-2);font-weight:500;">标准版测评题目</div>
                </div>
            </div>
            <!-- MBTI类型数 -->
            <div class="col-6 col-lg-3 animate-on-scroll">
                <div class="stat-card card-mbti p-4 p-lg-5 text-center h-100 position-relative overflow-hidden">
                    <div style="position:absolute;top:-15px;right:-15px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,var(--rose-glow),transparent);"></div>
                    <div class="stat-icon-box d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#EC4899,var(--rose));box-shadow:0 8px 24px var(--rose-glow);">
                        <i class="bi bi-grid-3x3-gap-fill text-white" style="font-size:1.4rem;"></i>
                    </div>
                    <div class="stat-number fw-bold mb-1" style="font-size:clamp(1.8rem,3.5vw,2.6rem);color:var(--text-1);line-height:1;">16 型</div>
                    <div style="font-size:0.88rem;color:var(--text-2);font-weight:500;">人格完整解读</div>
                </div>
            </div>
            <!-- 今日测试人数 -->
            <div class="col-6 col-lg-3 animate-on-scroll">
                <div class="stat-card card-mbti p-4 p-lg-5 text-center h-100 position-relative overflow-hidden">
                    <div style="position:absolute;top:-15px;right:-15px;width:60px;height:60px;border-radius:50%;background:radial-gradient(circle,var(--amber-glow),transparent);"></div>
                    <div class="stat-icon-box d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#F59E0B,var(--amber));box-shadow:0 8px 24px var(--amber-glow);">
                        <i class="bi bi-lightning-charge-fill text-white" style="font-size:1.4rem;"></i>
                    </div>
                    <div class="stat-number fw-bold mb-1" style="font-size:clamp(1.8rem,3.5vw,2.6rem);color:var(--text-1);line-height:1;">专属</div>
                    <div style="font-size:0.88rem;color:var(--text-2);font-weight:500;">电子证书 · 可下载</div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- ==================== 类型热度排行榜 ==================== -->
<section class="py-5 py-lg-6" style="background:var(--bg-1);">
    <div class="container py-4">
        <div class="text-center mb-5 animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background:var(--rose-glow);border:1px solid rgba(244,114,182,0.1);">
                <i class="bi bi-bar-chart-line-fill" style="color:var(--rose);"></i>
                <span style="color:var(--rose);font-size:0.85rem;font-weight:600;">真实数据</span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.4rem);">来看看大家都是什么性格</h2>
            <p class="section-subtitle mx-auto" style="max-width:480px;font-weight:300;">
                已有 <span id="totalCountLabel" style="color:var(--primary);">--</span> 人完成测评，你不是一个人
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 animate-on-scroll">
                <div class="glass p-3 p-lg-4" style="border-radius:var(--radius-xl);" id="rankingContainer">
                    <!-- 排行行通过JS渲染 -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 测试流程（精致重设计） ==================== -->
<section class="py-5 py-lg-6" style="background:var(--bg-2);position:relative;overflow:hidden;">
    <!-- 背景装饰 -->
    <div style="position:absolute;top:-100px;left:50%;transform:translateX(-50%);width:700px;height:400px;background:radial-gradient(ellipse,var(--primary-glow),transparent 70%);pointer-events:none;opacity:0.4;"></div>
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 1px 1px,rgba(129,140,248,0.03) 1px,transparent 0);background-size:28px 28px;pointer-events:none;"></div>

    <div class="container py-4 position-relative" style="z-index:1;">
        <div class="text-center mb-5 animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background:var(--emerald-glow);border:1px solid rgba(52,211,153,0.1);">
                <i class="bi bi-list-check" style="color:var(--emerald);"></i>
                <span style="color:var(--emerald);font-size:0.85rem;font-weight:600;">测试流程</span>
            </div>
            <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.4rem);">三步，拿到你的专属报告</h2>
            <p style="color:var(--text-3);font-size:0.95rem;margin-top:10px;font-weight:300;">没有门槛，不花一分钱，几分钟就能看到结果</p>
        </div>

        <!-- 步骤容器 -->
        <div class="steps-container animate-on-scroll" style="max-width:960px;margin:0 auto;">
            <!-- 连接线背景（桌面端） -->
            <div class="d-none d-md-block" style="position:absolute;top:92px;left:80px;right:80px;height:3px;z-index:0;">
                <div style="width:100%;height:100%;background:linear-gradient(90deg,var(--primary),var(--emerald),var(--rose));border-radius:2px;opacity:0.25;"></div>
                <!-- 流动动画 -->
                <div style="position:absolute;top:-2px;left:0;width:40%;height:7px;background:linear-gradient(90deg,transparent,var(--primary),transparent);border-radius:4px;animation:flowLine 3s ease-in-out infinite;"></div>
            </div>

            <div class="row g-4 g-lg-5 justify-content-center position-relative" style="z-index:1;">
                <!-- 步骤 1 -->
                <div class="col-md-4 step-card-wrap" data-step="1">
                    <div class="step-card step-card-primary card-mbti p-4 text-center h-100 position-relative"
                         style="border-radius:20px;border:1px solid rgba(129,140,248,0.12);background:var(--surface-step-primary);transition:all 0.4s cubic-bezier(.16,1,.3,1);cursor:pointer;"
                         onmouseover="this.style.transform='translateY(-8px) scale(1.02)';this.style.borderColor='rgba(129,140,248,0.35)';this.style.boxShadow='0 20px 50px rgba(129,140,248,0.2),0 0 0 1px rgba(129,140,248,0.1)'"
                         onmouseout="this.style.transform='';this.style.borderColor='rgba(129,140,248,0.12)';this.style.boxShadow=''">
                        <!-- 装饰性光晕 -->
                        <div style="position:absolute;top:-30px;left:50%;transform:translateX(-50%);width:100px;height:60px;background:radial-gradient(ellipse,var(--primary-glow),transparent);opacity:0.5;pointer-events:none;"></div>
                        <!-- 数字徽章 -->
                        <div style="margin:0 auto 18px;position:relative;width:76px;height:76px;">
                            <!-- 外圈旋转 -->
                            <div style="position:absolute;inset:-5px;border-radius:50%;border:2px dashed rgba(129,140,248,0.25);animation:spin-slow 20s linear infinite;"></div>
                            <!-- 渐变环 -->
                            <div style="position:absolute;inset:0;border-radius:50%;padding:2px;background:conic-gradient(from 180deg,var(--primary),rgba(99,102,241,0.2),transparent,var(--primary-dim));-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;"></div>
                            <!-- 实体圆 -->
                            <div class="step-core-primary" style="position:absolute;inset:3px;border-radius:50%;background:linear-gradient(145deg,#1e1b4b,#12122a);display:flex;align-items:center;justify-content:center;">

                                <span style="font-size:1.7rem;font-weight:900;color:var(--primary);letter-spacing:-1px;">1</span>
                            </div>
                        </div>
                        <!-- 图标 -->
                        <div class="mb-3">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,var(--primary-dim),rgba(99,102,241,0.3));">
                                <i class="bi bi-pencil-square" style="font-size:1.25rem;color:var(--primary);"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.15rem;color:var(--text-1);letter-spacing:0.3px;">开始测试</h5>
                        <p style="color:var(--text-3);font-size:0.88rem;line-height:1.75;">回答 <strong style="color:var(--primary);">80 道</strong>精选测试题<br>大约需要 <strong style="color:var(--primary);">10 分钟</strong>完成</p>
                        <!-- 底部标签 -->
                        <div style="margin-top:14px;display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;background:rgba(129,140,248,0.08);font-size:0.72rem;color:var(--primary);">
                            <i class="bi bi-clock" style="font-size:0.65rem;"></i> 快速评估
                        </div>
                    </div>
                </div>

                <!-- 步骤 2 -->
                <div class="col-md-4 step-card-wrap" data-step="2">
                    <div class="step-card step-card-emerald card-mbti p-4 text-center h-100 position-relative"
                         style="border-radius:20px;border:1px solid rgba(52,211,153,0.12);background:var(--surface-step-emerald);transition:all 0.4s cubic-bezier(.16,1,.3,1);cursor:pointer;"
                         onmouseover="this.style.transform='translateY(-8px) scale(1.02)';this.style.borderColor='rgba(52,211,153,0.35)';this.style.boxShadow='0 20px 50px rgba(52,211,153,0.15),0 0 0 1px rgba(52,211,153,0.08)'"
                         onmouseout="this.style.transform='';this.style.borderColor='rgba(52,211,153,0.12)';this.style.boxShadow=''">
                        <div style="position:absolute;top:-30px;left:50%;transform:translateX(-50%);width:100px;height:60px;background:radial-gradient(ellipse,var(--emerald-glow),transparent);opacity:0.5;pointer-events:none;"></div>
                        <div style="margin:0 auto 18px;position:relative;width:76px;height:76px;">
                            <div style="position:absolute;inset:-5px;border-radius:50%;border:2px dashed rgba(52,211,153,0.25);animation:spin-slow 20s linear infinite reverse;"></div>
                            <div style="position:absolute;inset:0;border-radius:50%;padding:2px;background:conic-gradient(from 180deg,var(--emerald),rgba(16,185,129,0.2),transparent,#059669);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;"></div>
                            <div class="step-core-emerald" style="position:absolute;inset:3px;border-radius:50%;background:linear-gradient(145deg,#064E3B,#111a18);display:flex;align-items:center;justify-content:center;">

                                <span style="font-size:1.7rem;font-weight:900;color:var(--emerald);letter-spacing:-1px;">2</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#05966933,rgba(16,185,129,0.25));">
                                <i class="bi bi-bar-chart-line" style="font-size:1.25rem;color:var(--emerald);"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.15rem;color:var(--text-1);letter-spacing:0.3px;">查看结果</h5>
                        <p style="color:var(--text-3);font-size:0.88rem;line-height:1.75;">即时获得 <strong style="color:var(--emerald);">MBTI 类型</strong>分析<br>了解你的<strong style="color:var(--emerald);">性格特征和优势</strong></p>
                        <div style="margin-top:14px;display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;background:rgba(52,211,153,0.08);font-size:0.72rem;color:var(--emerald);">
                            <i class="bi bi-bolt" style="font-size:0.65rem;"></i> 即时出结果
                        </div>
                    </div>
                </div>

                <!-- 步骤 3 -->
                <div class="col-md-4 step-card-wrap" data-step="3">
                    <div class="step-card step-card-rose card-mbti p-4 text-center h-100 position-relative"
                         style="border-radius:20px;border:1px solid rgba(244,114,182,0.12);background:var(--surface-step-rose);transition:all 0.4s cubic-bezier(.16,1,.3,1);cursor:pointer;"
                         onmouseover="this.style.transform='translateY(-8px) scale(1.02)';this.style.borderColor='rgba(244,114,182,0.35)';this.style.boxShadow='0 20px 50px rgba(244,114,182,0.15),0 0 0 1px rgba(244,114,182,0.08)'"
                         onmouseout="this.style.transform='';this.style.borderColor='rgba(244,114,182,0.12)';this.style.boxShadow=''">
                        <div style="position:absolute;top:-30px;left:50%;transform:translateX(-50%);width:100px;height:60px;background:radial-gradient(ellipse,var(--rose-glow),transparent);opacity:0.5;pointer-events:none;"></div>
                        <div style="margin:0 auto 18px;position:relative;width:76px;height:76px;">
                            <div style="position:absolute;inset:-5px;border-radius:50%;border:2px dashed rgba(244,114,182,0.25);animation:spin-slow 20s linear infinite;"></div>
                            <div style="position:absolute;inset:0;border-radius:50%;padding:2px;background:conic-gradient(from 180deg,var(--rose),rgba(219,39,119,0.2),transparent,#DB2777);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;"></div>
                            <div class="step-core-rose" style="position:absolute;inset:3px;border-radius:50%;background:linear-gradient(145deg,#500724,#16101d);display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:1.7rem;font-weight:900;color:var(--rose);letter-spacing:-1px;">3</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#DB277733,rgba(244,114,182,0.25));">
                                <i class="bi bi-award" style="font-size:1.25rem;color:var(--rose);"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.15rem;color:var(--text-1);letter-spacing:0.3px;">获取证书</h5>
                        <p style="color:var(--text-3);font-size:0.88rem;line-height:1.75;">自动生成<strong style="color:var(--rose);">专属证书</strong>和编号<br><strong style="color:var(--rose);">支持下载</strong>和社交分享</p>
                        <div style="margin-top:14px;display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;background:rgba(244,114,182,0.08);font-size:0.72rem;color:var(--rose);">
                            <i class="bi bi-patch-check" style="font-size:0.65rem;"></i> 永久保存
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 pt-2 animate-on-scroll">
            <a href="test.php" class="btn btn-primary-mbti btn-lg px-5" id="startTestBtn"
               style="border-radius:14px;padding:14px 36px;font-size:1rem;font-weight:700;letter-spacing:0.5px;box-shadow:0 8px 32px var(--primary-glow),0 0 0 1px rgba(129,140,248,0.2);transition:all 0.4s cubic-bezier(.16,1,.3,1);position:relative;overflow:hidden;"
               onmouseover="this.style.transform='translateY(-3px) scale(1.03)';this.style.boxShadow='0 16px 48px rgba(129,140,248,0.45),0 0 0 1px rgba(129,140,248,0.3)'"
               onmouseout="this.style.transform='';this.style.boxShadow='0 8px 32px var(--primary-glow),0 0 0 1px rgba(129,140,248,0.2)'">
                <!-- 按钮内部光效 -->
                <div style="position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.15),transparent);animation:btnShine 3s ease-in-out infinite;"></div>
                <i class="bi bi-lightning-charge-fill me-2"></i>立即开始测试
            </a>
            <p style="color:var(--text-4);font-size:0.78rem;margin-top:14px;"><i class="bi bi-shield-check me-1"></i>完全免费 · 无需注册 · 即刻获取结果</p>
        </div>
    </div>
</section>

<style>
/* 步骤区域的专属动画 */
@keyframes flowLine {
    0%   { left: -40%; opacity: 0; }
    50%  { opacity: 1; }
    100% { left: 100%; opacity: 0; }
}
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
@keyframes btnShine {
    0%   { left: -60%; }
    50%  { left: 120%; }
    100% { left: 120%; }
}

/* 步骤卡片入场动画 */
.step-card-wrap {
    animation: stepFadeUp 0.6s ease-out both;
}
.step-card-wrap:nth-child(1) { animation-delay: 0.1s; }
.step-card-wrap:nth-child(2) { animation-delay: 0.25s; }
.step-card-wrap:nth-child(3) { animation-delay: 0.4s; }

@keyframes stepFadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* 移动端优化 */
@media (max-width: 767px) {
    .step-card { padding: 24px 18px !important; }
    .step-card > div[style*="width:76px"] { width:64px !important; height:64px !important; }
    .step-card > div[style*="width:76px"] span { font-size:1.4rem !important; }
}
</style>

<!-- ==================== 权威背书区块 ==================== -->
<section class="py-5 py-lg-6" style="background:var(--bg-1);position:relative;overflow:hidden;">
    <!-- 背景装饰 -->
    <div style="position:absolute;bottom:-80px;right:-80px;width:400px;height:400px;background:radial-gradient(circle,var(--rose-glow),transparent);pointer-events:none;opacity:0.3;"></div>
    <div style="position:absolute;top:-60px;left:-60px;width:300px;height:300px;background:radial-gradient(circle,var(--primary-glow),transparent);pointer-events:none;opacity:0.2;"></div>

    <div class="container py-2 position-relative" style="z-index:1;">
        <!-- 核心卖点数字条 -->
        <div class="animate-on-scroll mb-5 pb-3">
            <div class="row g-3 g-md-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 p-lg-4 glass" style="border-radius:var(--radius-lg);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--border-2)'" onmouseout="this.style.transform='';this.style.borderColor=''">
                        <div style="font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--primary),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;">80题</div>
                        <div style="font-size:0.8rem;color:var(--text-3);margin-top:4px;">标准完整测评</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 p-lg-4 glass" style="border-radius:var(--radius-lg);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--border-2)'" onmouseout="this.style.transform='';this.style.borderColor=''">
                        <div style="font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--cyan),var(--emerald));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;">4维度</div>
                        <div style="font-size:0.8rem;color:var(--text-3);margin-top:4px;">人格核心维度</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 p-lg-4 glass" style="border-radius:var(--radius-lg);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--border-2)'" onmouseout="this.style.transform='';this.style.borderColor=''">
                        <div style="font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--amber),#FB923C);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;">10分钟</div>
                        <div style="font-size:0.8rem;color:var(--text-3);margin-top:4px;">快速完成测评</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 p-lg-4 glass" style="border-radius:var(--radius-lg);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='var(--border-2)'" onmouseout="this.style.transform='';this.style.borderColor=''">
                        <div style="font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--rose),var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;">即出结果</div>
                        <div style="font-size:0.8rem;color:var(--text-3);margin-top:4px;">报告+电子证书</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 权威说明横幅 -->
        <div class="animate-on-scroll">
            <div class="glass p-4 p-lg-5 home-spotlight-panel" style="border-radius:var(--radius-xl);background:var(--surface-spotlight);border-color:rgba(129,140,248,0.12);">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,var(--primary-dim),var(--primary));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-shield-check-fill text-white" style="font-size:1.3rem;"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1" style="color:var(--text-1);font-size:1.15rem;">科学理论支撑，70+年实践验证</h4>
                                <p style="color:var(--text-3);font-size:0.88rem;line-height:1.75;margin:0;">MBTI 基于荣格心理类型理论，由 Briggs Myers 母女发展而来，是全球企业和学术机构最广泛使用的人格评估工具之一，广泛应用于职业规划、团队建设与个人成长领域。</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--text-2);">
                                <i class="bi bi-check-circle-fill" style="color:var(--emerald);flex-shrink:0;"></i>
                                <span>荣格心理类型理论为基础</span>
                            </div>
                            <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--text-2);">
                                <i class="bi bi-check-circle-fill" style="color:var(--emerald);flex-shrink:0;"></i>
                                <span>80 道标准题覆盖 4 个维度</span>
                            </div>
                            <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--text-2);">
                                <i class="bi bi-check-circle-fill" style="color:var(--emerald);flex-shrink:0;"></i>
                                <span>题目经过信效度优化设计</span>
                            </div>
                            <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:var(--text-2);">
                                <i class="bi bi-check-circle-fill" style="color:var(--emerald);flex-shrink:0;"></i>
                                <span>覆盖职场、情感、社交等多维解读</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ==================== CTA 区域 ==================== -->
<section class="py-5 py-lg-6 home-cta-section" style="position:relative;overflow:hidden;background:var(--surface-cta);">

    <!-- 多层光效背景 -->
    <div style="position:absolute;inset:0;pointer-events:none;">
        <div style="position:absolute;top:10%;left:5%;width:350px;height:350px;background:radial-gradient(circle,rgba(129,140,248,0.18),transparent);border-radius:50%;animation:heroGlow 8s ease-in-out infinite;"></div>
        <div style="position:absolute;bottom:5%;right:8%;width:400px;height:400px;background:radial-gradient(circle,rgba(244,114,182,0.14),transparent);border-radius:50%;animation:heroGlow 10s ease-in-out infinite -3s;"></div>
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:300px;background:radial-gradient(ellipse,rgba(99,102,241,0.08),transparent);pointer-events:none;"></div>
        <!-- 网格点 -->
        <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 1px 1px,rgba(129,140,248,0.04) 1px,transparent 0);background-size:32px 32px;"></div>
    </div>

    <div class="container position-relative" style="z-index:1;">
        <!-- 主体内容 -->
        <div class="text-center animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-5 glass" style="font-size:0.84rem;">
                <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);box-shadow:0 0 10px var(--emerald);animation:statIconPulse 2s ease-in-out infinite;"></span>
                <span style="color:var(--text-2);">80 题标准测评 · 16 型人格结果 · 即刻生成电子证书</span>
            </div>

            <h2 class="fw-bold mb-4" style="font-size:clamp(1.8rem,4.5vw,3rem);letter-spacing:-0.5px;line-height:1.2;color:var(--text-1);">
                你是哪种性格？<br>
                <span class="gradient-text">现在就来揭晓</span>
            </h2>
            <p class="mb-5 mx-auto" style="color:var(--text-3);max-width:520px;font-weight:300;line-height:1.9;font-size:1.05rem;">80 道标准题 · 完整性格报告 · 专属电子证书<br>完全免费，无需注册，随时可查</p>

            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center mb-5">
                <a href="test.php" class="btn fw-bold"
                   style="font-size:1.1rem;padding:16px 48px;border-radius:60px;background:linear-gradient(135deg,#6366F1,#EC4899);color:#fff;border:none;box-shadow:0 8px 32px rgba(99,102,241,0.4),0 0 0 1px rgba(255,255,255,0.08);transition:all 0.4s cubic-bezier(.16,1,.3,1);letter-spacing:0.5px;position:relative;overflow:hidden;"
                   onmouseover="this.style.transform='translateY(-5px) scale(1.02)';this.style.boxShadow='0 20px 50px rgba(99,102,241,0.5),0 0 0 1px rgba(255,255,255,0.12)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 8px 32px rgba(99,102,241,0.4),0 0 0 1px rgba(255,255,255,0.08)'">
                    <span style="position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.2),transparent);animation:btnShine 3s ease-in-out infinite;"></span>
                    <i class="bi bi-lightning-charge-fill me-2" style="font-size:1rem;"></i>立即免费测试
                </a>
                <a href="query.php" class="btn fw-semibold"
                   style="font-size:0.95rem;padding:14px 36px;border-radius:60px;background:transparent;color:var(--text-2);border:1px solid var(--border-2);transition:all 0.35s;"
                   onmouseover="this.style.borderColor='var(--border-3)';this.style.color='var(--text-1)';this.style.background='var(--primary-surface)';this.style.transform='translateY(-3px)'"
                   onmouseout="this.style.borderColor='var(--border-2)';this.style.color='var(--text-2)';this.style.background='transparent';this.style.transform=''">
                    <i class="bi bi-search me-2"></i>查询证书
                </a>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;color:var(--text-3);">
                    <i class="bi bi-lock-fill" style="color:var(--emerald);font-size:0.78rem;"></i>完全免费
                </div>
                <div style="color:var(--border-2);">·</div>
                <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;color:var(--text-3);">
                    <i class="bi bi-person-x" style="color:var(--primary);font-size:0.78rem;"></i>无需注册
                </div>
                <div style="color:var(--border-2);">·</div>
                <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;color:var(--text-3);">
                    <i class="bi bi-shield-check" style="color:var(--cyan);font-size:0.78rem;"></i>隐私保护
                </div>
                <div style="color:var(--border-2);">·</div>
                <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;color:var(--text-3);">
                    <i class="bi bi-download" style="color:var(--rose);font-size:0.78rem;"></i>证书可下载
                </div>
                <div style="color:var(--border-2);">·</div>
                <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;color:var(--text-3);">
                    <i class="bi bi-phone" style="color:var(--amber);font-size:0.78rem;"></i>手机友好
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-2 gap-md-3" style="max-width:760px;margin:0 auto;">
                <span class="glass px-3 py-2" style="border-radius:999px;font-size:0.78rem;color:var(--text-2);">适合做自我认知</span>
                <span class="glass px-3 py-2" style="border-radius:999px;font-size:0.78rem;color:var(--text-2);">适合情侣/朋友互测</span>
                <span class="glass px-3 py-2" style="border-radius:999px;font-size:0.78rem;color:var(--text-2);">适合社媒分享证书</span>
                <span class="glass px-3 py-2" style="border-radius:999px;font-size:0.78rem;color:var(--text-2);">适合职场性格参考</span>
            </div>
        </div>
    </div>
</section>

<script>
/* ===== 统计 & 排行榜数据 ===== */

// MBTI类型信息映射
// 16种 MBTI 类型信息（Bootstrap Icons 风格）
const mbtiTypesInfo = {
    'INTJ':{name:'建筑师',color:'#818CF8',icon:'bi-diagram-3'},
    'INTP':{name:'逻辑学家',color:'#22D3EE',icon:'bi-cpu'},
    'ENTJ':{name:'指挥官',color:'#818CF8',icon:'bi-flag'},
    'ENTP':{name:'辩论家',color:'#818CF8',icon:'bi-lightbulb'},
    'INFJ':{name:'提倡者',color:'#A78BFA',icon:'bi-star'},
    'INFP':{name:'调停者',color:'#2DD4BF',icon:'bi-heart'},
    'ENFJ':{name:'主人公',color:'#2DD4BF',icon:'bi-people'},
    'ENFP':{name:'竞选者',color:'#C084FC',icon:'bi-palette'},
    'ISTJ':{name:'检查官',color:'#818CF8',icon:'bi-clipboard-check'},
    'ISFJ':{name:'守护者',color:'#34D399',icon:'bi-shield-check'},
    'ESTJ':{name:'总经理',color:'#F87171',icon:'bi-graph-up'},
    'ESFJ':{name:'执政官',color:'#FBBF24',icon:'bi-hand-thumbs-up'},
    'ISTP':{name:'鉴赏家',color:'#FBBF24',icon:'bi-tools'},
    'ISFP':{name:'探险家',color:'#F472B6',icon:'bi-flower1'},
    'ESTP':{name:'企业家',color:'#FB923C',icon:'bi-bullseye'},
    'ESFP':{name:'表演者',color:'#F472B6',icon:'bi-camera-reels'},
};

// 排行榜图标 HTML 模板
function rankIcon(iconClass, color) {
    return `<div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,${color}22,${color}10);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi ${iconClass}" style="font-size:0.88rem;color:${color};"></i></div>`;
}

// 数字计数动画
function animateCount(el, target, duration) {
    if (target <= 0) { el.textContent = '--'; return; }
    const start = 0;
    const startTime = performance.now();
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // easeOutExpo
        const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        const current = Math.floor(start + (target - start) * eased);
        el.textContent = current.toLocaleString();
        if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
}

// 渲染排行榜
function renderRanking(typeStats, total) {
    const container = document.getElementById('rankingContainer');
    if (!container) return;

    // 排序取Top5
    const sorted = typeStats.slice().sort((a, b) => b.count - a.count).slice(0, 5);

    const maxCount = sorted.length > 0 ? sorted[0].count : 1;

    if (sorted.length === 0 || total <= 0) {
        // 显示默认占位
        const defaultTypes = ['INTJ','INFP','ENTJ','ENFP','INFJ'];

        container.innerHTML = defaultTypes.map((code, i) => {
            const info = mbtiTypesInfo[code] || {name: code, color: '#818CF8', icon: 'bi-question-circle'};
            const pct = Math.max(3, 15 - i * 1.5);
            return `
            <div class="rank-row d-flex align-items-center gap-3 p-3 rounded-3 mb-2" style="background:var(--bg-${i % 2 === 0 ? '1' : '2'});border:1px solid var(--border-1);">
                <div class="fw-bold text-center" style="width:28px;font-size:1rem;color:${i < 3 ? 'var(--amber)' : 'var(--text-3)'};">${i + 1}</div>
                <div class="d-flex align-items-center gap-2.5" style="width:160px;min-width:130px;">
                    ${rankIcon(info.icon, info.color)}
                    <div>
                        <div class="fw-bold" style="font-size:0.88rem;color:var(--text-1);letter-spacing:0.5px;">${code}</div>
                        <div style="font-size:0.72rem;color:var(--text-3);">${info.name}</div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div style="height:8px;border-radius:4px;background:var(--bg-card);overflow:hidden;">
                        <div class="rank-bar-fill" style="height:100%;width:0%;border-radius:4px;background:linear-gradient(90deg,${info.color},${info.color}88);data-width="${pct}%"></div>
                    </div>
                </div>
                <div style="font-size:0.78rem;color:var(--text-3);white-space:nowrap;">--%</div>
            </div>`;
        }).join('');
        // 触发进度条动画
        requestAnimationFrame(() => {
            container.querySelectorAll('.rank-bar-fill').forEach(bar => {
                bar.style.width = bar.getAttribute('data-width');
            });
        });
        return;
    }

    container.innerHTML = sorted.map((item, i) => {
        const info = mbtiTypesInfo[item.mbti_type] || {name: item.mbti_type, color: '#818CF8', icon: 'bi-question-circle'};
        const pct = total > 0 ? ((item.count / total) * 100).toFixed(1) : 0;
        const barPct = maxCount > 0 ? ((item.count / maxCount) * 100).toFixed(1) : 0;
        return `
        <div class="rank-row d-flex align-items-center gap-3 p-3 rounded-3 mb-2" style="background:var(--bg-${i % 2 === 0 ? '1' : '2'});border:1px solid var(--border-1);">
            <div class="fw-bold text-center" style="width:28px;font-size:1rem;color:${i < 3 ? 'var(--amber)' : 'var(--text-3)'};">${i + 1}</div>
            <div class="d-flex align-items-center gap-2.5" style="width:160px;min-width:130px;">
                ${rankIcon(info.icon, info.color)}
                <div>
                    <div class="fw-bold" style="font-size:0.88rem;color:var(--text-1);letter-spacing:0.5px;">${item.mbti_type}</div>
                    <div style="font-size:0.72rem;color:var(--text-3);">${info.name}</div>
                </div>
            </div>
            <div class="flex-grow-1">
                <div style="height:8px;border-radius:4px;background:var(--bg-card);overflow:hidden;">
                    <div class="rank-bar-fill" style="height:100%;width:0%;border-radius:4px;background:linear-gradient(90deg,${info.color},${info.color}88);" data-width="${barPct}%"></div>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--text-2);white-space:nowrap;">
                <span class="fw-semibold" style="color:var(--text-1);">${item.count.toLocaleString()}</span>
                <span style="color:var(--text-3);"> 人 (${pct}%)</span>
            </div>
        </div>`;
    }).join('');

    // 延迟触发进度条动画
    requestAnimationFrame(() => {
        setTimeout(() => {
            container.querySelectorAll('.rank-bar-fill').forEach(bar => {
                bar.style.width = bar.getAttribute('data-width');
            });
        }, 100);
    });
}

// 使用 IntersectionObserver 触发统计数字动画
let statsAnimated = false;
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !statsAnimated) {
            statsAnimated = true;
            // 固定数字的动画
            document.querySelectorAll('.stat-number[data-stat="fixed"]').forEach(el => {
                const target = parseInt(el.getAttribute('data-target'));
                animateCount(el, target, 1200);
            });
        }
    });
}, { threshold: 0.3 });

document.querySelectorAll('.stat-number').forEach(el => {
    statsObserver.observe(el.closest('.stat-card'));
});

// 排行榜滚动触发
const rankObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const container = document.getElementById('rankingContainer');
            if (container && container.querySelectorAll('.rank-bar-fill').length > 0) {
                setTimeout(() => {
                    container.querySelectorAll('.rank-bar-fill').forEach(bar => {
                        bar.style.width = bar.getAttribute('data-width');
                    });
                }, 200);
            }
            rankObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.2 });

const rankContainer = document.getElementById('rankingContainer');
if (rankContainer) rankObserver.observe(rankContainer);

// 加载统计数据
(function loadStats() {
    fetch('api.php?action=get_stats')
        .then(res => res.json())
        .then(result => {
            if (result.success && result.data) {
                const { total, type_stats } = result.data;
                // 更新统计卡片
                const totalEl = document.querySelector('.stat-number[data-stat="total"]');
                const todayEl = document.querySelector('.stat-number[data-stat="today"]');
                if (totalEl && total > 0) {
                    animateCount(totalEl, total, 1800);
                }
                // 排行榜上方的人数标签
                const totalCountLabel = document.getElementById('totalCountLabel');
                if (totalCountLabel && total > 0) {
                    totalCountLabel.textContent = total.toLocaleString();
                }
                // 今天的数据需要后端支持，如果API没有返回则显示占位
                if (todayEl && result.data.today !== undefined && result.data.today > 0) {
                    animateCount(todayEl, result.data.today, 1400);
                }
                // 渲染排行榜
                renderRanking(type_stats || [], total || 0);
            } else {
                // API失败，使用占位数据
                renderRanking([], 0);
            }
        })
        .catch(err => {
            console.warn('统计数据加载失败，使用占位数据', err);
            renderRanking([], 0);
        });
})();

/* ===== 16种MBTI类型网格 ===== */
const mbtiTypes = [
    {code:'ISTJ',name:'检查官',color:'#818CF8'},
    {code:'ISFJ',name:'守护者',color:'#34D399'},
    {code:'INFJ',name:'提倡者',color:'#A78BFA'},
    {code:'INTJ',name:'建筑师',color:'#94A3B8'},
    {code:'ISTP',name:'鉴赏家',color:'#FBBF24'},
    {code:'ISFP',name:'探险家',color:'#F472B6'},
    {code:'INFP',name:'调停者',color:'#2DD4BF'},
    {code:'INTP',name:'逻辑学家',color:'#22D3EE'},
    {code:'ESTP',name:'企业家',color:'#FB923C'},
    {code:'ESFP',name:'表演者',color:'#F472B6'},
    {code:'ENFP',name:'竞选者',color:'#C084FC'},
    {code:'ENTP',name:'辩论家',color:'#818CF8'},
    {code:'ESTJ',name:'总经理',color:'#F87171'},
    {code:'ESFJ',name:'执政官',color:'#FBBF24'},
    {code:'ENFJ',name:'主人公',color:'#2DD4BF'},
    {code:'ENTJ',name:'指挥官',color:'#818CF8'},
];

const grid = document.getElementById('mbtiTypeGrid');
mbtiTypes.forEach((t, i) => {
    const col = document.createElement('div');
    col.className = 'col-3';
    col.innerHTML = `
        <div class="p-2 rounded-3 text-center"
             style="background:var(--primary-surface);cursor:default;transition:all 0.35s cubic-bezier(.4,0,.2,1);border:1px solid var(--border-1);animation:fadeInUp 0.5s ease ${i*0.04}s both;animation:typeGlow 3s ease-in-out ${i*0.2}s infinite;"
             onmouseover="this.style.background='${t.color}15';this.style.borderColor='${t.color}33';this.style.transform='translateY(-4px) scale(1.05)';this.style.boxShadow='0 0 20px ${t.color}15'"
             onmouseout="this.style.background='var(--primary-surface)';this.style.borderColor='var(--border-1)';this.style.transform='';this.style.boxShadow=''">
            <div style="font-size:0.7rem;font-weight:800;letter-spacing:1px;color:var(--text-1);">${t.code}</div>
        </div>
    `;
    grid.appendChild(col);
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
