<?php
// paginas/index.php
// PDV Touch — Bootstrap 5 — responsivo de verdade
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<title>Open Açaí – PDV</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>

<style>
  /* ── TEMA ── */
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
    --cream:      #fdf6ee;
    --muted:      #7a6a9a;
  }

  body {
    background: var(--bg);
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    min-height: 100vh;
  }

  /* ── TOPBAR ── */
  .navbar {
    background: var(--panel) !important;
    border-bottom: 2px solid var(--border);
  }
  .navbar-brand {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    color: var(--gold) !important;
    font-size: 1.4rem;
  }
  .navbar-brand span { color: var(--pink); }
  .nav-link {
    color: var(--muted) !important;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 100px;
    padding: 0.35rem 0.9rem !important;
    border: 1.5px solid transparent;
    transition: all 0.15s;
  }
  .nav-link:hover { color: var(--cream) !important; border-color: var(--border); }
  .nav-link.active { background: var(--acai) !important; color: #fff !important; border-color: var(--acai); }
  #clock { font-size: 0.9rem; color: var(--muted); }

  /* ── SEÇÕES ── */
  .section-card {
    background: var(--panel);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 1.2rem;
  }
  .step-badge {
    width: 32px; height: 32px;
    background: var(--acai);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 0.9rem;
    color: #fff;
    flex-shrink: 0;
  }
  .step-title {
    font-family: 'Syne', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cream);
  }

  /* ── BOTÕES TAMANHO ── */
  .size-btn {
    background: var(--card);
    border: 2.5px solid var(--border);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.15s;
    color: var(--cream);
    width: 100%;
    padding: 1.2rem 0.5rem;
    text-align: center;
    user-select: none;
    position: relative;
  }
  .size-btn:active { transform: scale(0.97); }
  .size-btn.selected {
    border-color: var(--acai-light);
    background: rgba(124,45,143,0.25);
    box-shadow: 0 0 0 3px rgba(168,75,194,0.15);
  }
  .size-btn .check-badge {
    position: absolute; top: 8px; right: 10px;
    background: var(--acai-light); color: #fff;
    border-radius: 50%; width: 22px; height: 22px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; font-weight: 800;
    opacity: 0; transition: opacity 0.15s;
  }
  .size-btn.selected .check-badge { opacity: 1; }
  .size-btn .size-icon  { font-size: 2.2rem; display: block; line-height: 1; margin-bottom: 0.4rem; }
  .size-btn .size-name  { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; display: block; }
  .size-btn .size-ml    { font-size: 0.78rem; color: var(--muted); display: block; }
  .size-btn .size-price { font-size: 1.1rem; font-weight: 700; color: var(--gold); display: block; margin-top: 0.2rem; }

  /* ── BOTÕES ADICIONAIS ── */
  .add-btn {
    background: var(--card);
    border: 2.5px solid var(--border);
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.15s;
    color: var(--cream);
    width: 100%;
    padding: 0.9rem 0.3rem 0.7rem;
    text-align: center;
    user-select: none;
    position: relative;
  }
  .add-btn:active { transform: scale(0.95); }
  .add-btn.selected {
    border-color: var(--pink);
    background: rgba(232,67,147,0.18);
    box-shadow: 0 0 0 3px rgba(232,67,147,0.1);
  }
  .add-btn.sem-estoque { opacity: 0.28; pointer-events: none; }
  .add-btn .check-badge {
    position: absolute; top: 4px; right: 6px;
    background: var(--pink); color: #fff;
    border-radius: 100px; padding: 0.08rem 0.35rem;
    font-size: 0.6rem; font-weight: 800;
    opacity: 0; transition: opacity 0.15s;
  }
  .add-btn.selected .check-badge { opacity: 1; }
  .add-btn .add-icon  { font-size: 1.7rem; display: block; line-height: 1; margin-bottom: 0.3rem; }
  .add-btn .add-name  { font-size: 0.72rem; font-weight: 600; display: block; line-height: 1.2; }
  .add-btn .add-price { font-size: 0.65rem; color: var(--muted); display: block; margin-top: 0.15rem; }
  .add-btn .add-zero  {
    position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
    font-size: 0.55rem; color: var(--red); font-weight: 700; white-space: nowrap;
  }

  /* ── QUANTIDADE ── */
  .qty-btn {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: var(--card);
    border: 2px solid var(--border);
    color: var(--cream);
    font-size: 1.4rem; font-weight: 700;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s; flex-shrink: 0;
  }
  .qty-btn:active { background: var(--acai); transform: scale(0.9); }
  .qty-num {
    font-family: 'Syne', sans-serif;
    font-size: 1.8rem; font-weight: 800;
    min-width: 2rem; text-align: center;
  }

  /* ── OBS ── */
  .obs-input {
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: 12px;
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    resize: none;
    width: 100%;
    padding: 0.8rem 1rem;
    outline: none;
    transition: border-color 0.2s;
  }
  .obs-input:focus  { border-color: var(--acai-light); }
  .obs-input::placeholder { color: var(--muted); }

  /* ── BOTTOMBAR ── */
  .bottombar {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: var(--panel);
    border-top: 2px solid var(--border);
    padding: 0.75rem 1rem;
    z-index: 100;
  }
  .ticket-empty { font-size: 0.82rem; color: var(--muted); font-style: italic; }
  .ticket-total-line {
    font-family: 'Syne', sans-serif;
    font-size: 1rem; font-weight: 800; color: var(--gold);
  }
  .ticket-chip {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 0.18rem 0.65rem;
    font-size: 0.72rem;
    color: var(--cream);
    white-space: nowrap;
    display: inline-flex; align-items: center; gap: 0.3rem;
  }
  .ticket-chip .rx { color: var(--red); font-weight: 800; cursor: pointer; padding-left: 0.2rem; font-size: 0.8em; }

  .btn-add-copo {
    background: var(--card);
    border: 2px solid var(--acai-light);
    color: var(--acai-light);
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    font-family: 'Syne', sans-serif;
    font-size: 0.9rem; font-weight: 800;
    cursor: pointer; white-space: nowrap;
    transition: all 0.15s;
  }
  .btn-add-copo:disabled { opacity: 0.3; cursor: not-allowed; }
  .btn-add-copo:not(:disabled):active { transform: scale(0.97); }

  .btn-confirmar {
    background: linear-gradient(135deg, var(--acai), var(--pink));
    border: none; color: #fff;
    border-radius: 12px;
    padding: 0.6rem 1.4rem;
    font-family: 'Syne', sans-serif;
    font-size: 0.95rem; font-weight: 800;
    cursor: pointer; white-space: nowrap;
    box-shadow: 0 4px 18px rgba(232,67,147,0.3);
    transition: all 0.15s;
  }
  .btn-confirmar:disabled { opacity: 0.3; cursor: not-allowed; }
  .btn-confirmar:not(:disabled):active { transform: scale(0.97); }

  .btn-limpar {
    background: none;
    border: 1.5px solid rgba(231,76,60,0.35);
    color: var(--red);
    border-radius: 12px;
    padding: 0.6rem 0.9rem;
    font-size: 1.1rem; cursor: pointer;
    transition: all 0.15s;
  }
  .btn-limpar:disabled { opacity: 0.3; cursor: not-allowed; }
  .btn-limpar:not(:disabled):hover { background: rgba(231,76,60,0.1); }

  /* padding pra não ficar atrás da bottombar */
  .content-wrap { padding-bottom: 110px; }

  /* ── LOADING ── */
  .loading-ph {
    display: flex; align-items: center; justify-content: center;
    gap: 0.5rem; padding: 2rem; color: var(--muted); font-size: 0.9rem;
  }
  .spinner-sm {
    width: 18px; height: 18px;
    border: 2px solid var(--border);
    border-top-color: var(--acai-light);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── MODAL ── */
  .modal-content {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--cream);
  }
  .modal-title { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--gold); }
  .cupom {
    background: var(--card);
    border: 1px dashed var(--border);
    border-radius: 12px;
    padding: 1rem;
    font-size: 0.85rem;
    line-height: 1.9;
  }
  .cupom-header {
    text-align: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 0.95rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px dashed var(--border);
    margin-bottom: 0.5rem;
  }
  .cupom-header small { display: block; font-size: 0.75rem; color: var(--muted); font-weight: 400; font-family: 'DM Sans', sans-serif; }
  .cupom-item  { display: flex; justify-content: space-between; }
  .cupom-adds  { color: var(--muted); font-size: 0.8rem; padding-left: 0.8rem; }
  .cupom-obs   { color: var(--acai-light); font-size: 0.78rem; padding-left: 0.8rem; font-style: italic; }
  .cupom-total { display: flex; justify-content: space-between; font-family: 'Syne', sans-serif; font-weight: 800; color: var(--gold); padding-top: 0.5rem; border-top: 1px dashed var(--border); margin-top: 0.5rem; }

  .btn-imprimir { background: var(--green); border: none; color: #fff; font-weight: 700; border-radius: 10px; padding: 0.7rem 1.4rem; }
  .btn-imprimir:disabled { opacity: 0.5; }
  .btn-voltar   { background: var(--card); border: 1.5px solid var(--border); color: var(--cream); font-weight: 600; border-radius: 10px; padding: 0.7rem 1.4rem; }

  /* ── TOAST ── */
  .toast-container { bottom: 115px !important; }
  .toast { background: var(--green) !important; color: #fff !important; border: none !important; font-weight: 600; border-radius: 100px !important; }
  .toast.error { background: var(--red) !important; }

  /* impressão feita via nova janela — sem @media print necessário */
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
    <ul class="navbar-nav gap-1 me-auto">
      <li class="nav-item"><a class="nav-link active" href="index.php">🧾 Novo Pedido</a></li>
      <li class="nav-item"><a class="nav-link" href="verpedidos.php">📋 Pedidos</a></li>
      <li class="nav-item"><a class="nav-link" href="estoque.php">📦 Estoque</a></li>
    </ul>
    <span id="clock" class="d-none d-md-block"></span>
  </div>
</nav>

<!-- CONTEÚDO -->
<div class="container-fluid content-wrap px-3 pt-3">

  <!-- 1. TAMANHO -->
  <div class="section-card mb-3">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="step-badge">1</div>
      <span class="step-title">Tamanho do Copo</span>
    </div>
    <div class="row g-3" id="sizeGrid">
      <div class="col-12"><div class="loading-ph"><div class="spinner-sm"></div> Carregando...</div></div>
    </div>
  </div>

  <!-- 2. ADICIONAIS -->
  <div class="section-card mb-3">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="step-badge">2</div>
      <span class="step-title">Adicionais</span>
    </div>
    <div class="row g-2" id="addGrid">
      <div class="col-12"><div class="loading-ph"><div class="spinner-sm"></div> Carregando...</div></div>
    </div>
  </div>

  <!-- 3. QTD + OBS -->
  <div class="section-card mb-3">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="step-badge">3</div>
      <span class="step-title">Quantidade & Observação</span>
    </div>
    <div class="row g-3 align-items-start">
      <div class="col-auto">
        <div class="d-flex flex-column align-items-center gap-2">
          <span style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);font-weight:600;">Copos</span>
          <div class="d-flex align-items-center gap-3">
            <button class="qty-btn" onclick="changeQty(-1)">−</button>
            <span class="qty-num" id="qtyNum">1</span>
            <button class="qty-btn" onclick="changeQty(1)">+</button>
          </div>
        </div>
      </div>
      <div class="col">
        <label class="d-block mb-1" style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);font-weight:600;">Observação (opcional)</label>
        <textarea class="obs-input" id="obsInput" rows="3"
                  placeholder="Ex: sem granola, mais mel, bem gelado..."></textarea>
      </div>
    </div>
  </div>

</div><!-- /.container-fluid -->

<!-- BOTTOMBAR FIXO -->
<div class="bottombar">
  <div class="d-flex align-items-center gap-2 flex-wrap mb-1" id="ticketMini">
    <span class="ticket-empty">Nenhum copo adicionado ainda...</span>
  </div>
  <div class="d-flex align-items-center gap-2">
    <button class="btn-add-copo"  id="btnAdd"     onclick="adicionarCopo()" disabled>＋ Adicionar</button>
    <button class="btn-confirmar" id="btnConfirm" onclick="abrirModal()"    disabled>Confirmar 🖨️</button>
    <button class="btn-limpar"    id="btnClear"   onclick="limparTudo()"    disabled>🗑</button>
  </div>
</div>

<!-- MODAL CUPOM -->
<div class="modal fade" id="modalCupom" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title">✅ Pedido Pronto!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-2">
        <p style="font-size:0.85rem;color:var(--muted);">Confira e mande pra impressora.</p>
        <div class="cupom" id="cupomPreview"></div>
      </div>
      <div class="modal-footer border-0 pt-0 gap-2">
        <button class="btn-voltar"   data-bs-dismiss="modal">← Voltar</button>
        <button class="btn-imprimir" id="btnPrint" onclick="confirmarEImprimir()">🖨️ Imprimir</button>
      </div>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-container position-fixed start-50 translate-middle-x p-3" style="bottom:115px;z-index:200;">
  <div id="toast" class="toast align-items-center border-0 px-4" role="alert">
    <div class="d-flex">
      <div class="toast-body fw-bold" id="toastMsg"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = '../api.php';

let tamanhos = [], adicionais = [];
let tamSel = null, addsSel = [], quantidade = 1;
let itensPedido = [], itemCounter = 0;
let bsModal, bsToast;

document.addEventListener('DOMContentLoaded', () => {
  bsModal = new bootstrap.Modal(document.getElementById('modalCupom'));
  bsToast = new bootstrap.Toast(document.getElementById('toast'), { delay: 2500 });

  atualizarRelogio();
  setInterval(atualizarRelogio, 1000);
  carregarTamanhos();
  carregarAdicionais();
});

function atualizarRelogio() {
  const el = document.getElementById('clock');
  if (el) el.textContent = new Date().toLocaleTimeString('pt-BR', { hour:'2-digit', minute:'2-digit' });
}

/* ── API ── */
async function carregarTamanhos() {
  try {
    const j = await fetch(`${API}?acao=tamanhos`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    tamanhos = j.dados;
    renderTamanhos();
  } catch(e) {
    document.getElementById('sizeGrid').innerHTML =
      `<div class="col-12 text-danger small p-2">Erro: ${e.message}</div>`;
  }
}

async function carregarAdicionais() {
  try {
    const j = await fetch(`${API}?acao=adicionais`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    adicionais = j.dados;
    renderAdicionais();
  } catch(e) {
    document.getElementById('addGrid').innerHTML =
      `<div class="col-12 text-danger small p-2">Erro: ${e.message}</div>`;
  }
}

/* ── RENDER ── */
function renderTamanhos() {
  const icons = { 250:'🥤', 400:'🍺' };
  document.getElementById('sizeGrid').innerHTML = tamanhos.map(t => `
    <div class="col-6">
      <div class="size-btn ${tamSel?.id===t.id?'selected':''}" onclick="selecionarTamanho(${t.id})">
        <span class="check-badge">✓</span>
        <span class="size-icon">${icons[t.ml]||'🥤'}</span>
        <span class="size-name">${t.nome}</span>
        <span class="size-ml">${t.ml} ml</span>
        <span class="size-price">R$ ${fmt(t.preco)}</span>
      </div>
    </div>
  `).join('');
}

function renderAdicionais() {
  document.getElementById('addGrid').innerHTML = adicionais.map(a => `
    <div class="col-3 col-sm-3 col-md-2">
      <div class="add-btn ${a.sem_estoque?'sem-estoque':''} ${addsSel.includes(a.id)?'selected':''}"
           onclick="toggleAdicional(${a.id})">
        <span class="check-badge">✓</span>
        <span class="add-icon">${a.icone}</span>
        <span class="add-name">${a.nome}</span>
        <span class="add-price">+R$ ${fmt(a.preco)}</span>
        ${a.sem_estoque?'<span class="add-zero">SEM ESTOQUE</span>':''}
      </div>
    </div>
  `).join('');
}

/* ── INTERAÇÕES ── */
function selecionarTamanho(id) {
  tamSel = tamanhos.find(t => t.id === id) || null;
  renderTamanhos();
  document.getElementById('btnAdd').disabled = !tamSel;
}

function toggleAdicional(id) {
  const i = addsSel.indexOf(id);
  if (i === -1) addsSel.push(id); else addsSel.splice(i, 1);
  renderAdicionais();
}

function changeQty(d) {
  quantidade = Math.max(1, Math.min(10, quantidade + d));
  document.getElementById('qtyNum').textContent = quantidade;
}

function adicionarCopo() {
  if (!tamSel) return;
  const addsObj   = adicionais.filter(a => addsSel.includes(a.id));
  const addsTotal = addsObj.reduce((s,a) => s+a.preco, 0);
  const obs       = document.getElementById('obsInput').value.trim();

  for (let i = 0; i < quantidade; i++) {
    itemCounter++;
    itensPedido.push({
      _id: itemCounter,
      tamanho_id: tamSel.id,
      label: `${tamSel.nome} ${tamSel.ml}ml`,
      preco_base: tamSel.preco,
      adds: addsObj.map(a => ({id:a.id, nome:a.nome, preco:a.preco})),
      addsTotal,
      total: tamSel.preco + addsTotal,
      observacao: obs,
    });
  }

  tamSel = null; addsSel = []; quantidade = 1;
  document.getElementById('obsInput').value     = '';
  document.getElementById('qtyNum').textContent = '1';
  document.getElementById('btnAdd').disabled    = true;
  renderTamanhos(); renderAdicionais(); renderTicketMini();
  toast('✓ Copo adicionado!');
}

function renderTicketMini() {
  const mini    = document.getElementById('ticketMini');
  const confirm = document.getElementById('btnConfirm');
  const clear   = document.getElementById('btnClear');

  if (!itensPedido.length) {
    mini.innerHTML = `<span class="ticket-empty">Nenhum copo adicionado ainda...</span>`;
    confirm.disabled = clear.disabled = true;
    return;
  }

  confirm.disabled = clear.disabled = false;
  const total = itensPedido.reduce((s,i) => s+i.total, 0);

  mini.innerHTML = `
    ${itensPedido.map(item => `
      <span class="ticket-chip">
        🥤 ${item.label}
        ${item.adds.length ? `<span style="color:var(--muted)">+${item.adds.length}</span>` : ''}
        <span class="rx" onclick="removerItem(${item._id})">✕</span>
      </span>
    `).join('')}
    <span class="ticket-total-line ms-auto">${itensPedido.length} copo(s) · R$ ${fmt(total)}</span>
  `;
}

function removerItem(id) {
  itensPedido = itensPedido.filter(i => i._id !== id);
  renderTicketMini();
}

/* ── MODAL ── */
function abrirModal() {
  if (!itensPedido.length) return;
  const total = itensPedido.reduce((s,i) => s+i.total, 0);
  document.getElementById('cupomPreview').innerHTML = `
    <div class="cupom-header">🍇 OPEN AÇAÍ<small>${new Date().toLocaleString('pt-BR')}</small></div>
    ${itensPedido.map((item,idx) => `
      <div class="cupom-item"><span>${idx+1}. ${item.label}</span><span>R$ ${fmt(item.preco_base)}</span></div>
      ${item.adds.map(a=>`<div class="cupom-adds">+ ${a.nome} ..... R$ ${fmt(a.preco)}</div>`).join('')}
      ${item.observacao?`<div class="cupom-obs">Obs: ${item.observacao}</div>`:''}
    `).join('')}
    <div class="cupom-total"><span>TOTAL</span><span>R$ ${fmt(total)}</span></div>
  `;
  bsModal.show();
}

/* ── CONFIRMAR + IMPRIMIR ── */
async function confirmarEImprimir() {
  const btn = document.getElementById('btnPrint');
  btn.disabled = true; btn.textContent = '⏳ Salvando...';
  try {
    const res  = await fetch(`${API}?acao=criar_pedido`, {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({
        observacao: '',
        itens: itensPedido.map(item => ({
          tamanho_id: item.tamanho_id,
          observacao: item.observacao,
          adicionais: item.adds.map(a => a.id),
        })),
      }),
    });
    const json = await res.json();
    if (!json.ok) throw new Error(json.mensagem);

    const pedido = json.dados;
    const numFmt = String(pedido.numero).padStart(3,'0');
    const agora  = new Date().toLocaleString('pt-BR');

    // Marca como impresso no banco
    await fetch(`${API}?acao=imprimir`, {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ pedido_id: pedido.pedido_id }),
    });

    // Monta HTML do cupom
    const linhas = itensPedido.map((item, idx) => `
      <div class="linha"><span>${idx+1}. ${item.label}</span><span>R$ ${fmt(item.preco_base)}</span></div>
      ${item.adds.map(a => `<div class="add">+ ${a.nome} ......... R$ ${fmt(a.preco)}</div>`).join('')}
      ${item.observacao ? `<div class="obs">Obs: ${item.observacao}</div>` : ''}
    `).join('');

    const total = itensPedido.reduce((s,i) => s+i.total, 0);

    const html = `<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <title>Pedido #${numFmt}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Courier New', monospace;
      font-size: 13px;
      width: 80mm;
      padding: 8px;
      color: #000;
    }
    .header {
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      border-bottom: 1px dashed #000;
      padding-bottom: 6px;
      margin-bottom: 6px;
    }
    .header small {
      display: block;
      font-size: 11px;
      font-weight: normal;
      margin-top: 3px;
    }
    .linha {
      display: flex;
      justify-content: space-between;
      margin-top: 4px;
    }
    .add  { padding-left: 10px; color: #444; font-size: 12px; }
    .obs  { padding-left: 10px; color: #666; font-style: italic; font-size: 11px; }
    .total {
      display: flex;
      justify-content: space-between;
      font-weight: bold;
      font-size: 15px;
      border-top: 1px dashed #000;
      margin-top: 8px;
      padding-top: 6px;
    }
    .rodape {
      text-align: center;
      margin-top: 10px;
      font-size: 11px;
      color: #666;
      border-top: 1px dashed #000;
      padding-top: 6px;
    }
  </style>
</head>
<body>
  <div class="header">
    🍇 OPEN AÇAÍ
    <small>Pedido #${numFmt} · ${agora}</small>
  </div>
  ${linhas}
  <div class="total"><span>TOTAL</span><span>R$ ${fmt(total)}</span></div>
  <div class="rodape">Obrigado! 💜</div>
</body>
</html>`;

    // Imprime via iframe oculto — sem popup, sem bloqueio
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

    bsModal.hide();
    limparTudo(true);
    toast(`🖨️ Pedido #${numFmt} impresso!`);

  } catch(e) {
    toast('❌ ' + e.message, true);
  } finally {
    btn.disabled = false; btn.textContent = '🖨️ Imprimir';
  }
}

function limparTudo(force = false) {
  if (!force && itensPedido.length && !confirm('Limpar o pedido inteiro?')) return;
  itensPedido = []; itemCounter = 0;
  tamSel = null; addsSel = []; quantidade = 1;
  document.getElementById('obsInput').value     = '';
  document.getElementById('qtyNum').textContent = '1';
  document.getElementById('btnAdd').disabled    = true;
  renderTamanhos(); renderAdicionais(); renderTicketMini();
}

function fmt(v) { return parseFloat(v).toFixed(2).replace('.', ','); }

function toast(msg, isError=false) {
  const el = document.getElementById('toast');
  el.classList.toggle('error', isError);
  document.getElementById('toastMsg').textContent = msg;
  bsToast.show();
}
</script>
</body>
</html>