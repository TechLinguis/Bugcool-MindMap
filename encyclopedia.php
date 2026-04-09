<?php
$pageTitle = 'MBTI 百科';
require_once 'includes/header.php';
?>

    <!-- Hero -->
    <section class="py-5 text-center" style="background: var(--surface-hero); position: relative; overflow: hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%, var(--primary-glow) 0%, transparent 70%);pointer-events:none;"></div>
        <div class="container position-relative" style="padding-top:3rem;padding-bottom:3rem;">
            <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:999px;background:var(--primary-glass);border:1px solid var(--border-2);font-size:.8rem;font-weight:600;color:var(--primary);margin-bottom:1rem;">
                <i class="bi bi-book-fill"></i> 知识库
            </div>
            <h1 style="font-size:2.4rem;font-weight:800;color:var(--text-1);letter-spacing:-0.5px;margin-bottom:.75rem;line-height:1.2;">MBTI 百科</h1>
            <p style="font-size:1.05rem;color:var(--text-2);max-width:540px;margin:0 auto;line-height:1.8;">从理论基础到实践应用，系统了解 MBTI 性格类型体系的方方面面。</p>
        </div>
    </section>

    <div class="container py-5" style="max-width:1100px;margin:0 auto;">

        <!-- 目录导航 -->
        <div class="animate-on-scroll mb-5" style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
            <a href="#origin" class="toc-item"><i class="bi bi-clock-history"></i> 起源与发展</a>
            <a href="#theory" class="toc-item"><i class="bi bi-diagram-3"></i> 理论基础</a>
            <a href="#types" class="toc-item"><i class="bi bi-grid-3x3-gap"></i> 16 种人格类型</a>
            <a href="#dimensions" class="toc-item"><i class="bi bi-sliders"></i> 四维度详解</a>
            <a href="#application" class="toc-item"><i class="bi bi-briefcase"></i> 实践应用</a>
            <a href="#criticism" class="toc-item"><i class="bi bi-chat-left-text"></i> 争议与局限</a>
        </div>
        <style>
            .toc-item { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:var(--bg-card); border:1px solid var(--border-2); color:var(--text-2); font-size:.88rem; font-weight:600; text-decoration:none; transition:all .25s ease; }
            .toc-item:hover { color:var(--primary); border-color:var(--primary); background:var(--primary-glass); }
        </style>

        <!-- 01 起源与发展 -->
        <div id="origin" class="animate-on-scroll mb-5">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-glass);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-clock-history" style="color:var(--primary);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">起源与发展</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">The Origin & Evolution</p></div>
            </div>
            <div style="padding:1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:2;color:var(--text-2);font-size:.97rem;">
                <p style="margin-bottom:1rem;">MBTI 的历史可以追溯到 <strong style="color:var(--text-1);">1913 年</strong>。那年，著名心理学家 <strong style="color:var(--text-1);">卡尔·荣格</strong>（Carl Jung）在慕尼黑技术学校发表了一系列关于心理类型的讲座，首次提出了内向与外向两种基本态度类型，以及感觉、直觉、思维、情感四种心理功能。</p>
                <p style="margin-bottom:1rem;">1944 年，<strong style="color:var(--text-1);">凯瑟琳·布里格斯</strong>和她的女儿 <strong style="color:var(--text-1);">伊莎贝尔·迈尔斯</strong>在荣格理论的基础上，编制出了第一版 MBTI 问卷。她们希望通过这个工具，帮助人们更好地理解个体差异，找到最适合自己的职业和生活方式。</p>
                <p style="margin-bottom:1.5rem;">此后数十年间，MBTI 经历了多次修订和完善，如今已被翻译成 <strong style="color:var(--text-1);">30 多种语言</strong>，每年有超过 <strong style="color:var(--text-1);">200 万人次</strong>使用，是全球应用最广泛的性格测试工具之一。</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                    <?php
                    $milestones = [
                        ['year'=>'1913','event'=>'荣格首次提出心理类型理论'],
                        ['year'=>'1944','event'=>'布里格斯母女编制第一版 MBTI'],
                        ['year'=>'1950s','event'=>'迈尔斯持续完善问卷设计'],
                        ['year'=>'Today','event'=>'每年超过 200 万人次使用'],
                    ];
                    foreach ($milestones as $m):
                    ?>
                    <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <div style="font-weight:800;font-size:1.1rem;color:var(--primary);margin-bottom:.4rem;"><?= $m['year'] ?></div>
                        <div style="font-size:.85rem;color:var(--text-3);line-height:1.6;"><?= $m['event'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- 02 理论基础 -->
        <div id="theory" class="animate-on-scroll mb-5">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(34,211,238,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-diagram-3" style="color:var(--cyan);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">理论基础</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">Theoretical Foundation</p></div>
            </div>
            <div style="padding:1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:2;color:var(--text-2);font-size:.97rem;">
                <p style="margin-bottom:1.5rem;">MBTI 的理论基础建立在三个核心概念之上：</p>
                <div style="display:grid;gap:1rem;">
                    <div style="padding:1.2rem 1.4rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <div style="font-weight:800;font-size:.7rem;color:var(--primary);margin-bottom:4px;letter-spacing:1px;">01</div>
                        <div style="font-weight:700;color:var(--text-1);margin-bottom:2px;">态度类型（Attitude）</div>
                        <div style="font-size:.82rem;color:var(--amber);margin-bottom:.5rem;">外向 (E) vs 内向 (I)</div>
                        <div style="font-size:.9rem;color:var(--text-3);">这一维度描述一个人的心理能量是倾向于外部世界（人际交往、行动、外部事件），还是内部世界（内心想法、回忆、反省）。</div>
                    </div>
                    <div style="padding:1.2rem 1.4rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <div style="font-weight:800;font-size:.7rem;color:var(--cyan);margin-bottom:4px;letter-spacing:1px;">02</div>
                        <div style="font-weight:700;color:var(--text-1);margin-bottom:2px;">心理功能（Function）</div>
                        <div style="font-size:.82rem;color:var(--cyan);margin-bottom:.5rem;">感知功能：感觉 (S) vs 直觉 (N)</div>
                        <div style="font-size:.9rem;color:var(--text-3);margin-bottom:.8rem;">感知功能决定一个人如何获取和处理信息。感觉型偏好关注具体的事实和细节，直觉型则偏好可能性和模式。</div>
                        <div style="font-size:.82rem;color:var(--rose);margin-bottom:.5rem;">判断功能：思维 (T) vs 情感 (F)</div>
                        <div style="font-size:.9rem;color:var(--text-3);">判断功能决定一个人如何做决定。思维型偏好逻辑和客观原则，情感型则考虑价值和人际和谐。</div>
                    </div>
                    <div style="padding:1.2rem 1.4rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <div style="font-weight:800;font-size:.7rem;color:var(--rose);margin-bottom:4px;letter-spacing:1px;">03</div>
                        <div style="font-weight:700;color:var(--text-1);margin-bottom:2px;">生活方式（Lifestyle）</div>
                        <div style="font-size:.82rem;color:var(--rose);margin-bottom:.5rem;">判断 (J) vs 感知 (P)</div>
                        <div style="font-size:.9rem;color:var(--text-3);">这一维度描述一个人如何应对外部世界。判断型倾向于有计划、有组织的生活，感知型则偏好灵活、开放、随遇而安的方式。</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 03 16种人格类型 -->
        <div id="types" class="animate-on-scroll mb-5">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(244,114,182,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-grid-3x3-gap" style="color:var(--rose);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">16 种人格类型</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">16 Personality Types</p></div>
            </div>
            <div style="padding:1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:1.8;color:var(--text-2);font-size:.97rem;">
                <p style="margin-bottom:1.5rem;">四个维度的两极组合产生了 <strong style="color:var(--text-1);">16 种</strong>不同的人格类型，每种类型用四个字母表示：</p>
                <div style="display:grid;gap:2rem;">

                    <div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                            <i class="bi bi-cpu" style="color:var(--primary);"></i>
                            <span style="font-weight:700;color:var(--text-1);">分析师 (Analysts)</span>
                            <span style="font-size:.78rem;color:var(--text-4);">INT · 偏爱理性思维</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;">
                            <?php foreach ([['INTJ','建筑师','var(--primary)','富有想象力和战略眼光的思想家，一切都在他们的计划之中。'],['INTP','逻辑学家','var(--cyan)','用创意和逻辑探索世界的抽象思考者。'],['ENTJ','指挥官','var(--rose)','大胆、富有想象力、意志坚定的领导者。'],['ENTP','辩论家','var(--emerald)','聪明好奇的思考者，热衷于智识挑战。']] as $t): ?>
                            <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:44px;height:44px;border-radius:10px;background:rgba(129,140,248,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(129,140,248,0.2);"><span style="font-weight:800;font-size:.78rem;color:<?= $t[2] ?>;"><?= $t[0] ?></span></div>
                                <div><div style="font-weight:700;color:var(--text-1);font-size:.92rem;margin-bottom:3px;"><?= $t[1] ?></div><div style="font-size:.8rem;color:var(--text-3);line-height:1.5;"><?= $t[3] ?></div></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                            <i class="bi bi-heart-fill" style="color:var(--rose);"></i>
                            <span style="font-weight:700;color:var(--text-1);">外交家 (Diplomats)</span>
                            <span style="font-size:.78rem;color:var(--text-4);">INF · 关注人际关系</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;">
                            <?php foreach ([['INFJ','提倡者','var(--primary)','安静而富有同理心的理想主义者，激励他人行动。'],['INFP','调停者','var(--cyan)','诗意、善良的利他主义者，始终坚守内心信念。'],['ENFJ','主人公','var(--rose)','富有魅力、鼓舞人心的领导者，能言善道。'],['ENFP','竞选者','var(--amber)','热情洋溢、充满创意的社交高手，点燃周围人的激情。']] as $t): ?>
                            <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:44px;height:44px;border-radius:10px;background:rgba(244,114,182,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(244,114,182,0.2);"><span style="font-weight:800;font-size:.78rem;color:<?= $t[2] ?>;"><?= $t[0] ?></span></div>
                                <div><div style="font-weight:700;color:var(--text-1);font-size:.92rem;margin-bottom:3px;"><?= $t[1] ?></div><div style="font-size:.8rem;color:var(--text-3);line-height:1.5;"><?= $t[3] ?></div></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                            <i class="bi bi-shield-fill" style="color:var(--emerald);"></i>
                            <span style="font-weight:700;color:var(--text-1);">守护者 (Sentinels)</span>
                            <span style="font-size:.78rem;color:var(--text-4);">ISJ · 注重稳定与秩序</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;">
                            <?php foreach ([['ISTJ','物流师','var(--primary)','务实、可靠的事实守护者，注重诚信和秩序。'],['ISFJ','守卫者','var(--emerald)','温暖、可靠、富有奉献精神的保护者。'],['ESTJ','总经理','var(--rose)','优秀的组织者，专注于将事情有序推进。'],['ESFJ','执政官','var(--amber)','细心、外向、热心公益的照顾者。']] as $t): ?>
                            <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:44px;height:44px;border-radius:10px;background:rgba(52,211,153,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(52,211,153,0.2);"><span style="font-weight:800;font-size:.78rem;color:<?= $t[2] ?>;"><?= $t[0] ?></span></div>
                                <div><div style="font-weight:700;color:var(--text-1);font-size:.92rem;margin-bottom:3px;"><?= $t[1] ?></div><div style="font-size:.8rem;color:var(--text-3);line-height:1.5;"><?= $t[3] ?></div></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                            <i class="bi bi-compass" style="color:var(--amber);"></i>
                            <span style="font-weight:700;color:var(--text-1);">探险家 (Explorers)</span>
                            <span style="font-size:.78rem;color:var(--text-4);">ISP · 灵活多变，偏好即兴与体验</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;">
                            <?php foreach ([['ISTP','鉴赏家','var(--cyan)','大胆而务实的实验者，擅长用双手和工具理解事物。'],['ISFP','探险家','var(--emerald)','灵活、魅力四射的艺术家，随时准备探索和体验新事物。'],['ESTP','企业家','var(--amber)','聪明、精力充沛的冒险者，擅长将问题转化为行动。'],['ESFP','表演者','var(--rose)','自发、精力充沛的表演者，热爱成为关注的焦点。']] as $t): ?>
                            <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:44px;height:44px;border-radius:10px;background:rgba(251,191,36,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(251,191,36,0.2);"><span style="font-weight:800;font-size:.78rem;color:<?= $t[2] ?>;"><?= $t[0] ?></span></div>
                                <div><div style="font-weight:700;color:var(--text-1);font-size:.92rem;margin-bottom:3px;"><?= $t[1] ?></div><div style="font-size:.8rem;color:var(--text-3);line-height:1.5;"><?= $t[3] ?></div></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 04 四维度详解 -->
        <div id="dimensions" class="animate-on-scroll mb-5">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(251,191,36,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-sliders" style="color:var(--amber);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">四维度详解</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">The Four Dichotomies</p></div>
            </div>
            <div style="display:grid;gap:1rem;">

                <?php
                $dims = [
                    ['label'=>'E / I','title'=>'外向 vs 内向','color'=>'var(--amber)','bg'=>'rgba(251,191,36,0.1)','eL'=>'外向 E','eD'=>'通过与人互动来获得能量，喜欢讨论、社交和行动，主动表达想法。','iL'=>'内向 I','iD'=>'通过独处和内省来恢复能量，喜欢深思熟虑，独立工作更高效。'],
                    ['label'=>'S / N','title'=>'感觉 vs 直觉','color'=>'var(--cyan)','bg'=>'var(--cyan-glow)','eL'=>'感觉 S','eD'=>'关注具体、实际的信息，信赖事实和五官感受，偏好可操作的方法。','iL'=>'直觉 N','iD'=>'关注整体模式和未来可能性，信赖直觉和第六感，偏好想象和创意。'],
                    ['label'=>'T / F','title'=>'思维 vs 情感','color'=>'var(--rose)','bg'=>'var(--rose-glow)','eL'=>'思维 T','eD'=>'依据逻辑和客观分析做决定，关注因果关系和一致性。','iL'=>'情感 F','iD'=>'依据个人价值观和他人感受做决定，关注和谐与共情。'],
                    ['label'=>'J / P','title'=>'判断 vs 感知','color'=>'var(--primary)','bg'=>'var(--primary-glass)','eL'=>'判断 J','eD'=>'喜欢有计划、有组织的生活，按清单行事，倾向于快速做决定。','iL'=>'感知 P','iD'=>'喜欢灵活、开放的生活方式，随机应变，倾向于保持开放选项。'],
                ];
                foreach ($dims as $d):
                ?>
                <div style="padding:1.5rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem;">
                        <div style="width:40px;height:40px;border-radius:10px;background:<?= $d['bg'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-circle-fill" style="color:<?= $d['color'] ?>;font-size:.5rem;"></i></div>
                        <span style="font-weight:800;font-size:1rem;color:<?= $d['color'] ?>;margin-right:8px;"><?= $d['label'] ?></span>
                        <span style="font-weight:600;color:var(--text-2);font-size:.88rem;"><?= $d['title'] ?></span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                            <div style="font-weight:700;color:var(--text-1);font-size:.88rem;margin-bottom:6px;"><span style="color:<?= $d['color'] ?>;margin-right:4px;">←</span><?= $d['eL'] ?></div>
                            <div style="font-size:.85rem;color:var(--text-3);line-height:1.6;"><?= $d['eD'] ?></div>
                        </div>
                        <div style="padding:1rem 1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                            <div style="font-weight:700;color:var(--text-1);font-size:.88rem;margin-bottom:6px;"><span style="color:<?= $d['color'] ?>;margin-right:4px;">→</span><?= $d['iL'] ?></div>
                            <div style="font-size:.85rem;color:var(--text-3);line-height:1.6;"><?= $d['iD'] ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- 05 实践应用 -->
        <div id="application" class="animate-on-scroll mb-5">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(52,211,153,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-briefcase-fill" style="color:var(--emerald);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">实践应用</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">Practical Applications</p></div>
            </div>
            <div style="padding:1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
                    <?php
                    $apps = [
                        ['icon'=>'bi-briefcase','title'=>'职业规划','desc'=>'了解自己偏好的工作方式和职业倾向，找到更有成就感的职业方向。'],
                        ['icon'=>'bi-people','title'=>'团队协作','desc'=>'理解队友的性格差异，提升沟通效率，减少团队摩擦。'],
                        ['icon'=>'bi-heart','title'=>'人际关系','desc'=>'认识自己和他人的情感需求，建立更健康的人际边界。'],
                        ['icon'=>'bi-person-check','title'=>'自我认知','desc'=>'深入了解自己的思维模式和行事风格，发挥优势，接纳局限。'],
                        ['icon'=>'bi-mortarboard','title'=>'教育学习','desc'=>'根据性格特点调整学习策略，找到最适合自己的输入方式。'],
                        ['icon'=>'bi-arrow-left-right','title'=>'沟通提升','desc'=>'理解不同性格的沟通偏好，更有针对性地表达和倾听。'],
                    ];
                    foreach ($apps as $a):
                    ?>
                    <div style="padding:1.2rem;background:var(--bg-card);border:1px solid var(--border-2);border-radius:var(--radius);">
                        <div style="width:36px;height:36px;border-radius:9px;background:var(--primary-glass);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem;"><i class="bi <?= $a['icon'] ?>" style="color:var(--primary);font-size:.88rem;"></i></div>
                        <div style="font-weight:600;color:var(--text-1);font-size:.92rem;margin-bottom:.4rem;"><?= $a['title'] ?></div>
                        <div style="font-size:.85rem;color:var(--text-3);line-height:1.6;"><?= $a['desc'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- 06 争议与局限 -->
        <div id="criticism" class="animate-on-scroll mb-4">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(251,191,36,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-chat-left-text-fill" style="color:var(--amber);font-size:1.1rem;"></i></div>
                <div><h2 style="font-size:1.3rem;font-weight:800;color:var(--text-1);margin:0;">争议与局限</h2><p style="font-size:.8rem;color:var(--text-4);margin:0;">Criticism & Limitations</p></div>
            </div>
            <div style="padding:1.75rem;background:var(--surface-result-panel);border:1px solid var(--surface-result-outline);border-radius:var(--radius-lg);line-height:2;color:var(--text-2);font-size:.97rem;">
                <p style="margin-bottom:1rem;">MBTI 作为一种流行的性格测试工具，在学术界也存在着不少争议。以下是一些主要的批评声音：</p>
                <ul style="padding-left:1.2rem;margin-bottom:1rem;">
                    <li style="margin-bottom:.5rem;"><strong style="color:var(--text-1);">测试-再测信度问题：</strong>研究表明，同一个人在不同时间做 MBTI 测试，可能得到不同的结果，这说明其稳定性有待验证。</li>
                    <li style="margin-bottom:.5rem;"><strong style="color:var(--text-1);">缺乏充分的理论支撑：</strong>荣格的理论本质上是一种哲学和经验性的描述，而非经过严格科学验证的心理学理论。</li>
                    <li style="margin-bottom:.5rem;"><strong style="color:var(--text-1);">二分法过于简化：</strong>将人简单地划分为两个极端（E 或 I），忽略了大多数人在两极之间都有不同程度的倾向。</li>
                    <li style="margin-bottom:.5rem;"><strong style="color:var(--text-1);">Barnum 效应：</strong>批评者认为 MBTI 的描述过于笼统和模糊，几乎适用于任何人（类似于星座运势）。</li>
                    <li style="margin-bottom:.5rem;"><strong style="color:var(--text-1);">非预测性：</strong>MBTI 描述的是偏好和倾向，而非能力，无法准确预测一个人在实际情境中的行为表现。</li>
                </ul>
                <div style="padding:1rem 1.2rem;background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);border-radius:var(--radius);font-size:.88rem;color:var(--amber);">
                    <i class="bi bi-exclamation-triangle-fill" style="margin-right:5px;"></i>
                    <strong>建议：</strong>将 MBTI 视为自我探索的起点，而非终极答案。了解自己的性格倾向可以提供有价值的参考，但不应被标签化或用于限制自己的可能性。
                </div>
            </div>
        </div>

    </div>

<?php require_once 'includes/footer.php'; ?>

