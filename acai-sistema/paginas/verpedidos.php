<?php
// paginas/verpedidos.php
// Lista de pedidos do dia com status e detalhes
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<title>Open Açaí – Pedidos</title>
 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
 
<style>
  :root {
    --bg:         #0f0a1a;
    --panel:      #1a1228;
    --card:       #221930;
    --border:     #2e2040;
    --acai:       #7c2d8f;
    --acai-light: #a84bc2;
    --pink:       #e84393;
    --gold:       #f5c842;
    --green:      #2ecc71;
    --red:        #e74c3c;
    --orange:     #e67e22;
    --blue:       #3498db;
    --cream:      #fdf6ee;
    --muted:      #7a6a9a;
  }
 
  body {
    background: var(--bg);
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    min-height: 100vh;
  }
 
  /* ── NAVBAR ── */
  .navbar { background: var(--panel) !important; border-bottom: 2px solid var(--border); }
  .navbar-brand { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--gold) !important; font-size: 1.4rem; }
  .navbar-brand span { color: var(--pink); }
  .nav-link { color: var(--muted) !important; font-size: 0.85rem; font-weight: 500; border-radius: 100px; padding: 0.35rem 0.9rem !important; border: 1.5px solid transparent; transition: all 0.15s; }
  .nav-link:hover { color: var(--cream) !important; border-color: var(--border); }
  .nav-link.active { background: var(--acai) !important; color: #fff !important; border-color: var(--acai); }
 
  /* ── RESUMO DO DIA ── */
  .resumo-card {
    background: var(--panel);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 1.2rem 1.5rem;
    text-align: center;
  }
  .resumo-valor {
    font-family: 'Syne', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--gold);
    display: block;
  }
  .resumo-label {
    font-size: 0.75rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
  }
 
  /* ── FILTROS ── */
  .filtro-btn {
    background: var(--card);
    border: 1.5px solid var(--border);
    color: var(--muted);
    border-radius: 100px;
    padding: 0.4rem 1rem;
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
  }
  .filtro-btn:hover { border-color: var(--acai-light); color: var(--cream); }
  .filtro-btn.active { background: var(--acai); border-color: var(--acai); color: #fff; }
 
  /* ── CARD DE PEDIDO ── */
  .pedido-card {
    background: var(--panel);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: border-color 0.15s;
  }
  .pedido-card:hover { border-color: var(--acai-light); }
 
  .pedido-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    cursor: pointer;
    gap: 0.8rem;
    flex-wrap: wrap;
  }
  .pedido-num {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--cream);
  }
  .pedido-hora { font-size: 0.78rem; color: var(--muted); }
  .pedido-total {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gold);
    margin-left: auto;
  }
 
  /* ── STATUS BADGES ── */
  .badge-status {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    white-space: nowrap;
  }
  .status-aberto      { background: rgba(52,152,219,0.2);  color: var(--blue);   border: 1px solid rgba(52,152,219,0.4);  }
  .status-em_preparo  { background: rgba(230,126,34,0.2);  color: var(--orange); border: 1px solid rgba(230,126,34,0.4);  }
  .status-pronto      { background: rgba(46,204,113,0.2);  color: var(--green);  border: 1px solid rgba(46,204,113,0.4);  }
  .status-impresso    { background: rgba(168,75,194,0.2);  color: var(--acai-light); border: 1px solid rgba(168,75,194,0.4); }
  .status-entregue    { background: rgba(46,204,113,0.15); color: #27ae60;       border: 1px solid rgba(46,204,113,0.3);  }
  .status-cancelado   { background: rgba(231,76,60,0.2);   color: var(--red);    border: 1px solid rgba(231,76,60,0.4);   }
 
  /* ── DETALHES DO PEDIDO ── */
  .pedido-detalhes {
    display: none;
    padding: 0 1.2rem 1.2rem;
    border-top: 1px solid var(--border);
  }
  .pedido-detalhes.aberto { display: block; }
 
  .copo-item {
    background: var(--card);
    border-radius: 12px;
    padding: 0.8rem 1rem;
    margin-top: 0.7rem;
  }
  .copo-titulo {
    font-family: 'Syne', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--cream);
    display: flex;
    justify-content: space-between;
  }
  .copo-adds { font-size: 0.78rem; color: var(--muted); margin-top: 0.3rem; line-height: 1.7; }
  .copo-obs  { font-size: 0.75rem; color: var(--acai-light); font-style: italic; margin-top: 0.2rem; }
 
  /* ── BOTÕES DE STATUS ── */
  .status-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.9rem; }
  .btn-status {
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.4rem 0.9rem;
    border-radius: 100px;
    border: 1.5px solid;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
    background: transparent;
  }
  .btn-status:active { transform: scale(0.96); }
  .btn-preparo  { color: var(--orange); border-color: var(--orange); }
  .btn-preparo:hover  { background: rgba(230,126,34,0.15); }
  .btn-pronto   { color: var(--green);  border-color: var(--green); }
  .btn-pronto:hover   { background: rgba(46,204,113,0.15); }
  .btn-entregue { color: #27ae60; border-color: #27ae60; }
  .btn-entregue:hover { background: rgba(46,204,113,0.1); }
  .btn-cancelar { color: var(--red);    border-color: var(--red); }
  .btn-cancelar:hover { background: rgba(231,76,60,0.15); }
  .btn-reimprimir { color: var(--acai-light); border-color: var(--acai-light); }
  .btn-reimprimir:hover { background: rgba(168,75,194,0.15); }
 
  /* ── ESTADO VAZIO ── */
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--muted);
  }
  .empty-state .ei { font-size: 3rem; opacity: 0.4; display: block; margin-bottom: 0.8rem; }
 
  /* ── LOADING ── */
  .loading-ph { display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 3rem; color: var(--muted); }
  .spinner-sm { width: 20px; height: 20px; border: 2.5px solid var(--border); border-top-color: var(--acai-light); border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
 
  /* ── TOAST ── */
  .toast-container { z-index: 9999; }
  .toast { background: var(--green) !important; color: #fff !important; border: none !important; font-weight: 600; border-radius: 100px !important; }
  .toast.error { background: var(--red) !important; }
 
  /* ── BTN ATUALIZAR ── */
  .btn-refresh {
    background: var(--card);
    border: 1.5px solid var(--border);
    color: var(--muted);
    border-radius: 100px;
    padding: 0.4rem 1rem;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
  }
  .btn-refresh:hover { border-color: var(--acai-light); color: var(--cream); }
</style>
</head>
<body>
 
<!-- NAVBAR -->
<nav class="navbar navbar-expand-md sticky-top px-3 py-0" style="min-height:56px;">
  <a class="navbar-brand me-3" href="#">Open <span>Açaí</span></a>
  <button class="navbar-toggler border-0 ms-auto me-2" type="button"
          data-bs-toggle="collapse" data-bs-target="#navMenu"
          style="color:var(--muted);font-size:1.2rem;">☰</button>
  <div class="collapse navbar-collapse" id="navMenu">
    <ul class="navbar-nav gap-1">
      <li class="nav-item"><a class="nav-link" href="index.php">🧾 Novo Pedido</a></li>
      <li class="nav-item"><a class="nav-link active" href="verpedidos.php">📋 Pedidos</a></li>
      <li class="nav-item"><a class="nav-link" href="estoque.php">📦 Estoque</a></li>
    </ul>
  </div>
</nav>
 
<div class="container-fluid px-3 pt-3 pb-5">
 
  <!-- RESUMO DO DIA -->
  <div class="row g-3 mb-3" id="resumoArea">
    <div class="col-12"><div class="loading-ph"><div class="spinner-sm"></div> Carregando resumo...</div></div>
  </div>
 
  <!-- CABEÇALHO + FILTROS -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:800;">
      Pedidos de Hoje
      <span style="font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:400;color:var(--muted);" id="totalCount"></span>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
      <!-- Filtros de status -->
      <button class="filtro-btn active" onclick="filtrar('todos', this)">Todos</button>
      <button class="filtro-btn" onclick="filtrar('aberto', this)">🔵 Aberto</button>
      <button class="filtro-btn" onclick="filtrar('em_preparo', this)">🟠 Preparo</button>
      <button class="filtro-btn" onclick="filtrar('pronto', this)">🟢 Pronto</button>
      <button class="filtro-btn" onclick="filtrar('entregue', this)">✅ Entregue</button>
      <button class="filtro-btn" onclick="filtrar('cancelado', this)">❌ Cancelado</button>
      <!-- Atualizar -->
      <button class="btn-refresh" onclick="carregarTudo()">↻ Atualizar</button>
    </div>
  </div>
 
  <!-- LISTA DE PEDIDOS -->
  <div id="listaPedidos">
    <div class="loading-ph"><div class="spinner-sm"></div> Carregando pedidos...</div>
  </div>
 
</div>
 
<!-- TOAST -->
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3">
  <div id="toast" class="toast align-items-center border-0 px-4" role="alert">
    <div class="d-flex">
      <div class="toast-body fw-bold" id="toastMsg"></div>
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = '../api.php';
 
let todosPedidos  = [];
let filtroAtual   = 'todos';
let bsToast;
 
document.addEventListener('DOMContentLoaded', () => {
  bsToast = new bootstrap.Toast(document.getElementById('toast'), { delay: 2500 });
  carregarTudo();
  // Auto-atualiza a cada 30 segundos
  setInterval(carregarTudo, 30000);
});
 
// ── CARREGAR TUDO ─────────────────────────────────────────────
async function carregarTudo() {
  await Promise.all([carregarResumo(), carregarPedidos()]);
}
 
async function carregarResumo() {
  try {
    const j = await fetch(`${API}?acao=relatorio_hoje`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    renderResumo(j.dados);
  } catch(e) {
    document.getElementById('resumoArea').innerHTML =
      `<div class="col-12 text-danger small">Erro ao carregar resumo: ${e.message}</div>`;
  }
}
 
async function carregarPedidos() {
  try {
    const j = await fetch(`${API}?acao=pedidos_do_dia`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    todosPedidos = j.dados;
    renderPedidos();
  } catch(e) {
    document.getElementById('listaPedidos').innerHTML =
      `<div class="text-danger small p-3">Erro: ${e.message}</div>`;
  }
}
 
// ── RENDER RESUMO ─────────────────────────────────────────────
function renderResumo(dados) {
  const r = dados.resumo;
  document.getElementById('resumoArea').innerHTML = `
    <div class="col-6 col-md-3">
      <div class="resumo-card">
        <span class="resumo-valor">${r.total_pedidos || 0}</span>
        <span class="resumo-label">Pedidos hoje</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="resumo-card">
        <span class="resumo-valor">R$ ${fmt(r.faturamento_total || 0)}</span>
        <span class="resumo-label">Faturamento</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="resumo-card">
        <span class="resumo-valor">R$ ${fmt(r.ticket_medio || 0)}</span>
        <span class="resumo-label">Ticket médio</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="resumo-card">
        <span class="resumo-valor">${dados.mais_vendidos[0]?.icone || '—'} ${dados.mais_vendidos[0]?.nome || '—'}</span>
        <span class="resumo-label">Mais vendido</span>
      </div>
    </div>
  `;
}
 
// ── RENDER PEDIDOS ────────────────────────────────────────────
function renderPedidos() {
  const lista  = document.getElementById('listaPedidos');
  const count  = document.getElementById('totalCount');
 
  let pedidos = filtroAtual === 'todos'
    ? todosPedidos
    : todosPedidos.filter(p => p.status === filtroAtual);
 
  count.textContent = `· ${pedidos.length} pedido(s)`;
 
  if (!pedidos.length) {
    lista.innerHTML = `
      <div class="empty-state">
        <span class="ei">🫙</span>
        <p>${filtroAtual === 'todos' ? 'Nenhum pedido hoje ainda.' : `Nenhum pedido com status "${labelStatus(filtroAtual)}".`}</p>
      </div>
    `;
    return;
  }
 
  lista.innerHTML = `<div class="d-flex flex-column gap-2">
    ${pedidos.map(p => cardPedido(p)).join('')}
  </div>`;
}
 
// ── CARD DE PEDIDO ────────────────────────────────────────────
function cardPedido(p) {
  const hora  = new Date(p.criado_em).toLocaleTimeString('pt-BR', { hour:'2-digit', minute:'2-digit' });
  const copos = p.itens?.length || 0;
 
  const detalhes = (p.itens || []).map(item => `
    <div class="copo-item">
      <div class="copo-titulo">
        <span>🥤 ${item.tamanho_nome} ${item.ml}ml</span>
        <span>R$ ${fmt(item.subtotal)}</span>
      </div>
      ${item.adicionais?.length ? `
        <div class="copo-adds">
          ${item.adicionais.map(a => `${a.icone} ${a.nome} × ${a.quantidade}`).join(' &nbsp;·&nbsp; ')}
        </div>
      ` : ''}
      ${item.observacao ? `<div class="copo-obs">"${item.observacao}"</div>` : ''}
    </div>
  `).join('');
 
  // Botões de ação conforme status atual
  const acoes = botoesStatus(p);
 
  return `
    <div class="pedido-card" id="card-${p.id}">
      <div class="pedido-header" onclick="toggleDetalhes(${p.id})">
        <span class="pedido-num">#${String(p.numero).padStart(3,'0')}</span>
        <span class="pedido-hora">${hora} · ${copos} copo(s)</span>
        <span class="pedido-total">R$ ${fmt(p.total)}</span>
        <span class="badge-status status-${p.status}">${labelStatus(p.status)}</span>
        <span style="color:var(--muted);font-size:0.9rem;">▾</span>
      </div>
      <div class="pedido-detalhes" id="det-${p.id}">
        <div class="pt-2">${detalhes}</div>
        <div class="status-actions">${acoes}</div>
      </div>
    </div>
  `;
}
 
function botoesStatus(p) {
  if (p.status === 'cancelado' || p.status === 'entregue') return '';
 
  let btns = '';
 
  if (p.status === 'aberto' || p.status === 'impresso') {
    btns += `<button class="btn-status btn-preparo" onclick="mudarStatus(${p.id},'em_preparo')">🟠 Em Preparo</button>`;
  }
  if (p.status === 'em_preparo') {
    btns += `<button class="btn-status btn-pronto" onclick="mudarStatus(${p.id},'pronto')">🟢 Pronto</button>`;
  }
  if (p.status === 'pronto') {
    btns += `<button class="btn-status btn-entregue" onclick="mudarStatus(${p.id},'entregue')">✅ Entregue</button>`;
  }
 
  btns += `<button class="btn-status btn-reimprimir" onclick="reimprimir(${p.id})">🖨️ Reimprimir</button>`;
 
  if (p.status !== 'entregue') {
    btns += `<button class="btn-status btn-cancelar" onclick="cancelar(${p.id})">❌ Cancelar</button>`;
  }
 
  return btns;
}
 
// ── TOGGLE DETALHES ───────────────────────────────────────────
function toggleDetalhes(id) {
  const det = document.getElementById(`det-${id}`);
  det.classList.toggle('aberto');
}
 
// ── FILTRAR ───────────────────────────────────────────────────
function filtrar(status, btn) {
  filtroAtual = status;
  document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  renderPedidos();
}
 
// ── MUDAR STATUS ──────────────────────────────────────────────
async function mudarStatus(id, status) {
  try {
    const j = await fetch(`${API}?acao=atualizar_status`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pedido_id: id, status }),
    }).then(r => r.json());
 
    if (!j.ok) throw new Error(j.mensagem);
 
    // Atualiza localmente sem recarregar tudo
    const p = todosPedidos.find(p => p.id === id);
    if (p) p.status = status;
    renderPedidos();
 
    // Reabre o card que estava aberto
    setTimeout(() => {
      const det = document.getElementById(`det-${id}`);
      if (det) det.classList.add('aberto');
    }, 50);
 
    toast(`✓ Status atualizado: ${labelStatus(status)}`);
  } catch(e) {
    toast('❌ ' + e.message, true);
  }
}
 
// ── CANCELAR ──────────────────────────────────────────────────
async function cancelar(id) {
  const p = todosPedidos.find(p => p.id === id);
  if (!confirm(`Cancelar pedido #${String(p.numero).padStart(3,'0')}? O estoque será devolvido.`)) return;
 
  try {
    const j = await fetch(`${API}?acao=cancelar`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pedido_id: id }),
    }).then(r => r.json());
 
    if (!j.ok) throw new Error(j.mensagem);
 
    if (p) p.status = 'cancelado';
    renderPedidos();
    toast('🗑 Pedido cancelado e estoque devolvido.');
  } catch(e) {
    toast('❌ ' + e.message, true);
  }
}
 
// ── REIMPRIMIR ────────────────────────────────────────────────
function reimprimir(id) {
  const p = todosPedidos.find(p => p.id === id);
  if (!p) return;
 
  const numFmt = String(p.numero).padStart(3,'0');
  const agora  = new Date().toLocaleString('pt-BR');
 
  const linhas = (p.itens || []).map((item, idx) => `
    <div class="linha"><span>${idx+1}. ${item.tamanho_nome} ${item.ml}ml</span><span>R$ ${fmt(item.subtotal)}</span></div>
    ${(item.adicionais || []).map(a => `<div class="add">${a.icone} ${a.nome} × ${a.quantidade}</div>`).join('')}
    ${item.observacao ? `<div class="obs">Obs: ${item.observacao}</div>` : ''}
  `).join('');
 
  const html = `<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <title>Pedido #${numFmt}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Courier New',monospace; font-size:13px; width:80mm; padding:8px; color:#000; }
    .header { text-align:center; font-size:16px; font-weight:bold; border-bottom:1px dashed #000; padding-bottom:6px; margin-bottom:6px; }
    .header small { display:block; font-size:11px; font-weight:normal; margin-top:3px; }
    .linha { display:flex; justify-content:space-between; margin-top:4px; }
    .add  { padding-left:10px; color:#444; font-size:12px; }
    .obs  { padding-left:10px; color:#666; font-style:italic; font-size:11px; }
    .total { display:flex; justify-content:space-between; font-weight:bold; font-size:15px; border-top:1px dashed #000; margin-top:8px; padding-top:6px; }
    .rodape { text-align:center; margin-top:10px; font-size:11px; color:#666; border-top:1px dashed #000; padding-top:6px; }
  </style>
</head>
<body>
  <div class="header">🍇 OPEN AÇAÍ<small>Pedido #${numFmt} · ${agora} [REIMPRESSÃO]</small></div>
  ${linhas}
  <div class="total"><span>TOTAL</span><span>R$ ${fmt(p.total)}</span></div>
  <div class="rodape">Obrigado! 💜</div>
</body>
</html>`;
 
  let iframe = document.getElementById('printFrame');
  if (iframe) iframe.remove();
  iframe = document.createElement('iframe');
  iframe.id = 'printFrame';
  iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:0;height:0;border:none;';
  document.body.appendChild(iframe);
  iframe.contentDocument.open();
  iframe.contentDocument.write(html);
  iframe.contentDocument.close();
  iframe.onload = () => {
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
  };
 
  toast(`🖨️ Reimprimindo pedido #${numFmt}...`);
}
 
// ── UTILS ─────────────────────────────────────────────────────
function labelStatus(s) {
  const map = {
    aberto:     '🔵 Aberto',
    em_preparo: '🟠 Em Preparo',
    pronto:     '🟢 Pronto',
    impresso:   '🖨️ Impresso',
    entregue:   '✅ Entregue',
    cancelado:  '❌ Cancelado',
  };
  return map[s] || s;
}
 
function fmt(v) { return parseFloat(v || 0).toFixed(2).replace('.', ','); }
 
function toast(msg, isError = false) {
  const el = document.getElementById('toast');
  el.classList.toggle('error', isError);
  document.getElementById('toastMsg').textContent = msg;
  bsToast.show();
}
</script>
</body>
</html> 