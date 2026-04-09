<?php $pageTitle = '证书查询'; include_once __DIR__ . '/includes/header.php'; ?>

<style>
/* ======== 查询页动画 ======== */
@keyframes queryFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
@keyframes queryPulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}
@keyframes resultSlideIn {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes iconSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* 页面图标浮动 */
.query-icon-float {
    animation: queryFloat 3s ease-in-out infinite;
}

/* 背景光晕呼吸 */
.query-glow {
    animation: queryPulse 6s ease-in-out infinite;
}
.query-glow:nth-child(2) { animation-delay: -3s; animation-duration: 8s; }

/* 搜索加载旋转 */
.query-loading-icon {
    animation: iconSpin 1s linear infinite;
}

/* 结果卡片入场 */
.query-result-card {
    opacity: 0;
    animation: resultSlideIn 0.5s ease forwards;
}

/* Tab 切换容器 */
.query-tab-container {
    background: var(--bg-3);
}

/* Tab 按钮 */
.query-tab-btn {
    color: var(--text-3);
    background: transparent;
}
.query-tab-btn.active {
    color: #fff;
    background: linear-gradient(135deg, var(--primary-dim), var(--rose));
    box-shadow: 0 4px 12px var(--primary-glow);
}
.query-tab-btn:not(.active):hover {
    color: var(--text-1);
    background: var(--bg-4);
}

/* 输入框容器 */
.query-input-group .input-group-text {
    background: var(--bg-3);
    border-color: var(--border-3);
}
.query-input-group .form-control {
    border-color: var(--border-3);
    background: var(--bg-input);
    color: var(--text-1);
}
.query-input-group .form-control::placeholder {
    color: var(--text-4);
}
.query-input-group .form-control:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 4px var(--primary-glass) !important;
    background: var(--bg-input);
}

/* 结果信息面板 */
.query-info-panel {
    background: var(--bg-3);
    border-radius: 14px;
    padding: 16px 20px;
}

/* 查询结果列表项 */
.query-result-item:hover {
    background: var(--bg-card-hover) !important;
}
.query-list-header {
    color: var(--text-1) !important;
}
</style>

<section class="py-5" style="min-height:calc(100vh - 72px);position:relative;overflow:hidden;">
    <!-- 动态背景光晕 -->
    <div style="position:absolute;inset:0;pointer-events:none;">
        <div class="query-glow" style="position:absolute;top:15%;right:10%;width:250px;height:250px;background:radial-gradient(circle,var(--primary-glow),transparent);border-radius:50%;"></div>
        <div class="query-glow" style="position:absolute;bottom:20%;left:5%;width:200px;height:200px;background:radial-gradient(circle,var(--cyan-glow),transparent);border-radius:50%;"></div>
        <!-- 浮动装饰粒子 -->
        <div style="position:absolute;top:30%;left:15%;width:6px;height:6px;border-radius:50%;background:rgba(129,140,248,0.25);animation:queryFloat 5s ease-in-out infinite;animation-delay:-1s;"></div>
        <div style="position:absolute;top:60%;right:20%;width:4px;height:4px;border-radius:50%;background:rgba(34,211,238,0.25);animation:queryFloat 7s ease-in-out infinite;animation-delay:-3s;"></div>
        <div style="position:absolute;top:20%;right:35%;width:8px;height:8px;border-radius:50%;background:rgba(244,114,182,0.2);animation:queryFloat 6s ease-in-out infinite;animation-delay:-2s;"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <!-- 页面标题 -->
                <div class="text-center mb-4 animate-fadeInUp">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 query-icon-float" style="width:80px;height:80px;border-radius:22px;background:linear-gradient(135deg,var(--primary-dim),var(--primary-bright));box-shadow:0 10px 32px var(--primary-glow);transition:transform 0.3s,box-shadow 0.3s;" onmouseover="this.style.boxShadow='0 14px 40px rgba(129,140,248,0.35)'" onmouseout="this.style.boxShadow='0 10px 32px var(--primary-glow)'">
                        <i class="bi bi-search text-white" style="font-size:2rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-2" style="font-size:1.9rem;letter-spacing:-0.3px;color:var(--text-1);">证书查询</h2>
                    <p style="color:var(--text-2);font-weight:300;">通过证书编号查询你的 MBTI 测试结果</p>
                </div>

                <!-- 查询选项卡 -->
                <div class="card-mbti p-4 p-lg-5 mb-4 animate-fadeInUp position-relative overflow-hidden">
                    <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--primary),var(--cyan));"></div>

                    <!-- 按编号查询 -->
                    <div id="tabByNo">
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2" style="font-size:0.88rem;color:var(--text-1);">证书编号</label>
                            <div class="input-group query-input-group">
                                <span class="input-group-text" style="border-radius:14px 0 0 14px;border:2px solid var(--border-3);border-right:none;">
                                    <i class="bi bi-upc" style="color:var(--primary);"></i>
                                </span>
                                <input type="text" id="queryCertNo" class="form-control form-control-lg" placeholder="例如：MBTI-20260405-A1B2C3" style="border-radius:0 14px 14px 0;border:2px solid var(--border-3);padding:14px 18px;font-size:0.95rem;transition:all 0.3s;">
                            </div>
                            <small style="color:var(--text-3);font-size:0.8rem;margin-top:6px;display:block;">证书编号可在测试完成后的结果页面找到</small>
                        </div>
                        <button class="btn w-100 py-3 fw-semibold" style="background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;border-radius:14px;border:none;box-shadow:0 6px 20px var(--primary-glow);font-size:0.98rem;transition:all 0.3s;" id="btn-query-byNo" onclick="queryByNo()" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(129,140,248,0.35)'" onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px var(--primary-glow)'">
                            <i class="bi bi-shield-check me-2"></i>查询证书
                        </button>
                    </div>

                </div>

                <!-- 查询结果区域 -->
                <div id="queryResult" style="display:none;"></div>

            </div>
        </div>
    </div>
</section>

<script>
function doQuery(action, params) {
    var resultEl = document.getElementById('queryResult');
    resultEl.style.display = 'block';
    resultEl.innerHTML = '<div class="text-center py-4"><div class="loading-spinner mx-auto mb-3"></div><p style="color:var(--text-2);">正在查询...</p></div>';

    var formData = new URLSearchParams();
    formData.append('action', action);
    Object.keys(params).forEach(function(k) { formData.append(k, params[k]); });

    fetch('api.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:formData.toString()})
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                renderCertResult(res.data);
            } else {
                resultEl.innerHTML = '<div class="card-mbti p-4 text-center query-result-card"><i class="bi bi-exclamation-circle" style="font-size:2.5rem;color:var(--rose);"></i><p class="fw-semibold mt-3 mb-1" style="color:var(--text-1);">未找到证书</p><p style="color:var(--text-2);font-size:0.9rem;">' + (res.message || '') + '</p></div>';
            }
        })
        .catch(function() {
            resultEl.innerHTML = '<div class="card-mbti p-4 text-center query-result-card"><p style="color:var(--rose);">网络错误，请重试</p></div>';
        });
}

function queryByNo() {
    var certNo = document.getElementById('queryCertNo').value.trim();
    if (!certNo) { showToast('请输入证书编号', 'error'); return; }
    doQuery('query_certificate', { query_type: 'no', certificate_no: certNo });
}

// Enter 键查询
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    queryByNo();
});

// 渲染单条证书结果
function renderCertResult(data) {
    const resultEl = document.getElementById('queryResult');
    resultEl.innerHTML = `
        <div class="card-mbti p-4 p-md-5 query-result-card position-relative overflow-hidden" style="border-top:3px solid ${data.type_color};">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;border-radius:18px;background:${data.type_color}15;">
                    <span style="font-size:2rem;">${data.icon || ''}</span>
                </div>
                <h3 class="fw-bold mb-1" style="color:${data.type_color};font-size:2rem;letter-spacing:3px;">${data.mbti_type}</h3>
                <p class="fw-semibold" style="font-size:1.05rem;color:var(--text-1);">${data.type_name || ''} · ${data.type_nickname || ''}</p>
            </div>

            <div class="query-info-panel">
                <div class="d-flex justify-content-between mb-2" style="font-size:0.9rem;">
                    <span style="color:var(--text-2);">证书编号</span>
                    <span class="fw-semibold" style="letter-spacing:1px;font-size:0.85rem;color:var(--text-1);">${data.certificate_no}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.9rem;">
                    <span style="color:var(--text-2);">测试日期</span>
                    <span class="fw-semibold" style="color:var(--text-1);">${data.created_at}</span>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="result.php?cert=${encodeURIComponent(data.certificate_no)}" class="btn px-5 py-2.5 fw-semibold" style="background:linear-gradient(135deg,var(--primary-dim),var(--rose));color:#fff;border-radius:12px;border:none;box-shadow:0 4px 16px var(--primary-glow);text-decoration:none;transition:all 0.3s;display:inline-block;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                    <i class="bi bi-eye me-2"></i>查看完整结果和证书
                </a>
            </div>
        </div>
    `;
}
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
