<?php
// paginas/estoque.php
// Controle de estoque dos adicionais
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<title>Open Açaí – Estoque</title>

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

  /* ── CARDS DE ALERTA ── */
  .alerta-card {
    background: rgba(231,76,60,0.12);
    border: 1.5px solid rgba(231,76,60,0.35);
    border-radius: 16px;
    padding: 1rem 1.3rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
  }
  .alerta-icon { font-size: 1.6rem; flex-shrink: 0; }
  .alerta-nome { font-weight: 600; font-size: 0.95rem; }
  .alerta-qtd  { font-size: 0.8rem; color: var(--red); font-weight: 600; }

  /* ── CARD DE ADICIONAL ── */
  .add-card {
    background: var(--panel);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 1.1rem 1.2rem;
    transition: border-color 0.15s;
  }
  .add-card:hover { border-color: var(--acai-light); }
  .add-card.sem-estoque { border-color: rgba(231,76,60,0.4); background: rgba(231,76,60,0.06); }
  .add-card.estoque-baixo { border-color: rgba(230,126,34,0.4); background: rgba(230,126,34,0.06); }

  .add-card-top {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
  }
  .add-emoji { font-size: 2rem; line-height: 1; flex-shrink: 0; }
  .add-nome  { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; }
  .add-preco { font-size: 0.78rem; color: var(--muted); margin-top: 0.1rem; }

  /* barra de estoque */
  .estoque-bar-wrap {
    margin-bottom: 0.7rem;
  }
  .estoque-bar-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.72rem;
    color: var(--muted);
    margin-bottom: 0.25rem;
  }
  .estoque-bar-labels .atual {
    font-family: 'Syne', sans-serif;
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--cream);
  }
  .estoque-bar {
    height: 8px;
    background: var(--card);
    border-radius: 100px;
    overflow: hidden;
    border: 1px solid var(--border);
  }
  .estoque-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 0.4s ease;
  }
  .fill-ok     { background: var(--green); }
  .fill-baixo  { background: var(--orange); }
  .fill-zero   { background: var(--red); width: 100% !important; }

  /* badge de status */
  .badge-estoque {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.2rem 0.55rem;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
  }
  .badge-ok     { background: rgba(46,204,113,0.15); color: var(--green);   border: 1px solid rgba(46,204,113,0.3); }
  .badge-baixo  { background: rgba(230,126,34,0.15); color: var(--orange);  border: 1px solid rgba(230,126,34,0.3); }
  .badge-zero   { background: rgba(231,76,60,0.15);  color: var(--red);     border: 1px solid rgba(231,76,60,0.3);  }

  /* input de reposição */
  .repor-row {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-top: 0.6rem;
  }
  .repor-input {
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    padding: 0.4rem 0.7rem;
    width: 80px;
    outline: none;
    transition: border-color 0.2s;
    text-align: center;
  }
  .repor-input:focus { border-color: var(--acai-light); }
  .btn-repor {
    background: var(--acai);
    border: none;
    color: #fff;
    border-radius: 10px;
    padding: 0.4rem 0.9rem;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
    white-space: nowrap;
  }
  .btn-repor:hover { background: var(--acai-light); }
  .btn-repor:active { transform: scale(0.97); }

  /* ── MODAL EDITAR ── */
  .modal-content {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--cream);
  }
  .modal-title { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--gold); }
  .form-label  { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); font-weight: 600; }
  .form-control, .form-select {
    background: var(--card) !important;
    border: 1.5px solid var(--border) !important;
    color: var(--cream) !important;
    border-radius: 10px !important;
    font-family: 'DM Sans', sans-serif;
  }
  .form-control:focus, .form-select:focus {
    border-color: var(--acai-light) !important;
    box-shadow: none !important;
  }
  .btn-salvar {
    background: linear-gradient(135deg, var(--acai), var(--pink));
    border: none; color: #fff;
    border-radius: 12px; padding: 0.65rem 1.5rem;
    font-family: 'Syne', sans-serif; font-weight: 800;
    cursor: pointer; transition: all 0.15s;
  }
  .btn-salvar:hover { filter: brightness(1.1); }
  .btn-cancelar-modal {
    background: var(--card); border: 1.5px solid var(--border);
    color: var(--cream); border-radius: 12px; padding: 0.65rem 1.2rem;
    font-family: 'DM Sans', sans-serif; font-weight: 600; cursor: pointer;
  }

  /* ── SECTION TITLE ── */
  .section-title {
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--cream);
    margin-bottom: 0.8rem;
  }

  /* ── LOADING ── */
  .loading-ph { display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 3rem; color: var(--muted); }
  .spinner-sm { width: 20px; height: 20px; border: 2.5px solid var(--border); border-top-color: var(--acai-light); border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── TOAST ── */
  .toast-container { z-index: 9999; }
  .toast { background: var(--green) !important; color: #fff !important; border: none !important; font-weight: 600; border-radius: 100px !important; }
  .toast.error { background: var(--red) !important; }

  /* ── BTN NOVO ADICIONAL ── */
  .btn-novo {
    background: linear-gradient(135deg, var(--acai), var(--pink));
    border: none; color: #fff;
    border-radius: 12px; padding: 0.5rem 1.2rem;
    font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.88rem;
    cursor: pointer; transition: all 0.15s;
    white-space: nowrap;
  }
  .btn-novo:hover { filter: brightness(1.1); }

  .btn-editar {
    background: none;
    border: 1.5px solid var(--border);
    color: var(--muted);
    border-radius: 8px;
    padding: 0.25rem 0.6rem;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
  }
  .btn-editar:hover { border-color: var(--acai-light); color: var(--cream); }
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
      <li class="nav-item"><a class="nav-link" href="verpedidos.php">📋 Pedidos</a></li>
      <li class="nav-item"><a class="nav-link active" href="estoque.php">📦 Estoque</a></li>
    </ul>
  </div>
</nav>

<div class="container-fluid px-3 pt-3 pb-5">

  <!-- ALERTAS DE ESTOQUE BAIXO -->
  <div id="alertasArea"></div>

  <!-- CABEÇALHO -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div class="section-title mb-0">📦 Adicionais</div>
    <div class="d-flex gap-2">
      <button class="btn-editar" onclick="carregarAdicionais()">↻ Atualizar</button>
      <button class="btn-novo" onclick="abrirModalNovo()">＋ Novo Adicional</button>
    </div>
  </div>

  <!-- GRID DE ADICIONAIS -->
  <div id="gridAdicionais">
    <div class="loading-ph"><div class="spinner-sm"></div> Carregando...</div>
  </div>

  <!-- HISTÓRICO DE MOVIMENTOS -->
  <div class="section-title mt-4">📋 Últimos Movimentos</div>
  <div id="historico">
    <div class="loading-ph"><div class="spinner-sm"></div> Carregando...</div>
  </div>

</div>

<!-- MODAL: NOVO / EDITAR ADICIONAL -->
<div class="modal fade" id="modalAdicional" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-1">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="modalTitulo">Novo Adicional</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editId"/>
        <div class="row g-3">
          <div class="col-8">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" id="editNome" placeholder="Ex: Paçoca"/>
          </div>
          <div class="col-4">
            <label class="form-label">Emoji</label>
            <input type="text" class="form-control text-center" id="editIcone" placeholder="🍬" maxlength="4"/>
          </div>
          <div class="col-6">
            <label class="form-label">Preço (R$)</label>
            <input type="number" class="form-control" id="editPreco" placeholder="2.50" step="0.50" min="0"/>
          </div>
          <div class="col-6">
            <label class="form-label">Estoque Inicial</label>
            <input type="number" class="form-control" id="editEstoque" placeholder="20" min="0"/>
          </div>
          <div class="col-6">
            <label class="form-label">Estoque Mínimo</label>
            <input type="number" class="form-control" id="editMinimo" placeholder="5" min="0"/>
          </div>
          <div class="col-6">
            <label class="form-label">Status</label>
            <select class="form-select" id="editAtivo">
              <option value="1">✅ Ativo</option>
              <option value="0">❌ Inativo</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 gap-2">
        <button class="btn-cancelar-modal" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn-salvar" onclick="salvarAdicional()">💾 Salvar</button>
      </div>
    </div>
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
let adicionais = [];
let bsModal, bsToast;

document.addEventListener('DOMContentLoaded', () => {
  bsModal = new bootstrap.Modal(document.getElementById('modalAdicional'));
  bsToast = new bootstrap.Toast(document.getElementById('toast'), { delay: 2500 });
  carregarAdicionais();
  carregarHistorico();
});

// ── CARREGAR ADICIONAIS ───────────────────────────────────────
async function carregarAdicionais() {
  try {
    const j = await fetch(`${API}?acao=adicionais`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    adicionais = j.dados;
    renderAlertas();
    renderAdicionais();
  } catch(e) {
    document.getElementById('gridAdicionais').innerHTML =
      `<div class="text-danger small p-3">Erro: ${e.message}</div>`;
  }
}

// ── ALERTAS ───────────────────────────────────────────────────
function renderAlertas() {
  const criticos = adicionais.filter(a => a.sem_estoque || a.estoque_baixo);

  if (!criticos.length) {
    document.getElementById('alertasArea').innerHTML = '';
    return;
  }

  document.getElementById('alertasArea').innerHTML = `
    <div class="mb-3">
      <div class="section-title text-danger mb-2">⚠️ Atenção — Estoque Baixo</div>
      <div class="row g-2">
        ${criticos.map(a => `
          <div class="col-12 col-md-6 col-lg-4">
            <div class="alerta-card">
              <span class="alerta-icon">${a.icone}</span>
              <div>
                <div class="alerta-nome">${a.nome}</div>
                <div class="alerta-qtd">
                  ${a.sem_estoque
                    ? '❌ Sem estoque!'
                    : `⚠️ Restam apenas ${a.estoque_atual} unidade(s)`}
                </div>
              </div>
            </div>
          </div>
        `).join('')}
      </div>
    </div>
  `;
}

// ── GRID DE ADICIONAIS ────────────────────────────────────────
function renderAdicionais() {
  const maxEstoque = 50; // referência pra barra visual

  document.getElementById('gridAdicionais').innerHTML = `
    <div class="row g-3">
      ${adicionais.map(a => {
        const pct     = Math.min(100, (a.estoque_atual / maxEstoque) * 100);
        const classe  = a.sem_estoque ? 'sem-estoque' : a.estoque_baixo ? 'estoque-baixo' : '';
        const fillCls = a.sem_estoque ? 'fill-zero' : a.estoque_baixo ? 'fill-baixo' : 'fill-ok';
        const badgeCls= a.sem_estoque ? 'badge-zero' : a.estoque_baixo ? 'badge-baixo' : 'badge-ok';
        const badgeTxt= a.sem_estoque ? 'Sem estoque' : a.estoque_baixo ? 'Estoque baixo' : 'OK';

        return `
          <div class="col-12 col-sm-6 col-lg-4">
            <div class="add-card ${classe}">
              <div class="add-card-top">
                <span class="add-emoji">${a.icone}</span>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2">
                    <span class="add-nome">${a.nome}</span>
                    <span class="badge-estoque ${badgeCls}">${badgeTxt}</span>
                  </div>
                  <div class="add-preco">R$ ${fmt(a.preco)} por unidade</div>
                </div>
                <button class="btn-editar" onclick="abrirModalEditar(${a.id})">✏️</button>
              </div>

              <div class="estoque-bar-wrap">
                <div class="estoque-bar-labels">
                  <span class="atual">${a.estoque_atual} un.</span>
                  <span>Mín: ${a.estoque_minimo}</span>
                </div>
                <div class="estoque-bar">
                  <div class="estoque-fill ${fillCls}" style="width:${pct}%"></div>
                </div>
              </div>

              <div class="repor-row">
                <input type="number" class="repor-input" id="repor-${a.id}"
                       placeholder="Qtd" min="1" max="999"/>
                <button class="btn-repor" onclick="reporEstoque(${a.id})">＋ Repor</button>
              </div>
            </div>
          </div>
        `;
      }).join('')}
    </div>
  `;
}

// ── REPOR ESTOQUE ─────────────────────────────────────────────
async function reporEstoque(id) {
  const input = document.getElementById(`repor-${id}`);
  const qtd   = parseInt(input.value);

  if (!qtd || qtd <= 0) {
    toast('❌ Informe uma quantidade válida.', true);
    return;
  }

  try {
    const j = await fetch(`${API}?acao=repor_estoque`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ adicional_id: id, quantidade: qtd }),
    }).then(r => r.json());

    if (!j.ok) throw new Error(j.mensagem);

    input.value = '';

    // Atualiza localmente
    const a = adicionais.find(a => a.id === id);
    if (a) {
      a.estoque_atual  += qtd;
      a.sem_estoque    = a.estoque_atual === 0;
      a.estoque_baixo  = a.estoque_atual <= a.estoque_minimo;
    }

    renderAlertas();
    renderAdicionais();
    carregarHistorico();
    toast(`✓ +${qtd} unidades adicionadas!`);

  } catch(e) {
    toast('❌ ' + e.message, true);
  }
}

// ── MODAL NOVO ────────────────────────────────────────────────
function abrirModalNovo() {
  document.getElementById('modalTitulo').textContent = '➕ Novo Adicional';
  document.getElementById('editId').value      = '';
  document.getElementById('editNome').value    = '';
  document.getElementById('editIcone').value   = '';
  document.getElementById('editPreco').value   = '';
  document.getElementById('editEstoque').value = '';
  document.getElementById('editMinimo').value  = '5';
  document.getElementById('editAtivo').value   = '1';
  bsModal.show();
}

// ── MODAL EDITAR ──────────────────────────────────────────────
function abrirModalEditar(id) {
  const a = adicionais.find(a => a.id === id);
  if (!a) return;

  document.getElementById('modalTitulo').textContent = `✏️ Editar — ${a.nome}`;
  document.getElementById('editId').value      = a.id;
  document.getElementById('editNome').value    = a.nome;
  document.getElementById('editIcone').value   = a.icone;
  document.getElementById('editPreco').value   = a.preco;
  document.getElementById('editEstoque').value = a.estoque_atual;
  document.getElementById('editMinimo').value  = a.estoque_minimo;
  document.getElementById('editAtivo').value   = '1';
  bsModal.show();
}

// ── SALVAR ADICIONAL ──────────────────────────────────────────
async function salvarAdicional() {
  const id      = document.getElementById('editId').value;
  const nome    = document.getElementById('editNome').value.trim();
  const icone   = document.getElementById('editIcone').value.trim() || '🍬';
  const preco   = parseFloat(document.getElementById('editPreco').value);
  const estoque = parseInt(document.getElementById('editEstoque').value);
  const minimo  = parseInt(document.getElementById('editMinimo').value);
  const ativo   = parseInt(document.getElementById('editAtivo').value);

  if (!nome || isNaN(preco) || isNaN(estoque) || isNaN(minimo)) {
    toast('❌ Preencha todos os campos.', true);
    return;
  }

  try {
    const acao = id ? 'editar_adicional' : 'novo_adicional';
    const j = await fetch(`${API}?acao=${acao}`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ id: id || null, nome, icone, preco, estoque_atual: estoque, estoque_minimo: minimo, ativo }),
    }).then(r => r.json());

    if (!j.ok) throw new Error(j.mensagem);

    bsModal.hide();
    await carregarAdicionais();
    toast(id ? '✓ Adicional atualizado!' : '✓ Adicional criado!');

  } catch(e) {
    toast('❌ ' + e.message, true);
  }
}

// ── HISTÓRICO DE MOVIMENTOS ───────────────────────────────────
async function carregarHistorico() {
  try {
    const j = await fetch(`${API}?acao=historico_estoque`).then(r => r.json());
    if (!j.ok) throw new Error(j.mensagem);
    renderHistorico(j.dados);
  } catch(e) {
    document.getElementById('historico').innerHTML =
      `<div class="text-danger small p-2">Erro: ${e.message}</div>`;
  }
}

function renderHistorico(movimentos) {
  if (!movimentos?.length) {
    document.getElementById('historico').innerHTML =
      `<div style="color:var(--muted);font-size:0.85rem;padding:1rem;">Nenhum movimento registrado.</div>`;
    return;
  }

  document.getElementById('historico').innerHTML = `
    <div style="background:var(--panel);border:1.5px solid var(--border);border-radius:16px;overflow:hidden;">
      <table class="table table-borderless mb-0" style="color:var(--cream);">
        <thead style="border-bottom:1px solid var(--border);">
          <tr style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);">
            <th class="ps-3 py-2">Adicional</th>
            <th class="py-2">Tipo</th>
            <th class="py-2">Qtd</th>
            <th class="py-2">Motivo</th>
            <th class="pe-3 py-2">Hora</th>
          </tr>
        </thead>
        <tbody>
          ${movimentos.map(m => {
            const isEntrada = m.tipo === 'entrada';
            const cor       = isEntrada ? 'var(--green)' : 'var(--red)';
            const sinal     = isEntrada ? '+' : '-';
            const hora      = new Date(m.criado_em).toLocaleString('pt-BR', {
              day:'2-digit', month:'2-digit', hour:'2-digit', minute:'2-digit'
            });
            return `
              <tr style="border-bottom:1px solid var(--border);font-size:0.85rem;">
                <td class="ps-3 py-2">${m.icone} ${m.nome}</td>
                <td class="py-2" style="color:${cor};font-weight:600;">${isEntrada ? '▲ Entrada' : '▼ Saída'}</td>
                <td class="py-2" style="color:${cor};font-weight:700;">${sinal}${m.quantidade}</td>
                <td class="py-2" style="color:var(--muted);font-size:0.78rem;">${m.motivo || '—'}</td>
                <td class="pe-3 py-2" style="color:var(--muted);font-size:0.78rem;white-space:nowrap;">${hora}</td>
              </tr>
            `;
          }).join('')}
        </tbody>
      </table>
    </div>
  `;
}

// ── UTILS ─────────────────────────────────────────────────────
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