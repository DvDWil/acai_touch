<?php
// 1. Incluir a conexão e conectar ao banco
require_once 'conexao.php';
$pdo = conectar();

// 2. Buscar dados das tabelas (conforme definido no seu banco.sql)
$queryTamanhos = $pdo->query("SELECT * FROM tamanhos WHERE ativo = 1 ORDER BY ml ASC");
$tamanhosDB = $queryTamanhos->fetchAll();

$querySabores = $pdo->query("SELECT * FROM sabores WHERE ativo = 1");
$saboresDB = $querySabores->fetchAll();

$queryAdicionais = $pdo->query("SELECT * FROM adicionais WHERE ativo = 1");
$adicionaisDB = $queryAdicionais->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PontoAçaí - Sistema Dinâmico</title>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  /* Mantenho todos os seus estilos originais de pontoAcai.html */
  :root {
    --roxo: #3D1A6B; --roxo-medio: #6B2FA0; --roxo-claro: #9B59D0; --acai: #2C0E4E;
    --rosa: #E91E8C; --rosa-claro: #FF6EB4; --amarelo: #FFD700; --creme: #FFF8F0;
    --verde: #2ECC71; --vermelho: #E74C3C; --laranja: #FF7043; --bg: #1A0A30;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
  body { font-family: 'Nunito', sans-serif; background: var(--bg); min-height: 100vh; overflow-x: hidden; user-select: none; }
  /* ... (Estilos omitidos por brevidade, mas devem ser mantidos do seu original) ... */
  <?php include 'pontoAcai_styles.css'; // Dica: Pode mover o CSS para um arquivo separado ?>
</style>
</head>
<body>

<div id="app">
  <div class="header">
    <div class="logo"><span class="logo-emoji">🍇</span>Ponto<span>Açaí</span></div>
    <div class="step-indicator" id="steps">
      <div class="step-dot active" id="dot1"></div>
      <div class="step-dot" id="dot2"></div>
      <div class="step-dot" id="dot3"></div>
      <div class="step-dot" id="dot4"></div>
      <div class="step-dot" id="dot5"></div>
    </div>
  </div>

  <div class="screen active" id="screen-tamanho">
    <div class="progress-bar"><div class="progress-fill" style="width:16%"></div></div>
    <div class="screen-title">🍇 Qual o tamanho do seu copo?</div>
    <div class="size-grid" id="size-grid-container">
      <?php foreach($tamanhosDB as $t): ?>
      <div class="size-card" onclick="selectTamanho(this, '<?= $t['id'] ?>')">
        <?php if($t['popular']): ?><div class="popular-badge">⭐ MAIS PEDIDO</div><?php endif; ?>
        <div class="cup-icon">🥤</div>
        <div class="cup-ml"><?= $t['ml'] ?>ml</div>
        <div class="cup-price">+ R$ <?= number_format($t['acrescimo'], 2, ',', '.') ?></div>
        <div class="cup-label"><?= $t['nome'] ?></div>
        <div class="cup-gratis">✅ <?= $t['complementos_gratis'] ?> complementos grátis</div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-primary" id="btn-next1" onclick="goTo('sabor')" disabled>Próximo →</button>
    </div>
  </div>

  <div class="screen" id="screen-sabor">
    <div class="progress-bar"><div class="progress-fill" style="width:33%"></div></div>
    <div class="screen-title">🍓 Qual o seu açaí?</div>
    <div class="type-grid" id="sabor-grid-container">
      <?php foreach($saboresDB as $s): ?>
      <div class="type-card" onclick="selectSabor(this, '<?= $s['id'] ?>', '<?= $s['nome'] ?>', '<?= $s['emoji'] ?>', <?= $s['preco_base'] ?>)">
        <div class="type-emoji"><?= $s['emoji'] ?></div>
        <div class="type-info">
          <div class="type-name"><?= $s['nome'] ?></div>
          <div class="type-desc">Sabor intenso e selecionado</div>
          <div class="type-preco-badge">R$ <?= number_format($s['preco_base'], 2, ',', '.') ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('tamanho')">← Voltar</button>
      <button class="btn btn-primary" id="btn-next2" onclick="goTo('complementos')" disabled>Próximo →</button>
    </div>
  </div>

  <div class="screen" id="screen-complementos">
    <div class="progress-bar"><div class="progress-fill" style="width:50%"></div></div>
    <div class="screen-title">✨ Quais complementos?</div>
    <div class="comp-header-info" id="comp-info-banner">Selecione seus itens favoritos</div>
    <div class="comp-grid" id="comp-grid-container">
      <?php foreach($adicionaisDB as $a): ?>
      <?php $semEstoque = ($a['estoque_atual'] <= 0); ?>
      <div class="comp-card <?= $semEstoque ? 'sem-estoque' : '' ?>" 
           onclick="<?= $semEstoque ? '' : "toggleComp(this, '{$a['id']}', '{$a['nome']}', '{$a['emoji']}', {$a['preco']})" ?>">
        <div class="comp-emoji"><?= $a['emoji'] ?></div>
        <div class="comp-name"><?= $a['nome'] ?></div>
        <div class="comp-price"><?= $semEstoque ? '❌ Esgotado' : "+ R$ ".number_format($a['preco'], 2, ',', '.') ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('sabor')">← Voltar</button>
      <button class="btn btn-primary" onclick="goTo('calda')">Próximo →</button>
    </div>
  </div>

  </div>

<script>
// Passando os dados do PHP para o JavaScript para lógica de cálculo
const configTamanhos = <?= json_encode($tamanhosDB) ?>;
const configAdicionais = <?= json_encode($adicionaisDB) ?>;

let state = {
  currentItem: { complementos: [] },
  allItems: []
};

function selectTamanho(el, id) {
  document.querySelectorAll('.size-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const tam = configTamanhos.find(t => t.id == id);
  state.currentItem.tamanhoId = id;
  state.currentItem.tamanhoInfo = tam;
  document.getElementById('btn-next1').disabled = false;
}

function selectSabor(el, id, nome, emoji, preco) {
  document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  state.currentItem.saborId = id;
  state.currentItem.saborNome = nome;
  state.currentItem.saborEmoji = emoji;
  state.currentItem.saborPreco = preco;
  document.getElementById('btn-next2').disabled = false;
}

function toggleComp(el, id, nome, emoji, preco) {
  const idx = state.currentItem.complementos.findIndex(c => c.id == id);
  if (idx >= 0) {
    state.currentItem.complementos.splice(idx, 1);
    el.classList.remove('selected');
  } else {
    state.currentItem.complementos.push({ id, nome, emoji, preco });
    el.classList.add('selected');
  }
}

// Funções de navegação e resumo permanecem iguais às suas originais, 
// apenas garantindo que usam state.currentItem.tamanhoInfo.acrescimo para os cálculos.
function goTo(screen) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById('screen-'+screen).classList.add('active');
}
</script>
</body>
</html>