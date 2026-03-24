<?php
// ============================================================
//  index.php  –  PontoAçaí – Sistema de Pedidos
//  Coloque em: C:\xampp\htdocs\acai-sistema\public\index.php
// ============================================================
require_once '../config/conexao.php';
$pdo = conectar();

$tamanhosDB  = $pdo->query("SELECT * FROM tamanhos  WHERE ativo = 1 ORDER BY ml ASC")->fetchAll();
$saboresDB   = $pdo->query("SELECT * FROM sabores   WHERE ativo = 1 ORDER BY nome ASC")->fetchAll();
$adicionaisDB= $pdo->query("SELECT * FROM adicionais WHERE ativo = 1 ORDER BY nome ASC")->fetchAll();
$caldasDB    = $pdo->query("SELECT * FROM caldas     WHERE ativo = 1 ORDER BY preco ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>PontoAçaí</title>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ── Variáveis ─────────────────────────────────────────────── */
:root {
  --roxo:        #3D1A6B;
  --roxo-medio:  #6B2FA0;
  --roxo-claro:  #9B59D0;
  --acai:        #2C0E4E;
  --rosa:        #E91E8C;
  --rosa-claro:  #FF6EB4;
  --amarelo:     #FFD700;
  --creme:       #FFF8F0;
  --verde:       #2ECC71;
  --vermelho:    #E74C3C;
  --laranja:     #FF7043;
  --bg:          #1A0A30;
  --card-bg:     rgba(255,255,255,0.07);
  --card-border: rgba(255,255,255,0.12);
  --radius:      16px;
  --radius-sm:   10px;
  --shadow:      0 8px 32px rgba(0,0,0,0.4);
}

/* ── Reset & Base ──────────────────────────────────────────── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html { height:100%; }
body {
  font-family:'Nunito', sans-serif;
  background: linear-gradient(160deg, #1A0A30 0%, #2C0E4E 60%, #1A0A30 100%);
  min-height:100vh;
  color:#fff;
  overflow-x:hidden;
  user-select:none;
}

/* ── Header ────────────────────────────────────────────────── */
.header {
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:16px 20px;
  background:rgba(0,0,0,0.3);
  backdrop-filter:blur(12px);
  border-bottom:1px solid var(--card-border);
  position:sticky;
  top:0;
  z-index:100;
}
.logo {
  font-family:'Baloo 2', cursive;
  font-size:1.6rem;
  font-weight:900;
  color:#fff;
  display:flex;
  align-items:center;
  gap:6px;
}
.logo span { color:var(--rosa); }
.logo-emoji { font-size:1.8rem; filter:drop-shadow(0 0 8px rgba(233,30,140,0.6)); }

/* ── Indicador de etapas ───────────────────────────────────── */
.steps {
  display:flex;
  align-items:center;
  gap:6px;
}
.step-dot {
  width:10px; height:10px;
  border-radius:50%;
  background:rgba(255,255,255,0.2);
  transition:all .3s;
}
.step-dot.active {
  background:var(--rosa);
  width:28px;
  border-radius:5px;
  box-shadow:0 0 8px var(--rosa);
}
.step-dot.done { background:var(--verde); }

/* ── Progress bar ──────────────────────────────────────────── */
.progress-bar { height:3px; background:rgba(255,255,255,0.1); margin-bottom:20px; border-radius:2px; overflow:hidden; }
.progress-fill { height:100%; background:linear-gradient(90deg, var(--rosa), var(--amarelo)); border-radius:2px; transition:width .4s ease; }

/* ── Screens ───────────────────────────────────────────────── */
.screen { display:none; padding:20px 16px 100px; max-width:600px; margin:0 auto; }
.screen.active { display:block; animation:slideIn .3s ease; }
@keyframes slideIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

.screen-title {
  font-family:'Baloo 2', cursive;
  font-size:1.6rem;
  font-weight:800;
  text-align:center;
  margin-bottom:6px;
}
.screen-subtitle { text-align:center; color:rgba(255,255,255,0.55); font-size:.9rem; margin-bottom:20px; }

/* ── Botões ─────────────────────────────────────────────────── */
.btn-group {
  position:fixed;
  bottom:0; left:0; right:0;
  display:flex;
  gap:10px;
  padding:12px 16px;
  background:rgba(26,10,48,0.95);
  backdrop-filter:blur(12px);
  border-top:1px solid var(--card-border);
  z-index:50;
}
.btn {
  flex:1;
  padding:14px;
  border:none;
  border-radius:var(--radius-sm);
  font-family:'Baloo 2', cursive;
  font-size:1.05rem;
  font-weight:700;
  cursor:pointer;
  transition:all .2s;
}
.btn-primary {
  background:linear-gradient(135deg, var(--rosa), #c2185b);
  color:#fff;
  box-shadow:0 4px 16px rgba(233,30,140,0.4);
}
.btn-primary:hover:not(:disabled) { transform:translateY(-1px); box-shadow:0 6px 20px rgba(233,30,140,0.5); }
.btn-primary:disabled { opacity:.4; cursor:not-allowed; }
.btn-secondary {
  background:var(--card-bg);
  color:rgba(255,255,255,0.7);
  border:1px solid var(--card-border);
}
.btn-success {
  background:linear-gradient(135deg, var(--verde), #27ae60);
  color:#fff;
  box-shadow:0 4px 16px rgba(46,204,113,0.4);
}
.btn-success:hover { transform:translateY(-1px); }

/* ── Cards de tamanho ──────────────────────────────────────── */
.size-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:12px; }
.size-card {
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:var(--radius);
  padding:16px 10px;
  text-align:center;
  cursor:pointer;
  transition:all .25s;
  position:relative;
  overflow:hidden;
}
.size-card:hover { border-color:var(--roxo-claro); transform:translateY(-2px); }
.size-card.selected {
  border-color:var(--rosa);
  background:rgba(233,30,140,0.15);
  box-shadow:0 0 0 2px rgba(233,30,140,0.3), var(--shadow);
}
.popular-badge {
  position:absolute;
  top:6px; right:6px;
  background:var(--amarelo);
  color:#333;
  font-size:.62rem;
  font-weight:800;
  padding:2px 6px;
  border-radius:20px;
  white-space:nowrap;
}
.cup-icon { font-size:2rem; margin-bottom:4px; }
.cup-ml { font-family:'Baloo 2', cursive; font-size:1.1rem; font-weight:800; color:#fff; }
.cup-price { font-size:.8rem; color:var(--rosa-claro); font-weight:700; margin:2px 0; }
.cup-label { font-size:.8rem; color:rgba(255,255,255,0.6); }
.cup-gratis { font-size:.72rem; color:var(--verde); margin-top:4px; }

/* ── Cards de sabor ────────────────────────────────────────── */
.type-grid { display:flex; flex-direction:column; gap:10px; }
.type-card {
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:var(--radius);
  padding:14px 16px;
  display:flex;
  align-items:center;
  gap:14px;
  cursor:pointer;
  transition:all .25s;
}
.type-card:hover { border-color:var(--roxo-claro); transform:translateX(3px); }
.type-card.selected {
  border-color:var(--rosa);
  background:rgba(233,30,140,0.15);
  box-shadow:0 0 0 2px rgba(233,30,140,0.3);
}
.type-emoji { font-size:2rem; flex-shrink:0; }
.type-info { flex:1; }
.type-name { font-family:'Baloo 2', cursive; font-size:1.05rem; font-weight:700; }
.type-desc { font-size:.8rem; color:rgba(255,255,255,0.5); margin-top:2px; }
.type-price {
  font-size:.9rem;
  font-weight:800;
  color:var(--amarelo);
  white-space:nowrap;
}

/* ── Complementos ──────────────────────────────────────────── */
.comp-banner {
  background:rgba(255,215,0,0.1);
  border:1px solid rgba(255,215,0,0.3);
  border-radius:var(--radius-sm);
  padding:10px 14px;
  text-align:center;
  font-size:.85rem;
  font-weight:700;
  color:var(--amarelo);
  margin-bottom:14px;
}
.comp-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.comp-card {
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:var(--radius);
  padding:14px 8px;
  text-align:center;
  cursor:pointer;
  transition:all .25s;
  position:relative;
}
.comp-card:hover:not(.sem-estoque) { border-color:var(--roxo-claro); transform:translateY(-2px); }
.comp-card.selected {
  border-color:var(--verde);
  background:rgba(46,204,113,0.15);
  box-shadow:0 0 0 2px rgba(46,204,113,0.25);
}
.comp-card.sem-estoque { opacity:.4; cursor:not-allowed; }
.comp-emoji { font-size:1.8rem; margin-bottom:4px; }
.comp-name { font-size:.78rem; font-weight:700; color:#fff; line-height:1.2; }
.comp-price { font-size:.72rem; color:rgba(255,255,255,0.55); margin-top:3px; }
.comp-card.selected .comp-price { color:var(--verde); }
.comp-badge-gratis {
  position:absolute;
  top:-6px; right:-6px;
  background:var(--verde);
  color:#fff;
  font-size:.6rem;
  font-weight:800;
  padding:2px 5px;
  border-radius:10px;
  display:none;
}
.comp-card.gratis .comp-badge-gratis { display:block; }

/* ── Caldas ─────────────────────────────────────────────────── */
.calda-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
.calda-card {
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:var(--radius);
  padding:20px;
  text-align:center;
  cursor:pointer;
  transition:all .25s;
}
.calda-card:hover { border-color:var(--roxo-claro); transform:translateY(-2px); }
.calda-card.selected {
  border-color:var(--amarelo);
  background:rgba(255,215,0,0.12);
  box-shadow:0 0 0 2px rgba(255,215,0,0.25);
}
.calda-emoji { font-size:2.2rem; margin-bottom:6px; }
.calda-nome { font-family:'Baloo 2', cursive; font-size:1rem; font-weight:700; }
.calda-price { font-size:.8rem; color:var(--amarelo); margin-top:3px; }

/* ── Resumo ─────────────────────────────────────────────────── */
.resumo-box {
  background:var(--card-bg);
  border:1px solid var(--card-border);
  border-radius:var(--radius);
  overflow:hidden;
  margin-bottom:14px;
}
.resumo-header {
  background:rgba(233,30,140,0.2);
  padding:12px 16px;
  font-family:'Baloo 2', cursive;
  font-size:1rem;
  font-weight:800;
  border-bottom:1px solid var(--card-border);
}
.resumo-linha {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 16px;
  border-bottom:1px solid rgba(255,255,255,0.05);
  font-size:.9rem;
}
.resumo-linha:last-child { border-bottom:none; }
.resumo-label { color:rgba(255,255,255,0.6); }
.resumo-valor { font-weight:700; color:#fff; }
.resumo-valor.gratis { color:var(--verde); }
.resumo-total {
  background:linear-gradient(135deg, var(--roxo-medio), var(--acai));
  padding:16px;
  text-align:center;
  border-top:1px solid var(--card-border);
}
.resumo-total-label { font-size:.85rem; color:rgba(255,255,255,0.7); margin-bottom:4px; }
.resumo-total-valor {
  font-family:'Baloo 2', cursive;
  font-size:2rem;
  font-weight:900;
  color:var(--amarelo);
}

.obs-input {
  width:100%;
  background:var(--card-bg);
  border:1px solid var(--card-border);
  border-radius:var(--radius-sm);
  padding:12px 14px;
  color:#fff;
  font-family:'Nunito', sans-serif;
  font-size:.9rem;
  resize:none;
  outline:none;
  margin-bottom:14px;
}
.obs-input:focus { border-color:var(--roxo-claro); }
.obs-input::placeholder { color:rgba(255,255,255,0.3); }

/* ── Sucesso ────────────────────────────────────────────────── */
.success-screen { display:none; flex-direction:column; align-items:center; justify-content:center; min-height:80vh; text-align:center; padding:40px 20px; }
.success-screen.active { display:flex; animation:fadeIn .5s ease; }
@keyframes fadeIn { from { opacity:0; transform:scale(.95); } to { opacity:1; transform:scale(1); } }
.success-icon { font-size:5rem; margin-bottom:16px; filter:drop-shadow(0 0 20px rgba(46,204,113,0.6)); }
.success-title { font-family:'Baloo 2', cursive; font-size:2rem; font-weight:900; color:var(--verde); margin-bottom:8px; }
.success-numero { font-size:1rem; color:rgba(255,255,255,0.6); margin-bottom:24px; }
.success-numero strong { color:var(--amarelo); font-size:1.4rem; }
</style>
</head>
<body>

<!-- ── Header ─────────────────────────────────────────────── -->
<div class="header">
  <div class="logo">
    <span class="logo-emoji">🍇</span>Ponto<span>Açaí</span>
  </div>
  <div class="steps" id="steps">
    <div class="step-dot active" id="dot1"></div>
    <div class="step-dot" id="dot2"></div>
    <div class="step-dot" id="dot3"></div>
    <div class="step-dot" id="dot4"></div>
    <div class="step-dot" id="dot5"></div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA 1 – TAMANHO
════════════════════════════════════════════════════════════ -->
<div class="screen active" id="screen-tamanho">
  <div class="progress-bar"><div class="progress-fill" id="prog1" style="width:16%"></div></div>
  <div class="screen-title">🥤 Escolha o tamanho</div>
  <p class="screen-subtitle">O tamanho define quantos complementos você ganha grátis</p>
  <div class="size-grid">
    <?php foreach($tamanhosDB as $t): ?>
    <div class="size-card" onclick="selectTamanho(this, <?= $t['id'] ?>)">
      <?php if($t['popular']): ?>
        <div class="popular-badge">⭐ MAIS PEDIDO</div>
      <?php endif; ?>
      <div class="cup-icon">🥤</div>
      <div class="cup-ml"><?= $t['ml'] ?>ml</div>
      <?php if($t['acrescimo'] > 0): ?>
        <div class="cup-price">+ R$ <?= number_format($t['acrescimo'],2,',','.') ?></div>
      <?php else: ?>
        <div class="cup-price" style="color:var(--verde)">Preço base</div>
      <?php endif; ?>
      <div class="cup-label"><?= htmlspecialchars($t['nome']) ?></div>
      <div class="cup-gratis">✅ <?= $t['complementos_gratis'] ?> grátis</div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="btn-group">
    <button class="btn btn-primary" id="btn-next1" onclick="goTo('sabor')" disabled>Próximo →</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA 2 – SABOR
════════════════════════════════════════════════════════════ -->
<div class="screen" id="screen-sabor">
  <div class="progress-bar"><div class="progress-fill" style="width:33%"></div></div>
  <div class="screen-title">🍇 Escolha o açaí</div>
  <p class="screen-subtitle">Preço base varia por sabor</p>
  <div class="type-grid">
    <?php foreach($saboresDB as $s): ?>
    <div class="type-card" onclick="selectSabor(this, <?= $s['id'] ?>, <?= $s['preco'] ?>)">
      <div class="type-emoji"><?= $s['emoji'] ?></div>
      <div class="type-info">
        <div class="type-name"><?= htmlspecialchars($s['nome']) ?></div>
        <?php if(!empty($s['descricao'])): ?>
          <div class="type-desc"><?= htmlspecialchars($s['descricao']) ?></div>
        <?php endif; ?>
      </div>
      <div class="type-price">R$ <?= number_format($s['preco'],2,',','.') ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="btn-group">
    <button class="btn btn-secondary" onclick="goTo('tamanho')">← Voltar</button>
    <button class="btn btn-primary" id="btn-next2" onclick="goTo('complementos')" disabled>Próximo →</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA 3 – COMPLEMENTOS
════════════════════════════════════════════════════════════ -->
<div class="screen" id="screen-complementos">
  <div class="progress-bar"><div class="progress-fill" style="width:50%"></div></div>
  <div class="screen-title">✨ Complementos</div>
  <div class="comp-banner" id="comp-banner">Carregando...</div>
  <div class="comp-grid">
    <?php foreach($adicionaisDB as $a): ?>
    <?php $esgotado = ($a['estoque_atual'] <= 0); ?>
    <div class="comp-card <?= $esgotado ? 'sem-estoque' : '' ?>"
         id="comp-<?= $a['id'] ?>"
         <?php if(!$esgotado): ?>onclick="toggleComp(this, <?= $a['id'] ?>, <?= $a['preco'] ?>)"<?php endif; ?>>
      <div class="comp-badge-gratis">GRÁTIS</div>
      <div class="comp-emoji"><?= $a['emoji'] ?></div>
      <div class="comp-name"><?= htmlspecialchars($a['nome']) ?></div>
      <div class="comp-price"><?= $esgotado ? '❌ Esgotado' : '+ R$ '.number_format($a['preco'],2,',','.') ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="btn-group">
    <button class="btn btn-secondary" onclick="goTo('sabor')">← Voltar</button>
    <button class="btn btn-primary" onclick="goTo('calda')">Próximo →</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA 4 – CALDA
════════════════════════════════════════════════════════════ -->
<div class="screen" id="screen-calda">
  <div class="progress-bar"><div class="progress-fill" style="width:75%"></div></div>
  <div class="screen-title">🍯 Escolha a calda</div>
  <p class="screen-subtitle">Finaliza com estilo</p>
  <div class="calda-grid">
    <?php foreach($caldasDB as $c): ?>
    <div class="calda-card" onclick="selectCalda(this, <?= $c['id'] ?>, <?= $c['preco'] ?>)">
      <div class="calda-emoji"><?= $c['emoji'] ?></div>
      <div class="calda-nome"><?= htmlspecialchars($c['nome']) ?></div>
      <div class="calda-price"><?= $c['preco'] > 0 ? '+ R$ '.number_format($c['preco'],2,',','.') : 'Grátis ✅' ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="btn-group">
    <button class="btn btn-secondary" onclick="goTo('complementos')">← Voltar</button>
    <button class="btn btn-primary" id="btn-next4" onclick="goTo('resumo')" disabled>Ver resumo →</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA 5 – RESUMO & CONFIRMAÇÃO
════════════════════════════════════════════════════════════ -->
<div class="screen" id="screen-resumo">
  <div class="progress-bar"><div class="progress-fill" style="width:95%"></div></div>
  <div class="screen-title">📋 Seu pedido</div>
  <p class="screen-subtitle">Confira antes de confirmar</p>

  <div class="resumo-box">
    <div class="resumo-header">🥤 Copo</div>
    <div class="resumo-linha">
      <span class="resumo-label">Tamanho</span>
      <span class="resumo-valor" id="r-tamanho">—</span>
    </div>
    <div class="resumo-linha">
      <span class="resumo-label">Sabor</span>
      <span class="resumo-valor" id="r-sabor">—</span>
    </div>
    <div class="resumo-linha">
      <span class="resumo-label">Calda</span>
      <span class="resumo-valor" id="r-calda">—</span>
    </div>
  </div>

  <div class="resumo-box">
    <div class="resumo-header">✨ Complementos</div>
    <div id="r-comps-lista">
      <div class="resumo-linha"><span class="resumo-label" style="color:rgba(255,255,255,0.3)">Nenhum selecionado</span></div>
    </div>
  </div>

  <div class="resumo-box">
    <div class="resumo-header">💰 Valores</div>
    <div class="resumo-linha">
      <span class="resumo-label">Açaí</span>
      <span class="resumo-valor" id="r-preco-sabor">R$ 0,00</span>
    </div>
    <div class="resumo-linha">
      <span class="resumo-label">Acréscimo tamanho</span>
      <span class="resumo-valor" id="r-acrescimo">R$ 0,00</span>
    </div>
    <div class="resumo-linha">
      <span class="resumo-label">Complementos pagos</span>
      <span class="resumo-valor" id="r-preco-comps">R$ 0,00</span>
    </div>
    <div class="resumo-linha">
      <span class="resumo-label">Calda extra</span>
      <span class="resumo-valor" id="r-preco-calda">R$ 0,00</span>
    </div>
    <div class="resumo-total">
      <div class="resumo-total-label">Total do pedido</div>
      <div class="resumo-total-valor" id="r-total">R$ 0,00</div>
    </div>
  </div>

  <textarea class="obs-input" id="observacao" rows="3" placeholder="Alguma observação? (opcional)"></textarea>

  <div class="btn-group">
    <button class="btn btn-secondary" onclick="goTo('calda')">← Voltar</button>
    <button class="btn btn-success" onclick="confirmarPedido()">✅ Confirmar pedido</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TELA DE SUCESSO
════════════════════════════════════════════════════════════ -->
<div class="success-screen" id="screen-sucesso">
  <div class="success-icon">✅</div>
  <div class="success-title">Pedido confirmado!</div>
  <div class="success-numero">Número: <strong id="suc-numero">—</strong></div>
  <button class="btn btn-primary" style="max-width:280px; margin:0 auto;" onclick="novoPedido()">
    + Novo pedido
  </button>
</div>

<!-- ═══════════════════════════════════════════════════════════
     DADOS PHP → JS
════════════════════════════════════════════════════════════ -->
<script>
const CFG_TAMANHOS   = <?= json_encode($tamanhosDB,   JSON_UNESCAPED_UNICODE) ?>;
const CFG_SABORES    = <?= json_encode($saboresDB,    JSON_UNESCAPED_UNICODE) ?>;
const CFG_ADICIONAIS = <?= json_encode($adicionaisDB, JSON_UNESCAPED_UNICODE) ?>;
const CFG_CALDAS     = <?= json_encode($caldasDB,     JSON_UNESCAPED_UNICODE) ?>;

// ── Estado do pedido ────────────────────────────────────────
let state = {
  tamanhoId:    null,
  tamanhoNome:  '',
  tamanhoML:    0,
  tamanhoAcrescimo: 0,
  gratis:       0,
  saborId:      null,
  saborNome:    '',
  saborEmoji:   '',
  saborPreco:   0,
  complementos: [],   // [{id, nome, emoji, preco, ehGratis}]
  caldaId:      null,
  caldaNome:    '',
  caldaPreco:   0,
};

// ── Navegação ────────────────────────────────────────────────
const STEPS = ['tamanho','sabor','complementos','calda','resumo'];
function goTo(screen) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById('screen-'+screen).classList.add('active');
  // Atualiza dots
  const idx = STEPS.indexOf(screen);
  document.querySelectorAll('.step-dot').forEach((d, i) => {
    d.classList.remove('active','done');
    if(i < idx)  d.classList.add('done');
    if(i === idx) d.classList.add('active');
  });
  if(screen === 'complementos') atualizarBannerComps();
  if(screen === 'resumo') montarResumo();
}

// ── Tamanho ──────────────────────────────────────────────────
function selectTamanho(el, id) {
  document.querySelectorAll('.size-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const t = CFG_TAMANHOS.find(x => x.id == id);
  state.tamanhoId       = id;
  state.tamanhoNome     = t.nome;
  state.tamanhoML       = t.ml;
  state.tamanhoAcrescimo= parseFloat(t.acrescimo);
  state.gratis          = parseInt(t.complementos_gratis);
  // Resetar complementos se mudar de tamanho
  state.complementos = [];
  document.querySelectorAll('.comp-card').forEach(c => {
    c.classList.remove('selected','gratis');
  });
  document.getElementById('btn-next1').disabled = false;
}

// ── Sabor ────────────────────────────────────────────────────
function selectSabor(el, id, preco) {
  document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const s = CFG_SABORES.find(x => x.id == id);
  state.saborId    = id;
  state.saborNome  = s.nome;
  state.saborEmoji = s.emoji;
  state.saborPreco = parseFloat(s.preco);
  document.getElementById('btn-next2').disabled = false;
}

// ── Complementos ─────────────────────────────────────────────
function toggleComp(el, id, preco) {
  const idx = state.complementos.findIndex(c => c.id == id);
  if(idx >= 0) {
    // Remove
    state.complementos.splice(idx, 1);
    el.classList.remove('selected','gratis');
  } else {
    // Adiciona
    const a = CFG_ADICIONAIS.find(x => x.id == id);
    state.complementos.push({ id, nome: a.nome, emoji: a.emoji, preco: parseFloat(a.preco) });
    el.classList.add('selected');
  }
  recalcularGratis();
  atualizarBannerComps();
}

function recalcularGratis() {
  state.complementos.forEach((c, i) => {
    c.ehGratis = i < state.gratis;
    const el = document.getElementById('comp-'+c.id);
    if(el) {
      if(c.ehGratis) el.classList.add('gratis'); else el.classList.remove('gratis');
    }
  });
}

function atualizarBannerComps() {
  const sel = state.complementos.length;
  const gratis = Math.min(sel, state.gratis);
  const pagos  = Math.max(0, sel - state.gratis);
  let txt = `✨ ${gratis} grátis`;
  if(pagos > 0) txt += ` · ${pagos} pago${pagos>1?'s':''}`;
  if(state.gratis > sel) txt += ` · ainda ${state.gratis - sel} grátis disponíve${(state.gratis-sel)>1?'is':'l'}`;
  document.getElementById('comp-banner').textContent = txt;
}

// ── Calda ────────────────────────────────────────────────────
function selectCalda(el, id, preco) {
  document.querySelectorAll('.calda-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const c = CFG_CALDAS.find(x => x.id == id);
  state.caldaId    = id;
  state.caldaNome  = c.nome;
  state.caldaPreco = parseFloat(c.preco);
  document.getElementById('btn-next4').disabled = false;
}

// ── Resumo ───────────────────────────────────────────────────
function calcularTotal() {
  const precoPagos = state.complementos.filter(c => !c.ehGratis).reduce((s,c) => s+c.preco, 0);
  return state.saborPreco + state.tamanhoAcrescimo + precoPagos + state.caldaPreco;
}

function fmt(v) { return 'R$ ' + v.toFixed(2).replace('.',','); }

function montarResumo() {
  // Copo
  document.getElementById('r-tamanho').textContent = `${state.tamanhoNome} (${state.tamanhoML}ml)`;
  document.getElementById('r-sabor').textContent   = `${state.saborEmoji} ${state.saborNome}`;
  document.getElementById('r-calda').textContent   = `${state.caldaNome}`;
  // Complementos
  const lista = document.getElementById('r-comps-lista');
  if(state.complementos.length === 0) {
    lista.innerHTML = `<div class="resumo-linha"><span class="resumo-label" style="color:rgba(255,255,255,0.3)">Nenhum</span></div>`;
  } else {
    lista.innerHTML = state.complementos.map(c => `
      <div class="resumo-linha">
        <span class="resumo-label">${c.emoji} ${c.nome}</span>
        <span class="resumo-valor ${c.ehGratis?'gratis':''}">${c.ehGratis ? 'GRÁTIS ✅' : fmt(c.preco)}</span>
      </div>`).join('');
  }
  // Valores
  const precoPagos = state.complementos.filter(c => !c.ehGratis).reduce((s,c) => s+c.preco, 0);
  document.getElementById('r-preco-sabor').textContent  = fmt(state.saborPreco);
  document.getElementById('r-acrescimo').textContent    = fmt(state.tamanhoAcrescimo);
  document.getElementById('r-preco-comps').textContent  = fmt(precoPagos);
  document.getElementById('r-preco-calda').textContent  = fmt(state.caldaPreco);
  document.getElementById('r-total').textContent        = fmt(calcularTotal());
}

// ── Confirmar pedido ─────────────────────────────────────────
async function confirmarPedido() {
  const obs = document.getElementById('observacao').value.trim();
  const payload = {
    tamanho_id:    state.tamanhoId,
    sabor_id:      state.saborId,
    calda_id:      state.caldaId,
    complementos:  state.complementos.map(c => ({ id: c.id, foi_gratis: c.ehGratis ? 1 : 0, preco: c.preco })),
    preco_sabor:   state.saborPreco,
    acrescimo:     state.tamanhoAcrescimo,
    preco_calda:   state.caldaPreco,
    total:         calcularTotal(),
    observacao:    obs,
  };

  try {
    const res = await fetch('../api_pedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if(data.ok) {
      document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
      document.getElementById('screen-sucesso').classList.add('active');
      document.getElementById('suc-numero').textContent = String(data.numero).padStart(3,'0');
    } else {
      alert('Erro ao salvar pedido: ' + (data.erro || 'Tente novamente.'));
    }
  } catch(e) {
    alert('Erro de conexão. Verifique o servidor.');
  }
}

// ── Novo pedido ──────────────────────────────────────────────
function novoPedido() {
  state = { tamanhoId:null, tamanhoNome:'', tamanhoML:0, tamanhoAcrescimo:0, gratis:0,
            saborId:null, saborNome:'', saborEmoji:'', saborPreco:0, complementos:[],
            caldaId:null, caldaNome:'', caldaPreco:0 };
  document.querySelectorAll('.size-card, .type-card, .comp-card, .calda-card')
          .forEach(c => c.classList.remove('selected','gratis'));
  document.getElementById('btn-next1').disabled = true;
  document.getElementById('btn-next2').disabled = true;
  document.getElementById('btn-next4').disabled = true;
  document.getElementById('observacao').value = '';
  document.getElementById('screen-sucesso').classList.remove('active');
  goTo('tamanho');
}
</script>
</body>
</html>
