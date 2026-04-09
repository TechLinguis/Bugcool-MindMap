<?php $pageTitle = 'MBTI 性格测试'; include_once __DIR__ . '/includes/header.php'; ?>


<style>
    .option-btn.selected { transform: translateY(-2px) !important; }
</style>

<!-- ==================== 测试开始页 ==================== -->
<section id="testIntro" class="py-5" style="min-height:calc(100vh - 72px);display:flex;align-items:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;pointer-events:none;overflow:hidden;">
        <div class="hero-gradient-orb animate-morph" style="top:-10%;right:-10%;width:350px;height:350px;background:rgba(99,102,241,0.12);animation-delay:-2s;"></div>
        <div class="hero-gradient-orb animate-morph" style="bottom:-5%;left:-5%;width:300px;height:300px;background:rgba(34,211,238,0.08);animation-delay:-4s;"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-7 animate-fadeInUp">
                <div class="card-mbti p-4 p-lg-5 position-relative overflow-hidden">
                    <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--primary),var(--cyan),var(--rose),var(--amber));"></div>

                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width:88px;height:88px;border-radius:24px;background:linear-gradient(135deg,var(--primary-dim),var(--rose));box-shadow:0 12px 32px var(--primary-glow);">
                            <i class="bi bi-clipboard2-pulse text-white" style="font-size:2.4rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-2" style="font-size:1.9rem;letter-spacing:-0.3px;color:var(--text-1);">MBTI 性格测试</h2>
                        <p class="d-flex justify-content-center gap-3 flex-wrap" style="color:var(--text-2);font-size:0.95rem;">
                            <span class="d-inline-flex align-items-center gap-1"><span class="fw-bold" style="color:var(--primary);">80</span> 道题目</span>
                            <span style="opacity:0.3;">·</span>
                            <span class="d-inline-flex align-items-center gap-1"><span class="fw-bold" style="color:var(--cyan);">4</span> 个维度</span>
                            <span style="opacity:0.3;">·</span>
                            <span>约 <span class="fw-bold" style="color:var(--rose);">10</span> 分钟</span>
                        </p>
                    </div>

                    <!-- 测试须知 -->
                    <div class="p-3 p-md-4 rounded-4 mb-4" style="background:var(--bg-2);border:1px solid var(--border-1);">
                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:var(--text-1);">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;border-radius:8px;background:var(--primary-glass);">
                                <i class="bi bi-info-circle-fill" style="color:var(--primary);font-size:0.85rem;"></i>
                            </span>
                            测试须知
                        </h6>
                        <ul class="mb-0 ps-0" style="color:var(--text-2);font-size:0.9rem;line-height:2.2;list-style:none;">
                            <li class="d-flex gap-2"><i class="bi bi-check-circle-fill" style="color:var(--emerald);margin-top:6px;flex-shrink:0;font-size:0.8rem;"></i>请选择最符合你真实情况的选项，而非你期望的答案</li>
                            <li class="d-flex gap-2"><i class="bi bi-check-circle-fill" style="color:var(--emerald);margin-top:6px;flex-shrink:0;font-size:0.8rem;"></i>没有"正确"或"错误"的答案，选择第一直觉即可</li>
                            <li class="d-flex gap-2"><i class="bi bi-check-circle-fill" style="color:var(--emerald);margin-top:6px;flex-shrink:0;font-size:0.8rem;"></i>请在安静的环境下完成，确保结果更准确</li>
                            <li class="d-flex gap-2"><i class="bi bi-check-circle-fill" style="color:var(--emerald);margin-top:6px;flex-shrink:0;font-size:0.8rem;"></i>测试完成后将自动生成你的专属 MBTI 证书</li>
                        </ul>
                    </div>

                    <!-- 快捷键提示 -->
                    <div class="d-flex align-items-center justify-content-center gap-4 mb-4 flex-wrap" style="font-size:0.82rem;color:var(--text-3);">
                        <span><kbd class="px-2 py-0.5 rounded" style="background:var(--bg-3);border:1px solid var(--border-2);font-size:0.75rem;color:var(--text-1);">A</kbd> 选择 A</span>
                        <span><kbd class="px-2 py-0.5 rounded" style="background:var(--bg-3);border:1px solid var(--border-2);font-size:0.75rem;color:var(--text-1);">B</kbd> 选择 B</span>
                        <span><kbd class="px-2 py-0.5 rounded" style="background:var(--bg-3);border:1px solid var(--border-2);font-size:0.75rem;color:var(--text-1);">←</kbd> 上一题</span>
                        <span><kbd class="px-2 py-0.5 rounded" style="background:var(--bg-3);border:1px solid var(--border-2);font-size:0.75rem;color:var(--text-1);">→</kbd> 下一题</span>
                    </div>

                    <button class="btn w-100 py-3 fw-semibold" style="background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;border-radius:14px;box-shadow:0 8px 24px var(--primary-glow);font-size:1.05rem;transition:all 0.3s;border:none;" id="btn-start-test" onclick="showUserForm()" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(129,140,248,0.45)'" onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px var(--primary-glow)'">
                        <i class="bi bi-shield-check me-2"></i>开始测试
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 用户信息弹窗 ==================== -->
<div id="userFormModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--bg-1);border:1px solid var(--border-1);border-radius:20px;padding:36px 32px;width:100%;max-width:440px;box-shadow:0 24px 60px rgba(0,0,0,.6);animation:fadeInUp .3s ease">
        <div class="text-center mb-4">
            <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,var(--primary-dim),var(--rose));display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;box-shadow:0 8px 24px var(--primary-glow)">
                <i class="bi bi-envelope-at text-white" style="font-size:1.4rem"></i>
            </div>
            <h3 class="fw-bold mb-1" style="font-size:1.2rem;color:var(--text-1)">开始测试前</h3>
            <p style="font-size:.85rem;color:var(--text-3);margin:0">填写邮箱，完成测试后自动发送结果</p>
        </div>
        <div style="margin-bottom:20px">
            <div style="font-size:.8rem;font-weight:600;color:var(--text-2);margin-bottom:6px">邮箱 <span style="color:var(--text-4)">(选填，用于接收测试结果)</span></div>
            <input class="inp" id="userEmail" type="email" placeholder="your@email.com" style="width:100%;padding:12px 16px;border-radius:12px;background:var(--bg-card);border:1px solid var(--border-2);color:var(--text-1);font-size:.95rem;outline:none;transition:border-color .2s" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-2)'">
            <div style="font-size:.72rem;color:var(--text-4);margin-top:5px">不填写则无法在测试后收到邮件通知</div>
        </div>
        <div style="display:flex;gap:10px">
            <button onclick="closeUserForm()" style="flex:1;padding:12px;border-radius:12px;border:1px solid var(--border-2);background:var(--bg-card);color:var(--text-2);font-weight:500;cursor:pointer;font-size:.95rem;transition:all .2s" onmouseover="this.style.borderColor='var(--border-3)'" onmouseout="this.style.borderColor='var(--border-2)'">稍后填写</button>
            <button onclick="startTest()" style="flex:2;padding:12px;border-radius:12px;border:none;background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;font-weight:600;cursor:pointer;font-size:.95rem;box-shadow:0 6px 20px var(--primary-glow);transition:all .2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(129,140,248,.45)'" onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px var(--primary-glow)'">
                <i class="bi bi-lightning-charge-fill me-1"></i>开始测试
            </button>
        </div>
    </div>
</div>

<!-- ==================== 答题区域 ==================== -->
<section id="testArea" style="display:none;">
    <div class="sticky-top" id="progressSticky" style="top:72px;backdrop-filter:blur(16px);z-index:100;padding:14px 0;border-bottom:1px solid var(--border-1);box-shadow:var(--shadow-sm);background:var(--bg-0);">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-line" style="color:var(--primary);"></i>
                    <span class="fw-semibold" style="font-size:0.88rem;color:var(--text-2);">测试进度</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1.5 px-2.5 py-1 rounded-lg" style="background:var(--primary-surface);border:1px solid var(--border-1);">
                        <i class="bi bi-stopwatch" style="color:var(--primary);font-size:0.82rem;"></i>
                        <span id="timerDisplay" style="font-size:0.88rem;font-weight:600;color:var(--text-1);font-variant-numeric:tabular-nums;letter-spacing:0.5px;">00:00</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span id="currentNum" class="fw-bold" style="color:var(--primary);font-size:1rem;">1</span>
                        <span style="color:var(--text-3);font-size:0.85rem;">/</span>
                        <span id="totalNum" style="color:var(--text-3);font-size:0.85rem;">80</span>
                    </div>
                </div>
            </div>
            <div class="progress" style="height:6px;border-radius:10px;background:var(--progress-bg);">
                <div id="progressBar" class="progress-bar" style="width:1.25%;border-radius:10px;background:linear-gradient(90deg,var(--primary-dim),var(--primary));transition:width 0.5s cubic-bezier(.4,0,.2,1);"></div>
            </div>
            <div class="d-flex justify-content-between mt-3 px-1">
                <span class="dim-indicator active" data-dim="EI" style="font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:6px;background:rgba(129,140,248,0.12);color:var(--primary);">E/I</span>
                <span class="dim-indicator" data-dim="SN" style="font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:6px;color:var(--text-3);">S/N</span>
                <span class="dim-indicator" data-dim="TF" style="font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:6px;color:var(--text-3);">T/F</span>
                <span class="dim-indicator" data-dim="JP" style="font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:6px;color:var(--text-3);">J/P</span>
            </div>
        </div>
    </div>

    <div class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div id="questionCard" class="card-mbti p-4 p-lg-5 animate-fadeIn position-relative overflow-hidden">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <span id="dimBadge" class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill" style="background:rgba(129,140,248,0.12);color:var(--primary);font-size:0.82rem;font-weight:600;">
                            <i class="bi bi-tag-fill" style="font-size:0.7rem;"></i> E / I 维度
                        </span>
                        <span style="color:var(--text-3);font-size:0.85rem;">第 <span id="qNum" class="fw-bold" style="color:var(--primary);">1</span> 题</span>
                    </div>
                    <h4 id="qText" class="fw-bold mb-4" style="font-size:1.2rem;line-height:1.8;color:var(--text-1);">在社交聚会中，你通常会：</h4>
                    <div class="d-grid gap-3" id="optionsArea">
                        <button class="btn option-btn text-start p-3 p-md-4" onclick="selectAnswer('a')" style="border:2px solid var(--border-2);border-radius:16px;transition:all 0.3s cubic-bezier(.4,0,.2,1);font-size:0.98rem;background:var(--bg-card);color:var(--text-1);">
                            <span class="d-inline-flex align-items-center justify-content-center me-3" style="width:36px;height:36px;border-radius:10px;background:var(--bg-3);font-weight:700;color:var(--primary);flex-shrink:0;font-size:0.9rem;transition:all 0.3s;">A</span>
                            <span id="optA" style="line-height:1.6;">主动与陌生人攀谈，享受认识新朋友</span>
                        </button>
                        <button class="btn option-btn text-start p-3 p-md-4" onclick="selectAnswer('b')" style="border:2px solid var(--border-2);border-radius:16px;transition:all 0.3s cubic-bezier(.4,0,.2,1);font-size:0.98rem;background:var(--bg-card);color:var(--text-1);">
                            <span class="d-inline-flex align-items-center justify-content-center me-3" style="width:36px;height:36px;border-radius:10px;background:var(--bg-3);font-weight:700;color:var(--rose);flex-shrink:0;font-size:0.9rem;transition:all 0.3s;">B</span>
                            <span id="optB" style="line-height:1.6;">和熟悉的朋友待在一起，不太主动社交</span>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button id="prevBtn" class="btn d-none" onclick="prevQuestion()" style="border:1px solid var(--border-2);border-radius:12px;padding:10px 20px;font-weight:500;color:var(--text-2);background:var(--bg-card);transition:all 0.3s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border-2)';this.style.color='var(--text-2)'">
                        <i class="bi bi-arrow-left me-1"></i> 上一题
                    </button>
                    <div></div>
                    <button id="nextBtn" class="btn d-none" onclick="nextQuestion()" style="background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;border-radius:12px;padding:10px 24px;font-weight:500;border:none;box-shadow:0 4px 16px var(--primary-glow);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(129,140,248,0.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px var(--primary-glow)'">
                        下一题 <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 提交中 ==================== -->
<section id="testSubmitting" style="display:none;">
    <div class="container text-center py-5" style="min-height:calc(100vh - 72px);display:flex;align-items:center;justify-content:center;">
        <div class="animate-fadeIn">
            <div class="mb-4 position-relative d-inline-block">
                <div class="loading-spinner mx-auto" style="width:72px;height:72px;border-width:4px;"></div>
                <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                    <i class="bi bi-lightning-charge-fill" style="color:var(--primary);font-size:1.5rem;"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-2" style="font-size:1.3rem;color:var(--text-1);">正在分析你的性格...</h4>
            <p style="color:var(--text-3);font-weight:300;">请稍候，正在为你生成专属证书</p>
        </div>
    </div>
</section>

<script>
var questions = [];
var currentIndex = 0;
var answers = {};
var timerInterval = null;
var timerSeconds = 0;
var userEmail = '';

var dimInfo = {
    'EI': {label: 'E / I 维度', color: '#818CF8'},
    'SN': {label: 'S / N 维度', color: '#22D3EE'},
    'TF': {label: 'T / F 维度', color: '#F472B6'},
    'JP': {label: 'J / P 维度', color: '#FBBF24'}
};

function showUserForm() {
    document.getElementById('userFormModal').style.display = 'flex';
}
function closeUserForm() {
    document.getElementById('userFormModal').style.display = 'none';
}

function startTest() {
    // 获取用户填写的邮箱
    userEmail = document.getElementById('userEmail').value.trim();
    document.getElementById('userFormModal').style.display = 'none';

    fetch('api.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'action=get_questions'})
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                questions = res.data;
                document.getElementById('totalNum').textContent = questions.length;
                document.getElementById('testIntro').style.display = 'none';
                document.getElementById('testArea').style.display = 'block';
                startTimer();
                renderQuestion();
            } else { showToast('加载题目失败：' + res.message, 'error'); }
        })
        .catch(function() { showToast('网络错误，请重试', 'error'); });
}

function startTimer() {
    timerSeconds = 0;
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        timerSeconds++;
        const m = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
        const s = String(timerSeconds % 60).padStart(2, '0');
        document.getElementById('timerDisplay').textContent = m + ':' + s;
    }, 1000);
}

function stopTimer() {
    if (timerInterval) { clearInterval(timerInterval); timerInterval = null; }
}

function updateDimIndicators(dim) {
    document.querySelectorAll('.dim-indicator').forEach(el => {
        if (el.dataset.dim === dim) {
            el.style.background = dimInfo[dim].color + '15';
            el.style.color = dimInfo[dim].color;
        } else {
            el.style.background = 'transparent';
            el.style.color = 'var(--text-3)';
        }
    });
}

function renderQuestion() {
    const q = questions[currentIndex];
    const dim = dimInfo[q.dimension];
    document.getElementById('dimBadge').innerHTML = '<i class="bi bi-tag-fill" style="font-size:0.7rem;"></i> ' + dim.label;
    document.getElementById('dimBadge').style.background = dim.color + '12';
    document.getElementById('dimBadge').style.color = dim.color;
    document.getElementById('currentNum').textContent = currentIndex + 1;
    document.getElementById('qNum').textContent = currentIndex + 1;
    document.getElementById('qText').textContent = q.question_text;
    document.getElementById('optA').textContent = q.option_a;
    document.getElementById('optB').textContent = q.option_b;
    document.getElementById('progressBar').style.width = ((currentIndex + 1) / questions.length * 100) + '%';
    updateDimIndicators(q.dimension);
    document.getElementById('prevBtn').classList.toggle('d-none', currentIndex === 0);

    const selected = answers[q.id];
    const btns = document.querySelectorAll('.option-btn');
    btns.forEach((btn, i) => {
        btn.classList.remove('selected');
        btn.style.borderColor = 'var(--border-2)';
        btn.style.background = 'var(--bg-card)';
        btn.style.color = 'var(--text-1)';
        btn.style.boxShadow = 'none';
        btn.querySelector('span:first-child').style.background = 'var(--bg-3)';
        btn.querySelector('span:first-child').style.color = i === 0 ? 'var(--primary)' : 'var(--rose)';
    });
    if (selected) {
        const idx = selected === 'a' ? 0 : 1;
        btns[idx].classList.add('selected');
        btns[idx].style.borderColor = dim.color;
        btns[idx].style.background = dim.color + '08';
        btns[idx].style.boxShadow = '0 4px 16px ' + dim.color + '18';
        btns[idx].querySelector('span:first-child').style.background = dim.color;
        btns[idx].querySelector('span:first-child').style.color = '#fff';
    }

    const nextBtn = document.getElementById('nextBtn');
    nextBtn.classList.toggle('d-none', !selected);
    nextBtn.innerHTML = currentIndex === questions.length - 1
        ? '提交测试 <i class="bi bi-check-lg ms-1"></i>'
        : '下一题 <i class="bi bi-arrow-right ms-1"></i>';

    const card = document.getElementById('questionCard');
    card.style.animation = 'none';
    card.offsetHeight;
    card.style.animation = 'fadeIn 0.35s ease';
}

function selectAnswer(choice) {
    const q = questions[currentIndex];
    answers[q.id] = choice;
    const btns = document.querySelectorAll('.option-btn');
    const dim = dimInfo[q.dimension];
    btns.forEach((btn, i) => {
        btn.classList.remove('selected');
        btn.style.borderColor = 'var(--border-2)';
        btn.style.background = 'var(--bg-card)';
        btn.style.color = 'var(--text-1)';
        btn.style.boxShadow = 'none';
        btn.querySelector('span:first-child').style.background = 'var(--bg-3)';
        btn.querySelector('span:first-child').style.color = i === 0 ? 'var(--primary)' : 'var(--rose)';
    });
    const idx = choice === 'a' ? 0 : 1;
    btns[idx].classList.add('selected');
    btns[idx].style.borderColor = dim.color;
    btns[idx].style.background = dim.color + '08';
    btns[idx].style.boxShadow = '0 4px 16px ' + dim.color + '18';
    btns[idx].querySelector('span:first-child').style.background = dim.color;
    btns[idx].querySelector('span:first-child').style.color = '#fff';

    const nextBtn = document.getElementById('nextBtn');
    nextBtn.classList.remove('d-none');
    nextBtn.innerHTML = currentIndex === questions.length - 1
        ? '提交测试 <i class="bi bi-check-lg ms-1"></i>'
        : '下一题 <i class="bi bi-arrow-right ms-1"></i>';
    if (currentIndex < questions.length - 1) setTimeout(() => nextQuestion(), 380);
}

function prevQuestion() { if (currentIndex > 0) { currentIndex--; renderQuestion(); } }
function nextQuestion() {
    if (currentIndex === questions.length - 1) { submitTest(); }
    else { currentIndex++; renderQuestion(); }
}

function submitTest() {
    var unanswered = questions.filter(function(q) { return !answers[q.id]; });
    if (unanswered.length > 0) {
        var idx = questions.indexOf(unanswered[0]);
        currentIndex = idx; renderQuestion();
        showToast('还有 ' + unanswered.length + ' 题未作答，已跳转到第 ' + (idx+1) + ' 题', 'info');
        return;
    }
    document.getElementById('testArea').style.display = 'none';
    stopTimer();
    document.getElementById('testSubmitting').style.display = 'block';
    var formData = new URLSearchParams();
    formData.append('action', 'submit_test');
    formData.append('name', '');
    formData.append('email', userEmail);
    formData.append('answers', JSON.stringify(answers));
    fetch('api.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:formData.toString()})
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) { window.location.href = 'result.php?cert=' + encodeURIComponent(res.data.certificate_no); }
            else { showToast(res.message, 'error'); document.getElementById('testSubmitting').style.display='none'; document.getElementById('testArea').style.display='block'; }
        })
        .catch(function() { showToast('网络错误，请重试', 'error'); document.getElementById('testSubmitting').style.display='none'; document.getElementById('testArea').style.display='block'; });
}

document.querySelectorAll('.option-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() { if (!this.classList.contains('selected')) { this.style.borderColor='var(--primary)'; this.style.transform='translateY(-1px)'; } });
    btn.addEventListener('mouseleave', function() { if (!this.classList.contains('selected')) { this.style.borderColor='var(--border-2)'; this.style.transform=''; } });
});

document.addEventListener('keydown', function(e) {
    if (document.getElementById('testArea').style.display === 'none') return;
    if (e.key==='1'||e.key==='a'||e.key==='A') selectAnswer('a');
    if (e.key==='2'||e.key==='b'||e.key==='B') selectAnswer('b');
    if (e.key==='ArrowLeft') prevQuestion();
    if ((e.key==='ArrowRight'||e.key==='Enter') && answers[questions[currentIndex]?.id]) nextQuestion();
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
