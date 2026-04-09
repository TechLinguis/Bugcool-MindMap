</div><!-- /.page-wrap -->
</main><!-- /.admin-main -->

<script src="../assets/js/bootstrap.bundle.min.js" defer></script>
<script>
function showFlash(msg, type='ok'){
  const el = document.getElementById('flash-msg');
  if(!el) return;
  el.className = 'alert alert-' + type;
  el.innerHTML = `<i class="bi bi-${type==='ok'?'check-circle-fill':'exclamation-triangle-fill'}"></i> ${msg}`;
  el.style.display = 'flex';
  setTimeout(()=>{ el.style.opacity='0'; setTimeout(()=>{ el.style.display='none'; el.style.opacity='1'; },400); }, 3500);
}
function toggleAll(src){
  document.querySelectorAll('.row-check').forEach(cb=>cb.checked=src.checked);
}
function getCheckedIds(){
  return [...document.querySelectorAll('.row-check:checked')].map(cb=>cb.value);
}
// 响应式侧边栏
document.addEventListener('DOMContentLoaded', ()=>{
  const sb = document.getElementById('sidebar');
  if(!sb) return;
  if(window.innerWidth <= 900){
    document.querySelector('.topbar-left')?.insertAdjacentHTML('afterbegin',
      '<button onclick="document.getElementById(\'sidebar\').classList.toggle(\'open\')" style="background:none;border:none;color:var(--t2);font-size:1.2rem;cursor:pointer;padding:0 8px 0 0"><i class="bi bi-list"></i></button>'
    );
  }
});
</script>
</body>
</html>
