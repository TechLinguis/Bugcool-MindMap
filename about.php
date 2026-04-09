<?php
$pageTitle = '关于本站';
require_once 'includes/header.php';
?>

    <!-- Hero -->
    <section class="py-5 text-center" style="background: var(--surface-hero); position: relative; overflow: hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%, var(--primary-glow) 0%, transparent 70%);pointer-events:none;"></div>
        <div class="container position-relative" style="padding-top:3rem;padding-bottom:3rem;">
            <div class="mb-3" style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:999px;background:var(--primary-glass);border:1px solid var(--border-2);font-size:.8rem;font-weight:600;color:var(--primary);">
                <i class="bi bi-puzzle-fill"></i> Bugcool MindMap
            </div>
            <h1 style="font-size:2.4rem;font-weight:800;color:var(--text-1);letter-spacing:-0.5px;margin-bottom:.75rem;line-height:1.2;">
                关于「<?= SITE_NAME ?>」
            </h1>
            <p style="font-size:1.05rem;color:var(--text-2);max-width:560px;margin:0 auto;line-height:1.8;">
                基于荣格心理类型理论打造的性格测试平台，帮助你用科学的方式认识自己。
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container" style="max-width:860px;margin:0 auto;">

            <!-- 项目愿景 -->
            <div class="animate-on-scroll mb-5">
                <h2 style="font-size:1.3rem;font-weight:700;color:var(--text-1);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:10px;background:var(--primary-glass);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-lightbulb-fill" style="color:var(--primary);font-size:.95rem;"></i>
                    </span>
                    项目愿景
                </h2>
                <div style="padding:1.5rem 1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:1.9;color:var(--text-2);font-size:.97rem;">
                    <p style="margin-bottom:1rem;"><?= SITE_NAME ?> 诞生于一个简单的想法：让性格探索变得<strong style="color:var(--text-1);">有趣、可信、触手可及</strong>。</p>
                    <p style="margin-bottom:1rem;">我们摒弃了传统心理测试的枯燥问卷形式，采用了现代化的界面设计和流畅的测试体验，让每一位用户在轻松愉悦的氛围中完成测试，并获得具有实际参考价值的分析报告。</p>
                    <p style="margin:0;">无论是想了解自己的职业倾向、改善人际关系，还是单纯对心理学感兴趣，<?= SITE_NAME ?> 都能为你提供一个值得信赖的起点。</p>
                </div>
            </div>

            <!-- 核心技术 -->
            <div class="animate-on-scroll mb-5">
                <h2 style="font-size:1.3rem;font-weight:700;color:var(--text-1);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:10px;background:rgba(52,211,153,0.12);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-code-slash" style="color:var(--emerald);font-size:.95rem;"></i>
                    </span>
                    核心技术
                </h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                    <?php
                    $techItems = [
                        ['icon'=>'bi-lightning-charge-fill','color'=>'var(--amber)','bg'=>'rgba(251,191,36,0.1)','title'=>'原生 PHP','desc'=>'轻量后端架构，无需复杂依赖，快速响应每一次测试请求'],
                        ['icon'=>'bi-palette-fill','color'=>'var(--rose)','bg'=>'var(--rose-glow)','title'=>'暗色 / 亮色主题','desc'=>'支持跟随系统偏好，适配多种使用场景和视力习惯'],
                        ['icon'=>'bi-phone-fill','color'=>'var(--cyan)','bg'=>'var(--cyan-glow)','title'=>'完全响应式','desc'=>'从手机到大屏，所有设备上都能获得一致的优质体验'],
                        ['icon'=>'bi-shield-check','color'=>'var(--primary)','bg'=>'var(--primary-glass)','title'=>'CSRF 安全防护','desc'=>'全站启用 CSRF token，保护每一个用户操作的安全'],
                    ];
                    foreach ($techItems as $t):
                    ?>
                    <div style="padding:1.2rem 1.4rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius);">
                        <div style="width:38px;height:38px;border-radius:10px;background:<?= $t['bg'] ?>;display:inline-flex;align-items:center;justify-content:center;margin-bottom:.75rem;">
                            <i class="bi <?= $t['icon'] ?>" style="color:<?= $t['color'] ?>;font-size:1rem;"></i>
                        </div>
                        <div style="font-weight:600;color:var(--text-1);font-size:.95rem;margin-bottom:.4rem;"><?= $t['title'] ?></div>
                        <div style="font-size:.85rem;color:var(--text-3);line-height:1.65;"><?= $t['desc'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MBTI 理论介绍 -->
            <div class="animate-on-scroll mb-5">
                <h2 style="font-size:1.3rem;font-weight:700;color:var(--text-1);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:10px;background:rgba(244,114,182,0.12);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-brain" style="color:var(--rose);font-size:.95rem;"></i>
                    </span>
                    理论基础
                </h2>
                <div style="padding:1.5rem 1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:1.9;color:var(--text-2);font-size:.97rem;">
                    <p style="margin-bottom:1rem;">MBTI（Myers-Briggs Type Indicator）以心理学家<strong style="color:var(--text-1);">卡尔·荣格</strong>的心理类型理论为基础，由凯瑟琳·布里格斯和伊莎贝尔·迈尔斯在 20 世纪中叶开发完成。</p>
                    <p style="margin-bottom:1rem;">MBTI 将人的性格归纳为四个维度，每个维度代表一种心理能量的倾向：</p>
                    <ul style="padding-left:1.2rem;margin-bottom:1rem;">
                        <li><strong style="color:var(--text-1);">外向 (E)</strong> vs <strong style="color:var(--text-1);">内向 (I)</strong> — 心理能量的来源方向</li>
                        <li><strong style="color:var(--text-1);">感觉 (S)</strong> vs <strong style="color:var(--text-1);">直觉 (N)</strong> — 获取信息的主要方式</li>
                        <li><strong style="color:var(--text-1);">思维 (T)</strong> vs <strong style="color:var(--text-1);">情感 (F)</strong> — 做决定的主要依据</li>
                        <li><strong style="color:var(--text-1);">判断 (J)</strong> vs <strong style="color:var(--text-1);">感知 (P)</strong> — 对外部世界的应对态度</li>
                    </ul>
                    <p style="margin:0;color:var(--text-3);font-size:.88rem;">
                        <i class="bi bi-info-circle" style="margin-right:4px;"></i>
                        MBTI 是一种性格倾向测试，结果仅供参考，不构成医学或职业诊断依据。
                    </p>
                </div>
            </div>

            <!-- 开源与贡献 -->
            <div class="animate-on-scroll mb-5">
                <h2 style="font-size:1.3rem;font-weight:700;color:var(--text-1);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:10px;background:var(--primary-glass);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-github" style="color:var(--primary);font-size:.95rem;"></i>
                    </span>
                    开源与贡献
                </h2>
                <div style="padding:1.5rem 1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);">
                    <p style="line-height:1.9;color:var(--text-2);font-size:.97rem;margin-bottom:1.2rem;">
                        <?= SITE_NAME ?> 是完全开源的项目，代码托管于 GitHub，欢迎开发者参与贡献、提交 Issue 或 Fork 进行二次开发。
                    </p>
                    <div style="display:flex;align-items:center;gap:12px;padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <i class="bi bi-github" style="font-size:1.5rem;color:var(--text-2);"></i>
                        <div>
                            <div style="font-weight:600;color:var(--text-1);font-size:.95rem;margin-bottom:2px;">Bugcool MindMap</div>
                            <a href="https://github.com/TechLinguis/Bugcool-MindMap" target="_blank" rel="noopener noreferrer"
                               style="font-size:.88rem;color:var(--primary);text-decoration:none;word-break:break-all;">
                                github.com/TechLinguis/Bugcool-MindMap
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 隐私声明 -->
            <div class="animate-on-scroll mb-4">
                <h2 style="font-size:1.3rem;font-weight:700;color:var(--text-1);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:10px;background:rgba(52,211,153,0.12);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-lock-fill" style="color:var(--emerald);font-size:.95rem;"></i>
                    </span>
                    隐私与数据
                </h2>
                <div style="padding:1.5rem 1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:1.9;color:var(--text-2);font-size:.97rem;">
                    <ul style="padding-left:1.2rem;margin:0;">
                        <li style="margin-bottom:.5rem;">你的测试结果仅存储在<strong style="color:var(--text-1);">本地服务器</strong>，不与任何第三方共享。</li>
                        <li style="margin-bottom:.5rem;">邮箱地址仅用于发送测试结果（需主动勾选），不会用于任何营销用途。</li>
                        <li style="margin-bottom:.5rem;">所有数据传输采用加密连接，保护你的个人信息安全。</li>
                        <li>如对数据处理有任何疑问，欢迎通过 GitHub 提交 Issue 联系我们。</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 text-center" style="background:var(--surface-cta);border-top:1px solid var(--border-1);">
        <div class="container">
            <h3 style="font-size:1.2rem;font-weight:700;color:var(--text-1);margin-bottom:.5rem;">准备好了解真实的自己了吗？</h3>
            <p style="color:var(--text-3);font-size:.92rem;margin-bottom:1.5rem;">免费测试，约 5 分钟完成，即刻获得专属分析报告</p>
            <a href="test.php" class="btn btn-primary-mbti" style="display:inline-flex;align-items:center;gap:8px;padding:.8rem 2rem;border-radius:999px;background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;font-weight:700;font-size:.95rem;border:none;box-shadow:0 4px 20px var(--primary-glow);text-decoration:none;transition:all .3s ease;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px var(--primary-glow)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 20px var(--primary-glow)'">
                <i class="bi bi-play-circle-fill"></i> 立即开始测试
            </a>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
