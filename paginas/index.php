<?php
// ============================================================
//  paginas/index.php  –  PontoAçaí – Sistema de Pedidos
//  C:\xampp\htdocs\acai_touch\paginas\index.php
// ============================================================
require_once '../config/conexao.php';
$pdo = conectar();

$tamanhosDB   = $pdo->query("SELECT * FROM tamanhos   WHERE ativo = 1 ORDER BY ml ASC")->fetchAll();
$saboresDB    = $pdo->query("SELECT * FROM sabores    WHERE ativo = 1 ORDER BY preco ASC, nome ASC")->fetchAll();
$adicionaisDB = $pdo->query("SELECT * FROM adicionais WHERE ativo = 1 ORDER BY nome ASC")->fetchAll();
$caldasDB     = $pdo->query("SELECT * FROM caldas     WHERE ativo = 1 ORDER BY preco ASC, preco_300 ASC")->fetchAll();
$bebidasDB    = $pdo->query("SELECT * FROM bebidas    WHERE ativo = 1 ORDER BY preco ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PontoAçaí</title>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --roxo:        #3D1A6B;
    --roxo-medio:  #6B2FA0;
    --roxo-claro:  #9B59D0;
    --acai:        #2C0E4E;
    --rosa:        #E91E8C;
    --rosa-claro:  #FF6EB4;
    --amarelo:     #FFD700;
    --verde:       #2ECC71;
    --vermelho:    #E74C3C;
    --bg:          #1A0A30;
  }
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
  body {
    font-family: 'Nunito', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    overflow-x: hidden;
    user-select: none;
    color: #fff;
  }
  body::before {
    content: '';
    position: fixed; inset: 0;
    background:
      radial-gradient(ellipse at 20% 50%, rgba(107,47,160,0.3) 0%, transparent 60%),
      radial-gradient(ellipse at 80% 20%, rgba(233,30,140,0.2) 0%, transparent 50%),
      radial-gradient(ellipse at 50% 90%, rgba(61,26,107,0.4) 0%, transparent 60%);
    pointer-events: none; z-index: 0;
  }
  .particle {
    position: fixed; width: 6px; height: 6px; border-radius: 50%;
    background: var(--rosa-claro); opacity: 0; pointer-events: none;
    z-index: 1; animation: float-up linear infinite;
  }
  @keyframes float-up {
    0%   { opacity: 0; transform: translateY(100vh) scale(0); }
    10%  { opacity: 0.6; }
    90%  { opacity: 0.2; }
    100% { opacity: 0; transform: translateY(-10vh) scale(1.5); }
  }
  #app { position: relative; z-index: 10; min-height: 100vh; display: flex; flex-direction: column; }

  /* HEADER */
  .header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 30px;
    background: rgba(26,10,48,0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(155,89,208,0.3);
  }
  .logo { font-family: 'Baloo 2', cursive; font-size: 2rem; font-weight: 900; color: #fff; letter-spacing: -1px; display: flex; align-items: center; gap: 6px; }
  .logo span { color: var(--rosa); }
  .step-indicator { display: flex; gap: 8px; align-items: center; }
  .step-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.2); transition: all 0.3s; }
  .step-dot.active { background: var(--rosa); transform: scale(1.3); }
  .step-dot.done { background: var(--verde); }

  /* TELAS */
  .screen { display: none; flex: 1; flex-direction: column; align-items: center; padding: 30px 20px 40px; animation: fadeSlide 0.4s ease; }
  .screen.active { display: flex; }
  @keyframes fadeSlide { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
  .screen-title { font-family: 'Baloo 2', cursive; font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: 8px; }
  .screen-sub { font-size: 1rem; color: rgba(255,255,255,0.6); text-align: center; margin-bottom: 30px; }
  .progress-bar { width: 100%; max-width: 700px; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; margin-bottom: 24px; overflow: hidden; }
  .progress-fill { height: 100%; background: linear-gradient(90deg, var(--rosa), var(--roxo-claro)); border-radius: 2px; transition: width 0.4s ease; }

  /* TELA 1: TAMANHO */
  .size-grid { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 900px; }
  .size-card {
    background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3);
    border-radius: 24px; padding: 30px 25px;
    display: flex; flex-direction: column; align-items: center; gap: 12px;
    cursor: pointer; transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    flex: 1; min-width: 220px; max-width: 260px; position: relative; overflow: hidden;
  }
  .size-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, var(--roxo-medio), var(--rosa)); opacity: 0; transition: opacity 0.3s; border-radius: 22px; }
  .size-card:hover, .size-card.selected { border-color: var(--rosa); transform: translateY(-8px) scale(1.03); box-shadow: 0 20px 50px rgba(233,30,140,0.3); }
  .size-card:hover::before, .size-card.selected::before { opacity: 1; }
  .size-card > * { position: relative; z-index: 1; }
  .popular-badge { position: absolute; top: -1px; right: 14px; background: var(--amarelo); color: #2C0E4E; font-size: 0.7rem; font-weight: 900; padding: 4px 12px; border-radius: 0 0 10px 10px; letter-spacing: 0.5px; z-index: 2; }
  .cup-icon { font-size: 4rem; line-height: 1; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4)); }
  .cup-ml { font-family: 'Baloo 2', cursive; font-size: 2rem; font-weight: 900; }
  .cup-price { font-size: 1rem; font-weight: 800; color: var(--amarelo); text-align: center; line-height: 1.3; }
  .cup-label { font-size: 0.85rem; color: rgba(255,255,255,0.7); }
  .cup-gratis { font-size: 0.78rem; color: var(--verde); font-weight: 700; background: rgba(46,204,113,0.15); padding: 3px 10px; border-radius: 20px; text-align: center; }
  .cup-caldas  { font-size: 0.75rem; color: #87ceeb; font-weight: 700; background: rgba(135,206,235,0.12); padding: 3px 10px; border-radius: 20px; text-align: center; }

  /* TELA 2: SABOR */
  .type-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; width: 100%; max-width: 800px; }
  .type-card {
    background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3);
    border-radius: 20px; padding: 20px 18px;
    display: flex; align-items: center; gap: 14px;
    cursor: pointer; transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; overflow: hidden;
  }
  .type-card::before { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity 0.3s; border-radius: 18px; }
  .type-card.morango::before   { background: linear-gradient(135deg, #8B0000, #E91E8C); }
  .type-card.casadinho::before { background: linear-gradient(135deg, #3E1F00, #7B3F00); }
  .type-card.semacucar::before { background: linear-gradient(135deg, #1a3a1a, #2e7d32); }
  .type-card.tradicional::before { background: linear-gradient(135deg, #2C0E4E, #6B2FA0); }
  .type-card.default::before   { background: linear-gradient(135deg, var(--roxo-medio), var(--rosa)); }
  .type-card:hover, .type-card.selected { border-color: var(--rosa); transform: translateY(-5px) scale(1.02); box-shadow: 0 15px 40px rgba(233,30,140,0.25); }
  .type-card:hover::before, .type-card.selected::before { opacity: 1; }
  .type-card > * { position: relative; z-index: 1; }
  .type-emoji { font-size: 2.6rem; flex-shrink: 0; }
  .type-info { flex: 1; }
  .type-name { font-family: 'Baloo 2', cursive; font-size: 1.1rem; font-weight: 800; line-height: 1.2; }
  .type-desc { font-size: 0.8rem; color: rgba(255,255,255,0.65); margin-top: 2px; }
  .type-price-badge { background: rgba(255,215,0,0.18); color: var(--amarelo); font-size: 0.82rem; font-weight: 800; padding: 4px 12px; border-radius: 20px; white-space: nowrap; flex-shrink: 0; }

  /* TELA 3: COMPLEMENTOS */
  .comp-banner { background: rgba(46,204,113,0.1); border: 1px solid rgba(46,204,113,0.3); border-radius: 14px; padding: 12px 20px; margin-bottom: 20px; text-align: center; font-size: 0.95rem; width: 100%; max-width: 900px; }
  .comp-banner strong { color: var(--verde); }
  .comp-counter-txt { font-family: 'Baloo 2', cursive; font-size: 1rem; font-weight: 800; color: var(--amarelo); margin-top: 4px; }
  .comp-extra-info { font-size: 0.82rem; color: rgba(255,255,255,0.45); margin-top: 4px; }
  .comp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; width: 100%; max-width: 900px; }
  .comp-card {
    background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3);
    border-radius: 18px; padding: 20px 14px;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    cursor: pointer; transition: all 0.2s; position: relative; text-align: center;
  }
  .comp-card.selected { border-color: var(--verde); background: rgba(46,204,113,0.15); box-shadow: 0 8px 25px rgba(46,204,113,0.2); }
  .comp-card.selected::after { content: '✓'; position: absolute; top: 8px; right: 10px; width: 22px; height: 22px; background: var(--verde); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 900; line-height: 22px; }
  .comp-card.sem-estoque { opacity: 0.35; cursor: not-allowed; }
  .comp-card:hover:not(.sem-estoque) { transform: translateY(-4px); border-color: var(--roxo-claro); }
  .comp-emoji { font-size: 2.4rem; }
  .comp-name  { font-weight: 800; font-size: 0.95rem; }
  .comp-price { font-size: 0.82rem; color: var(--amarelo); font-weight: 700; }
  .comp-price.gratis-label { color: var(--verde); }
  .comp-stock { font-size: 0.7rem; color: rgba(255,255,255,0.3); }
  .badge-gratis { position: absolute; top: -1px; left: -1px; background: var(--verde); color: #fff; font-size: 0.6rem; font-weight: 900; padding: 3px 8px; border-radius: 16px 0 10px 0; display: none; }
  .comp-card.gratis .badge-gratis { display: block; }

  /* TELA 4: CALDA */
  .calda-grid { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 900px; }
  .calda-card {
    background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3);
    border-radius: 22px; padding: 24px 18px;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    cursor: pointer; transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    flex: 1; min-width: 150px; max-width: 190px; text-align: center;
    position: relative; overflow: hidden;
  }
  .calda-card::before { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity 0.3s; border-radius: 20px; }
  .calda-card.leite::before   { background: linear-gradient(135deg, #5C2E91, #9B59D0); }
  .calda-card.choco::before   { background: linear-gradient(135deg, #3E1F00, #7B3F00); }
  .calda-card.morangoc::before { background: linear-gradient(135deg, #8B0000, #E91E8C); }
  .calda-card.nutella::before { background: linear-gradient(135deg, #3E1F00, #7B3F00); }
  .calda-card.semcalda::before { background: linear-gradient(135deg, #222, #444); }
  .calda-card.default-calda::before { background: linear-gradient(135deg, var(--roxo-medio), var(--rosa)); }
  .calda-card:hover, .calda-card.selected { border-color: var(--rosa); transform: translateY(-8px) scale(1.03); box-shadow: 0 20px 50px rgba(233,30,140,0.3); }
  .calda-card:hover::before, .calda-card.selected::before { opacity: 1; }
  .calda-card > * { position: relative; z-index: 1; }
  .calda-emoji { font-size: 2.6rem; }
  .calda-nome { font-family: 'Baloo 2', cursive; font-size: 1.05rem; font-weight: 800; }
  .calda-price-badge { font-size: 0.78rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; color: #fff; }
  .calda-price-badge.gratis { background: var(--verde); }
  .calda-price-badge.pago   { background: var(--rosa); }
  .calda-aviso-gratis { font-size: 0.68rem; color: rgba(255,255,255,0.5); margin-top: -4px; }

  /* TELA 5: BEBIDAS */
  .beb-grid { display: flex; gap: 18px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 800px; }
  .beb-card {
    background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3);
    border-radius: 22px; padding: 28px 22px;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    cursor: pointer; transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    flex: 1; min-width: 180px; max-width: 220px; text-align: center;
    position: relative; overflow: hidden;
  }
  .beb-card::before { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity 0.3s; border-radius: 20px; background: linear-gradient(135deg, #0a3d62, #1e88e5); }
  .beb-card.sem-beb::before { background: linear-gradient(135deg, #222, #444); }
  .beb-card:hover, .beb-card.selected { border-color: #1e88e5; transform: translateY(-8px) scale(1.03); box-shadow: 0 20px 50px rgba(30,136,229,0.3); }
  .beb-card.sem-beb:hover, .beb-card.sem-beb.selected { border-color: rgba(255,255,255,0.4); }
  .beb-card:hover::before, .beb-card.selected::before { opacity: 1; }
  .beb-card > * { position: relative; z-index: 1; }
  .beb-emoji { font-size: 3rem; }
  .beb-nome  { font-family: 'Baloo 2', cursive; font-size: 1rem; font-weight: 800; }
  .beb-desc  { font-size: 0.78rem; color: rgba(255,255,255,0.6); }
  .beb-price { font-size: 0.85rem; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
  .beb-price.gratis { background: var(--verde); color: #fff; }
  .beb-price.pago   { background: rgba(255,215,0,0.2); color: var(--amarelo); }

  /* TELA MAIS UM */
  .mais-grid { display: flex; gap: 20px; justify-content: center; width: 100%; max-width: 600px; }
  .mais-card { background: rgba(255,255,255,0.05); border: 2px solid rgba(155,89,208,0.3); border-radius: 24px; padding: 30px; flex: 1; cursor: pointer; text-align: center; transition: all 0.3s; }
  .mais-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.1); }
  .mais-card.sim { border-color: var(--rosa); }
  .mais-card.nao { border-color: var(--verde); }
  .mais-emoji { font-size: 3.5rem; margin-bottom: 10px; }
  .mais-label { font-family: 'Baloo 2', cursive; font-size: 1.8rem; font-weight: 900; }
  .mais-sub { font-size: 0.9rem; color: rgba(255,255,255,0.6); }

  /* RESUMO */
  .resumo-container { width: 100%; max-width: 700px; display: flex; flex-direction: column; gap: 16px; }
  .resumo-box { background: rgba(255,255,255,0.07); border: 1px solid rgba(155,89,208,0.2); border-radius: 16px; overflow: hidden; }
  .resumo-header { background: rgba(233,30,140,0.2); padding: 12px 18px; font-family: 'Baloo 2', cursive; font-size: 1rem; font-weight: 800; border-bottom: 1px solid rgba(255,255,255,0.05); }
  .resumo-linha { display: flex; justify-content: space-between; align-items: center; padding: 11px 18px; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.9rem; }
  .resumo-linha:last-child { border-bottom: none; }
  .resumo-label { color: rgba(255,255,255,0.6); }
  .resumo-valor { font-weight: 700; }
  .resumo-valor.gratis { color: var(--verde); }
  .resumo-total { background: linear-gradient(135deg, var(--roxo-medio), var(--acai)); padding: 18px; text-align: center; border-top: 1px solid rgba(255,255,255,0.08); }
  .resumo-total-label { font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-bottom: 4px; }
  .resumo-total-valor { font-family: 'Baloo 2', cursive; font-size: 2.2rem; font-weight: 900; color: var(--amarelo); }
  .obs-input { width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(155,89,208,0.3); border-radius: 12px; padding: 12px 16px; color: #fff; font-family: 'Nunito', sans-serif; font-size: 0.9rem; resize: none; outline: none; }
  .obs-input:focus { border-color: var(--roxo-claro); }
  .obs-input::placeholder { color: rgba(255,255,255,0.3); }

  /* SUCESSO */
  .sucesso-box { display: flex; flex-direction: column; align-items: center; gap: 16px; text-align: center; max-width: 500px; }
  .sucesso-icon { font-size: 6rem; animation: bounce 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
  @keyframes bounce { 0%{transform:scale(0)} 60%{transform:scale(1.2)} 100%{transform:scale(1)} }
  .sucesso-num-box { background: linear-gradient(135deg, var(--rosa), var(--roxo-medio)); border-radius: 20px; padding: 20px 48px; }
  .sucesso-num-label { font-size: 0.9rem; color: rgba(255,255,255,0.8); }
  .sucesso-num-val { font-family: 'Baloo 2', cursive; font-size: 3.5rem; font-weight: 900; line-height: 1; }

  /* BOTÕES */
  .btn-group { display: flex; gap: 14px; margin-top: 24px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 700px; }
  .btn { font-family: 'Nunito', sans-serif; font-size: 1.1rem; font-weight: 800; padding: 16px 36px; border: none; border-radius: 50px; cursor: pointer; transition: all 0.2s; letter-spacing: 0.3px; min-width: 160px; }
  .btn-primary { background: linear-gradient(135deg, var(--rosa), var(--roxo-medio)); color: #fff; box-shadow: 0 8px 25px rgba(233,30,140,0.4); }
  .btn-primary:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(233,30,140,0.5); }
  .btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }
  .btn-secondary { background: rgba(255,255,255,0.1); color: #fff; border: 1.5px solid rgba(255,255,255,0.2); }
  .btn-secondary:hover { background: rgba(255,255,255,0.18); transform: translateY(-2px); }
  .btn-success { background: linear-gradient(135deg, #27AE60, #2ECC71); color: #fff; box-shadow: 0 8px 25px rgba(46,204,113,0.4); font-size: 1.15rem; padding: 18px 48px; }
  .btn-success:hover { transform: translateY(-3px); box-shadow: 0 14px 35px rgba(46,204,113,0.5); }
  .btn-home { background: linear-gradient(135deg, var(--roxo-medio), var(--roxo-claro)); color: #fff; box-shadow: 0 8px 25px rgba(107,47,160,0.4); padding: 18px 40px; margin-top: 8px; }
  .btn-home:hover { transform: translateY(-3px); }
  .btn:active { transform: scale(0.97) !important; }

  @media (max-width: 600px) {
    .comp-grid  { grid-template-columns: repeat(2, 1fr); }
    .type-grid  { grid-template-columns: 1fr; }
    .size-grid  { flex-direction: column; align-items: center; }
    .size-card  { width: 100%; max-width: 100%; min-width: unset; }
    .calda-grid { flex-wrap: wrap; }
    .calda-card { max-width: calc(50% - 8px); min-width: 140px; }
    .beb-grid   { flex-direction: column; align-items: center; }
    .beb-card   { max-width: 100%; width: 100%; }
    .screen-title { font-size: 1.6rem; }
    .header { padding: 14px 16px; }
    .logo { font-size: 1.5rem; }
  }

  /* IMPRESSÃO */
  #nota-impressao { display: none; }
  @media print {
    @page { size: 80mm auto; margin: 0; }
    body > * { display: none !important; }
    #nota-impressao { display: block !important; font-family: 'Courier New', Courier, monospace; font-size: 13px; color: #000; background: #fff; width: 80mm; margin: 0 auto; padding: 6mm 4mm; line-height: 1.6; }
    .nota-center  { text-align: center; }
    .nota-bold    { font-weight: bold; }
    .nota-grande  { font-size: 17px; font-weight: bold; text-align: center; }
    .nota-numero  { font-size: 30px; font-weight: bold; text-align: center; letter-spacing: 3px; }
    .nota-hr      { border: none; border-top: 1px dashed #000; margin: 5px 0; }
    .nota-linha   { display: flex; justify-content: space-between; }
    .nota-gratis  { font-style: italic; color: #333; }
    .nota-total   { display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; border-top: 2px solid #000; padding-top: 4px; margin-top: 4px; }
    .nota-obs     { font-style: italic; font-size: 11px; }
    .nota-rodape  { text-align: center; font-size: 11px; margin-top: 10px; }
  }

  /* ADMIN */
  .admin-btn { position: fixed; top: 16px; right: 20px; background: rgba(255,255,255,0.07); border: 1px solid rgba(155,89,208,0.35); color: rgba(255,255,255,0.45); font-size: 0.72rem; font-weight: 700; padding: 5px 11px; border-radius: 8px; cursor: pointer; z-index: 150; font-family: 'Nunito', sans-serif; transition: all 0.2s; }
  .admin-btn:hover { color: #fff; background: rgba(155,89,208,0.25); }
  #admin-panel { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); z-index: 200; overflow-y: auto; padding: 20px; }
  #admin-panel.open { display: flex; align-items: flex-start; justify-content: center; }
  .admin-box { background: #1e0b3a; border: 1px solid rgba(155,89,208,0.4); border-radius: 20px; padding: 28px; width: 100%; max-width: 780px; color: #fff; margin: auto; }
  .admin-title { font-family: 'Baloo 2', cursive; font-size: 1.6rem; font-weight: 800; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
  .admin-close { background: var(--vermelho); border: none; color: #fff; font-size: 1.1rem; width: 34px; height: 34px; border-radius: 50%; cursor: pointer; font-weight: 900; line-height: 34px; text-align: center; }
  .admin-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
  .admin-tab { padding: 8px 18px; background: rgba(255,255,255,0.06); border: 1px solid rgba(155,89,208,0.3); border-radius: 20px; cursor: pointer; font-size: 0.88rem; color: rgba(255,255,255,0.7); font-family: 'Nunito', sans-serif; font-weight: 700; transition: all 0.2s; }
  .admin-tab.active { background: var(--rosa); border-color: var(--rosa); color: #fff; }
  .admin-tab-content { display: none; }
  .admin-tab-content.active { display: block; }
  .admin-section { margin-bottom: 24px; }
  .admin-section h3 { font-size: 0.95rem; color: var(--rosa-claro); margin-bottom: 12px; font-weight: 800; }
  .admin-section p.hint { font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-bottom: 12px; line-height: 1.5; }
  .admin-row { display: flex; gap: 10px; align-items: center; background: rgba(255,255,255,0.05); border-radius: 10px; padding: 10px 14px; margin-bottom: 8px; flex-wrap: wrap; }
  .admin-row label { flex: 1; font-size: 0.9rem; min-width: 140px; }
  .admin-row input[type="number"] { background: rgba(255,255,255,0.1); border: 1px solid rgba(155,89,208,0.4); border-radius: 8px; padding: 6px 12px; color: #fff; font-size: 0.9rem; width: 90px; font-family: 'Nunito', sans-serif; }
  .admin-row .unit { font-size: 0.8rem; color: rgba(255,255,255,0.45); }
  .estoque-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; }
  .estoque-ok    { background: rgba(46,204,113,0.2);  color: var(--verde); }
  .estoque-baixo { background: rgba(255,165,0,0.2);   color: #FFA500; }
  .estoque-zero  { background: rgba(231,76,60,0.2);   color: var(--vermelho); }
  .vendas-item { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 0.88rem; }
  .vendas-item:last-child { border: none; }
  .vendas-total-row { display: flex; justify-content: space-between; align-items: center; background: rgba(233,30,140,0.1); border: 1px solid rgba(233,30,140,0.3); border-radius: 10px; padding: 12px 16px; margin-top: 12px; }
  .vendas-total-row .label { font-weight: 800; font-size: 0.95rem; }
  .vendas-total-val { font-family: 'Baloo 2', cursive; font-size: 1.4rem; font-weight: 900; color: var(--amarelo); }
  @media (max-width: 600px) {
    .admin-tabs { gap: 6px; }
    .admin-tab  { font-size: 0.78rem; padding: 6px 12px; }
    .admin-box  { padding: 18px 14px; }
  }
</style>
</head>
<body>

<script>
  for(let i=0;i<12;i++){
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random()*100+'vw';
    p.style.animationDuration = (8+Math.random()*12)+'s';
    p.style.animationDelay = (Math.random()*15)+'s';
    p.style.width = p.style.height = (4+Math.random()*6)+'px';
    p.style.background = ['#FF6EB4','#9B59D0','#FFD700','#E91E8C'][Math.floor(Math.random()*4)];
    document.body.appendChild(p);
  }
</script>

<button class="admin-btn" onclick="toggleAdmin()">⚙ Admin</button>

<div id="app">
  <div class="header">
    <div class="logo">
      <img src="/acai_touch/assets/images/logo.png" alt="PontoAçaí" style="height:69px;">
    </div>
    <div class="step-indicator">
      <div class="step-dot active" id="dot1"></div>
      <div class="step-dot" id="dot2"></div>
      <div class="step-dot" id="dot3"></div>
      <div class="step-dot" id="dot4"></div>
      <div class="step-dot" id="dot5"></div>
      <div class="step-dot" id="dot6"></div>
    </div>
  </div>

  <!-- ═══ TELA 1: TAMANHO ═══ -->
  <div class="screen active" id="screen-tamanho">
    <div class="progress-bar"><div class="progress-fill" style="width:14%"></div></div>
    <div class="screen-title">🥤 Qual o tamanho do seu copo?</div>
    <p class="screen-sub">O tamanho define quantos acompanhamentos você ganha grátis</p>
    <div class="size-grid">
      <?php foreach($tamanhosDB as $t): ?>
      <div class="size-card" onclick="selectTamanho(this, <?= $t['id'] ?>)">
        <?php if($t['popular']): ?>
          <div class="popular-badge">⭐ MAIS PEDIDO</div>
        <?php endif; ?>
        <div class="cup-icon">🥤</div>
        <div class="cup-ml"><?= $t['ml'] ?>ml</div>
        <?php if($t['acrescimo'] > 0): ?>
          <div class="cup-price">+ R$ <?= number_format($t['acrescimo'],2,',','.') ?> no sabor</div>
        <?php else: ?>
          <div class="cup-price" style="color:var(--verde);">Preço base do sabor</div>
        <?php endif; ?>
        <div class="cup-label"><?= htmlspecialchars($t['nome']) ?></div>
        <div class="cup-gratis">✅ <?= $t['complementos_gratis'] ?> acomp. grátis</div>
        <div class="cup-caldas">🍯 <?= $t['caldas_gratis'] ?? 1 ?> calda<?= ($t['caldas_gratis'] ?? 1) > 1 ? 's' : '' ?> grátis</div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-primary" id="btn-next1" onclick="goTo('sabor')" disabled>Próximo →</button>
    </div>
  </div>

  <!-- ═══ TELA 2: SABOR ═══ -->
  <div class="screen" id="screen-sabor">
    <div class="progress-bar"><div class="progress-fill" style="width:28%"></div></div>
    <div class="screen-title">🍓 Qual o seu açaí?</div>
    <p class="screen-sub">Preço base · acréscimo do tamanho será somado</p>
    <div class="type-grid">
      <?php
        $classesSabor = ['morango'=>'morango','casadinho'=>'casadinho','açúcar'=>'semacucar','acucar'=>'semacucar','tradicional'=>'tradicional'];
        foreach($saboresDB as $s):
          $nomeLower = mb_strtolower($s['nome']);
          $classe = 'default';
          foreach($classesSabor as $k => $v) {
            if(str_contains($nomeLower, $k)) { $classe = $v; break; }
          }
      ?>
      <div class="type-card <?= $classe ?>" onclick="selectSabor(this, <?= $s['id'] ?>, <?= $s['preco'] ?>)">
        <div class="type-emoji"><?= $s['emoji'] ?></div>
        <div class="type-info">
          <div class="type-name"><?= htmlspecialchars($s['nome']) ?></div>
          <?php if(!empty($s['descricao'])): ?>
            <div class="type-desc"><?= htmlspecialchars($s['descricao']) ?></div>
          <?php endif; ?>
        </div>
        <div class="type-price-badge" id="sabor-preco-<?= $s['id'] ?>">R$ <?= number_format($s['preco_300'] ?? $s['preco'],2,',','.') ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('tamanho')">← Voltar</button>
      <button class="btn btn-primary" id="btn-next2" onclick="goTo('complementos')" disabled>Próximo →</button>
    </div>
  </div>

  <!-- ═══ TELA 3: COMPLEMENTOS ═══ -->
  <div class="screen" id="screen-complementos">
    <div class="progress-bar"><div class="progress-fill" style="width:43%"></div></div>
    <div class="screen-title">✨ Quais acompanhamentos?</div>
    <div class="comp-banner" id="comp-banner">
      <div>Escolha à vontade · os primeiros são <strong>grátis</strong>!</div>
      <div class="comp-counter-txt" id="comp-counter">—</div>
      <div class="comp-extra-info">Acompanhamento extra: R$ 1,90 cada</div>
    </div>
    <div class="comp-grid">
      <?php foreach($adicionaisDB as $a):
        $esgotado = ($a['estoque_atual'] <= 0);
      ?>
      <div class="comp-card <?= $esgotado ? 'sem-estoque' : '' ?>"
           id="comp-<?= $a['id'] ?>"
           <?php if(!$esgotado): ?>onclick="toggleComp(this, <?= $a['id'] ?>)"<?php endif; ?>>
        <div class="badge-gratis">GRÁTIS</div>
        <div class="comp-emoji"><?= $a['emoji'] ?></div>
        <div class="comp-name"><?= htmlspecialchars($a['nome']) ?></div>
        <div class="comp-price" id="comp-price-<?= $a['id'] ?>">
          <?= $esgotado ? '❌ Esgotado' : '+ R$ '.number_format($a['preco'],2,',','.') ?>
        </div>
        <?php if(!$esgotado): ?>
          <div class="comp-stock">estoque: <?= $a['estoque_atual'] ?></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('sabor')">← Voltar</button>
      <button class="btn btn-primary" onclick="goTo('calda')">Próximo →</button>
    </div>
  </div>

  <!-- ═══ TELA 4: CALDA ═══ -->
  <div class="screen" id="screen-calda">
    <div class="progress-bar"><div class="progress-fill" style="width:57%"></div></div>
    <div class="screen-title">🍯 E a calda?</div>
    <p class="screen-sub" id="calda-sub">Finaliza com estilo</p>
    <div class="calda-grid" id="calda-grid">
      <?php
        $classesCalda = ['leite'=>'leite','chocolate'=>'choco','morango'=>'morangoc','nutella'=>'nutella','sem'=>'semcalda'];
        foreach($caldasDB as $c):
          $nomeLower = mb_strtolower($c['nome']);
          $classe = 'default-calda';
          foreach($classesCalda as $k => $v) {
            if(str_contains($nomeLower, $k)) { $classe = $v; break; }
          }
          // Preço da Nutella é dinâmico (por tamanho), renderizamos via JS
          $isNutella = str_contains($nomeLower, 'nutella');
      ?>
      <div class="calda-card <?= $classe ?>"
           id="calda-card-<?= $c['id'] ?>"
           onclick="selectCalda(this, <?= $c['id'] ?>, <?= $isNutella ? "'nutella'" : $c['preco'] ?>, <?= $isNutella ? 'true' : 'false' ?>)">
        <div class="calda-emoji"><?= $c['emoji'] ?></div>
        <div class="calda-nome"><?= htmlspecialchars($c['nome']) ?></div>
        <div class="calda-price-badge gratis" id="calda-badge-<?= $c['id'] ?>">
          <?php if($isNutella): ?>
            <span id="calda-nutella-price">+ R$ ?</span>
          <?php else: ?>
            <?= $c['preco'] > 0 ? '+ R$ '.number_format($c['preco'],2,',','.') : 'Incluído ✅' ?>
          <?php endif; ?>
        </div>
        <?php if($isNutella): ?>
          <div class="calda-aviso-gratis" id="calda-nutella-aviso">Adicional conforme tamanho</div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('complementos')">← Voltar</button>
      <button class="btn btn-primary" id="btn-next4" onclick="goTo('bebida')" disabled>Próximo →</button>
    </div>
  </div>

  <!-- ═══ TELA 5: BEBIDA ═══ -->
  <div class="screen" id="screen-bebida">
    <div class="progress-bar"><div class="progress-fill" style="width:75%"></div></div>
    <div class="screen-title">💧 Quer uma bebida?</div>
    <p class="screen-sub">Opcional · água mineral disponível</p>
    <div class="beb-grid">
      <!-- Opção sem bebida -->
<div class="beb-card sem-beb" id="beb-0">
  <div class="beb-emoji">🚫</div>
  <div class="beb-nome">Sem bebida</div>
  <div class="beb-desc">Só o açaí mesmo!</div>
  <div class="beb-price gratis">Sem adicional</div>
</div>
<?php foreach($bebidasDB as $b): ?>
<div class="beb-card" id="beb-<?= $b['id'] ?>"
     data-id="<?= $b['id'] ?>"
     data-nome="<?= htmlspecialchars($b['nome'], ENT_QUOTES, 'UTF-8') ?>"
     data-preco="<?= $b['preco'] ?>">
  <div class="beb-emoji"><?= $b['emoji'] ?></div>
  <div class="beb-nome"><?= htmlspecialchars($b['nome']) ?></div>
  <?php if(!empty($b['descricao'])): ?>
    <div class="beb-desc"><?= htmlspecialchars($b['descricao']) ?></div>
  <?php endif; ?>
  <div class="beb-price pago">+ R$ <?= number_format($b['preco'],2,',','.') ?></div>
</div>
<?php endforeach; ?>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('calda')">← Voltar</button>
      <button class="btn btn-primary" id="btn-next5" onclick="goTo('mais')" disabled>Próximo →</button>
    </div>
  </div>

  <!-- ═══ TELA 6: MAIS UM? ═══ -->
  <div class="screen" id="screen-mais">
    <div class="progress-bar"><div class="progress-fill" style="width:85%"></div></div>
    <div class="screen-title">🙌 Deseja mais um açaí?</div>
    <div class="screen-sub">Seu primeiro copo já está salvo no pedido</div>
    <div class="mais-grid">
      <div class="mais-card sim" onclick="maisUm(true)">
        <div class="mais-emoji">🥤</div>
        <div class="mais-label">SIM!</div>
        <div class="mais-sub">Adicionar outro copo</div>
      </div>
      <div class="mais-card nao" onclick="maisUm(false)">
        <div class="mais-emoji">✅</div>
        <div class="mais-label">NÃO</div>
        <div class="mais-sub">Ir para o pagamento</div>
      </div>
    </div>
  </div>

  <!-- ═══ TELA 7: RESUMO ═══ -->
  <div class="screen" id="screen-resumo">
    <div class="progress-bar"><div class="progress-fill" style="width:100%"></div></div>
    <div class="screen-title">📋 Seu Pedido</div>
    <p class="screen-sub">Confira tudo antes de confirmar</p>
    <div class="resumo-container">
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
        <div class="resumo-header">✨ Acompanhamentos</div>
        <div id="r-comps-lista">
          <div class="resumo-linha"><span class="resumo-label" style="color:rgba(255,255,255,0.3)">Nenhum selecionado</span></div>
        </div>
      </div>
      <div class="resumo-box" id="r-bebida-box" style="display:none">
        <div class="resumo-header">💧 Bebida</div>
        <div class="resumo-linha">
          <span class="resumo-label" id="r-bebida-nome">—</span>
          <span class="resumo-valor" id="r-bebida-preco">R$ 0,00</span>
        </div>
      </div>
      <div class="resumo-box">
        <div class="resumo-header">💰 Valores</div>
        <div class="resumo-linha">
          <span class="resumo-label">Açaí (sabor)</span>
          <span class="resumo-valor" id="r-preco-sabor">R$ 0,00</span>
        </div>
        <div class="resumo-linha">
          <span class="resumo-label">Acréscimo do tamanho</span>
          <span class="resumo-valor" id="r-acrescimo">R$ 0,00</span>
        </div>
        <div class="resumo-linha">
          <span class="resumo-label">Acomp. extras</span>
          <span class="resumo-valor" id="r-preco-comps">R$ 0,00</span>
        </div>
        <div class="resumo-linha">
          <span class="resumo-label">Calda extra</span>
          <span class="resumo-valor" id="r-preco-calda">R$ 0,00</span>
        </div>
        <div class="resumo-linha" id="r-beb-linha" style="display:none">
          <span class="resumo-label">Bebida</span>
          <span class="resumo-valor" id="r-preco-beb">R$ 0,00</span>
        </div>
        <div class="resumo-total">
          <div class="resumo-total-label">💜 Total do Pedido</div>
          <div class="resumo-total-valor" id="r-total">R$ 0,00</div>
        </div>
      </div>
      <textarea class="obs-input" id="observacao" rows="3" placeholder="Alguma observação? (opcional)"></textarea>
    </div>
    <div class="btn-group">
      <button class="btn btn-secondary" onclick="goTo('bebida')">← Voltar</button>
      <button class="btn btn-success" onclick="confirmarPedido()">✅ Confirmar Pedido</button>
    </div>
  </div>

  <!-- ═══ TELA SUCESSO ═══ -->
  <div class="screen" id="screen-sucesso">
    <div class="sucesso-box">
      <div class="sucesso-icon">🎉</div>
      <div class="screen-title" style="color:var(--verde)">Pedido Realizado!</div>
      <p class="screen-sub">Seu açaí está sendo preparado com carinho 💜</p>
      <div class="sucesso-num-box">
        <div class="sucesso-num-label">Número do seu pedido</div>
        <div class="sucesso-num-val" id="suc-numero">#0</div>
      </div>
      <p style="color:rgba(255,255,255,0.5);font-size:.9rem">Aguarde ser chamado!</p>
      <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:8px">
        <button class="btn btn-success" onclick="imprimirNota()" style="padding:14px 30px;font-size:1rem">🖨️ Imprimir Notinha</button>
        <button class="btn btn-home" onclick="novoPedido()">🏠 Novo Pedido</button>
      </div>
    </div>
  </div>
</div><!-- #app -->

<div id="nota-impressao"></div>

<!-- ═══ DADOS PHP → JS ═══ -->
<script>
const CFG_TAMANHOS   = <?= json_encode($tamanhosDB,   JSON_UNESCAPED_UNICODE) ?>;
const CFG_SABORES    = <?= json_encode($saboresDB,    JSON_UNESCAPED_UNICODE) ?>;
const CFG_ADICIONAIS = <?= json_encode($adicionaisDB, JSON_UNESCAPED_UNICODE) ?>;
const CFG_CALDAS     = <?= json_encode($caldasDB,     JSON_UNESCAPED_UNICODE) ?>;
const CFG_BEBIDAS    = <?= json_encode($bebidasDB,    JSON_UNESCAPED_UNICODE) ?>;

// ── ESTADO ──────────────────────────────────────────────────
let state = {
  tamanhoId: null, tamanhoNome: '', tamanhoML: 0, tamanhoAcrescimo: 0, gratis: 0, caldasGratis: 1,
  saborId: null, saborNome: '', saborEmoji: '', saborPreco: 0,
  complementos: [],
  caldaId: null, caldaNome: '', caldaPreco: 0,
  bebidaId: null, bebidaNome: '', bebidaPreco: 0,
  itensConfirmados: [],
  totalPedidoAcumulado: 0
};

// ── NAVEGAÇÃO ────────────────────────────────────────────────
const STEPS = ['tamanho','sabor','complementos','calda','bebida','mais','resumo'];
function goTo(screen) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById('screen-'+screen).classList.add('active');
  const idx = STEPS.indexOf(screen);
  document.querySelectorAll('.step-dot').forEach((d, i) => {
    d.classList.remove('active','done');
    if(i < idx) d.classList.add('done');
    if(i === idx) d.classList.add('active');
  });
  if(screen === 'complementos') atualizarBannerComps();
  if(screen === 'calda') atualizarCaldas();
  if(screen === 'resumo') montarResumo();
  window.scrollTo(0,0);
}

// ── TAMANHO ──────────────────────────────────────────────────
function selectTamanho(el, id) {
  document.querySelectorAll('.size-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const t = CFG_TAMANHOS.find(x => x.id == id);
  state.tamanhoId        = id;
  state.tamanhoNome      = t.nome;
  state.tamanhoML        = t.ml;
  state.tamanhoAcrescimo = 0; // agora o preço já é por tamanho, sem acréscimo separado
  state.gratis           = t.complementos_gratis;
  state.caldasGratis     = t.caldas_gratis ?? 1;
  document.getElementById('btn-next1').disabled = false;

  // Atualiza preços dos sabores conforme o tamanho selecionado
  atualizarPrecosSabores();
}

function precoSabor(sabor) {
  if(state.tamanhoML <= 300) return parseFloat(sabor.preco_300) || parseFloat(sabor.preco);
  if(state.tamanhoML <= 400) return parseFloat(sabor.preco_400) || parseFloat(sabor.preco);
  return parseFloat(sabor.preco_500) || parseFloat(sabor.preco);
}

function atualizarPrecosSabores() {
  CFG_SABORES.forEach(s => {
    const badge = document.getElementById('sabor-preco-' + s.id);
    if(badge) badge.textContent = 'R$ ' + precoSabor(s).toFixed(2).replace('.', ',');
  });
}
// ── SABOR ────────────────────────────────────────────────────
function selectSabor(el, id, preco) {
  document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const s = CFG_SABORES.find(x => x.id == id);
  state.saborId    = id;
  state.saborNome  = s.nome;
  state.saborEmoji = s.emoji;
  state.saborPreco = precoSabor(s);
  document.getElementById('btn-next2').disabled = false;
}

// ── COMPLEMENTOS ─────────────────────────────────────────────
function toggleComp(el, id) {
  const idx = state.complementos.findIndex(c => c.id == id);
  if(idx >= 0) {
    state.complementos.splice(idx, 1);
    el.classList.remove('selected','gratis');
  } else {
    const a = CFG_ADICIONAIS.find(x => x.id == id);
    state.complementos.push({ id, nome: a.nome, emoji: a.emoji, preco: parseFloat(a.preco) });
    el.classList.add('selected');
  }
  recalcularGratis();
  atualizarBannerComps();
}

function recalcularGratis() {
  state.complementos.forEach((c, i) => {
    c.ehGratis = (i < state.gratis);
    const el = document.getElementById('comp-'+c.id);
    const priceEl = document.getElementById('comp-price-'+c.id);
    if(el) { el.classList.toggle('gratis', c.ehGratis); }
    if(priceEl) {
      priceEl.className = 'comp-price' + (c.ehGratis ? ' gratis-label' : '');
      priceEl.textContent = c.ehGratis ? '🎁 Grátis' : '+ R$ 1,90';
    }
  });
  document.querySelectorAll('.comp-card:not(.selected)').forEach(el => {
    const id = el.id.replace('comp-','');
    const priceEl = document.getElementById('comp-price-'+id);
    if(priceEl && !el.classList.contains('sem-estoque')) {
      priceEl.className = 'comp-price';
      priceEl.textContent = '+ R$ 1,90';
    }
  });
}

function atualizarBannerComps() {
  const sel   = state.complementos.length;
  const gratis= Math.min(sel, state.gratis);
  const pagos = Math.max(0, sel - state.gratis);
  const restam= Math.max(0, state.gratis - sel);
  let txt = '';
  if(sel === 0)    txt = `🎁 Você tem ${state.gratis} acomp. grátis disponíve${state.gratis > 1 ? 'is' : 'l'}`;
  else if(restam > 0) txt = `🎁 Ainda tem ${restam} grátis`;
  else if(pagos > 0)  txt = `💰 ${gratis} grátis + ${pagos} extra${pagos > 1 ? 's' : ''} (R$ ${(pagos*1.9).toFixed(2).replace('.',',')} )`;
  else                txt = `✅ Grátis esgotados — próximos: R$ 1,90 cada`;
  document.getElementById('comp-counter').textContent = txt;
}

// ── CALDA ────────────────────────────────────────────────────
// Preço da Nutella é dinâmico conforme ml do tamanho
function precoNutella() {
  if(state.tamanhoML <= 300) return 4.90;
  if(state.tamanhoML <= 400) return 5.90;
  return 6.90;
}

function atualizarCaldas() {
  // Atualiza o badge da Nutella conforme tamanho selecionado
  const nutella = CFG_CALDAS.find(c => c.nome.toLowerCase().includes('nutella'));
  if(!nutella) return;
  const priceEl = document.getElementById('calda-nutella-price');
  if(priceEl) priceEl.textContent = '+ R$ ' + precoNutella().toFixed(2).replace('.',',');
  // Atualiza subtítulo
  const sub = document.getElementById('calda-sub');
  if(sub) sub.textContent = `${state.caldasGratis} calda${state.caldasGratis > 1 ? 's' : ''} incluída${state.caldasGratis > 1 ? 's' : ''} grátis no ${state.tamanhoML}ml`;
}

function selectCalda(el, id, precoOuNutella, isNutella) {
  document.querySelectorAll('.calda-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const c = CFG_CALDAS.find(x => x.id == id);
  state.caldaId   = id;
  state.caldaNome = c.nome;
  state.caldaPreco = isNutella ? precoNutella() : parseFloat(precoOuNutella);
  document.getElementById('btn-next4').disabled = false;
}

// ── BEBIDA ───────────────────────────────────────────────────
// ── BEBIDA ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.beb-card').forEach(function(el) {
    el.addEventListener('click', function() {
      document.querySelectorAll('.beb-card').forEach(c => c.classList.remove('selected'));
      el.classList.add('selected');
      state.bebidaId    = el.dataset.id ? parseInt(el.dataset.id) : null;
      state.bebidaNome  = el.dataset.nome || 'Sem bebida';
      state.bebidaPreco = parseFloat(el.dataset.preco) || 0;
      document.getElementById('btn-next5').disabled = false;
    });
  });
});

function selectBebida(el, id, nome, preco) {
  document.querySelectorAll('.beb-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  state.bebidaId    = id || null;
  state.bebidaNome  = nome;
  state.bebidaPreco = parseFloat(preco) || 0;
  document.getElementById('btn-next5').disabled = false;
}
// ── MAIS UM ──────────────────────────────────────────────────

function maisUm(querMais) {
  if(querMais) {
    const valorCopoAtual = calcularTotal();
    state.itensConfirmados.push({
      tamanhoNome: state.tamanhoNome,
      tamanhoML:   state.tamanhoML,
      saborNome:   state.saborNome,
      caldaNome:   state.caldaNome,
      caldaPreco:  state.caldaPreco,
      complementos: state.complementos.slice(),
      saborPreco:   state.saborPreco,
      acrescimo:    state.tamanhoAcrescimo,
      bebidaNome:   state.bebidaNome,
      bebidaPreco:  state.bebidaPreco,
      valor:        valorCopoAtual
    });
    state.totalPedidoAcumulado += valorCopoAtual;
    // Reset copo atual
    state.tamanhoId = null; state.tamanhoNome = ''; state.tamanhoML = 0;
    state.tamanhoAcrescimo = 0; state.gratis = 0; state.caldasGratis = 1;
    state.saborId = null; state.saborNome = ''; state.saborEmoji = ''; state.saborPreco = 0;
    state.caldaId = null; state.caldaNome = ''; state.caldaPreco = 0;
    state.bebidaId = null; state.bebidaNome = ''; state.bebidaPreco = 0;
    state.complementos = [];
    document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected','gratis'));
    document.getElementById('btn-next1').disabled = true;
    document.getElementById('btn-next2').disabled = true;
    document.getElementById('btn-next4').disabled = true;
    document.getElementById('btn-next5').disabled = true;
    goTo('tamanho');
  } else {
    montarResumo();
    goTo('resumo');
  }
}

// ── CÁLCULOS ─────────────────────────────────────────────────
function calcularTotal() {
  const extraComps = state.complementos.filter(c => !c.ehGratis).reduce((s,c) => s+c.preco, 0);
  return (state.saborPreco||0) + extraComps + (state.caldaPreco||0) + (state.bebidaPreco||0);
}
function fmt(v) { return 'R$ ' + parseFloat(v||0).toFixed(2).replace('.',','); }

// ── RESUMO ───────────────────────────────────────────────────
function montarResumo() {
  const lista        = document.getElementById('r-comps-lista');
  const totalDisplay = document.getElementById('r-total');
  let htmlItens      = '';

  state.itensConfirmados.forEach((item, index) => {
    htmlItens += `
      <div class="resumo-linha" style="border-left:3px solid var(--rosa);margin-bottom:4px">
        <span class="resumo-label">Copo #${index+1}: ${item.tamanhoNome} ${item.tamanhoML}ml — ${item.saborNome}</span>
        <span class="resumo-valor">${fmt(item.valor)}</span>
      </div>`;
  });

  const numAtual   = state.itensConfirmados.length + 1;
  const valorAtual = calcularTotal();
  htmlItens += `
    <div class="resumo-linha" style="border-left:3px solid var(--verde);font-weight:bold">
      <span class="resumo-label">Copo #${numAtual}: ${state.tamanhoNome||''} ${state.tamanhoML||0}ml — ${state.saborNome||''}</span>
      <span class="resumo-valor">${fmt(valorAtual)}</span>
    </div>`;

  const totalFinal = state.totalPedidoAcumulado + valorAtual;
  lista.innerHTML = htmlItens;
  totalDisplay.textContent = fmt(totalFinal);

  document.getElementById('r-tamanho').textContent = state.tamanhoNome || '—';
  document.getElementById('r-sabor').textContent   = state.saborNome   || '—';
  document.getElementById('r-calda').textContent   = state.caldaNome   || '—';

  const extraComps = state.complementos.filter(c => !c.ehGratis).reduce((s,c) => s+c.preco, 0);
  document.getElementById('r-preco-sabor').textContent = fmt(state.saborPreco||0);
  document.getElementById('r-acrescimo').textContent = fmt(0);
  document.getElementById('r-preco-comps').textContent = fmt(extraComps);
  document.getElementById('r-preco-calda').textContent = fmt(state.caldaPreco||0);

  // Bebida no resumo
  const bebBox   = document.getElementById('r-bebida-box');
  const bebLinha = document.getElementById('r-beb-linha');
  if(state.bebidaPreco > 0) {
    bebBox.style.display   = 'block';
    bebLinha.style.display = 'flex';
    document.getElementById('r-bebida-nome').textContent  = state.bebidaNome;
    document.getElementById('r-bebida-preco').textContent = fmt(state.bebidaPreco);
    document.getElementById('r-preco-beb').textContent    = fmt(state.bebidaPreco);
  } else {
    bebBox.style.display   = 'none';
    bebLinha.style.display = 'none';
  }
}

// ── CONFIRMAR PEDIDO ─────────────────────────────────────────
async function confirmarPedido() {
  const obs        = document.getElementById('observacao').value.trim();
  const totalFinal = state.totalPedidoAcumulado + calcularTotal();
  const payload = {
    tamanho_id:   state.tamanhoId,
    sabor_id:     state.saborId,
    calda_id:     state.caldaId,
    complementos: state.complementos.map(c => ({ id: c.id, foi_gratis: c.ehGratis ? 1 : 0, preco: c.preco })),
    preco_sabor:  state.saborPreco,
    acrescimo:    state.tamanhoAcrescimo,
    preco_calda:  state.caldaPreco,
    bebida_id:    state.bebidaId,
    preco_bebida: state.bebidaPreco,
    total:        totalFinal,
    observacao:   obs,
  };
  try {
    const res  = await fetch('../api_pedido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if(data.ok) {
      const qtdCopos = state.itensConfirmados.length + 1;
      registrarVendaAdmin(data.numero, totalFinal, qtdCopos);
      gerarNota(data.numero);
      document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
      document.getElementById('screen-sucesso').classList.add('active');
      document.getElementById('suc-numero').textContent = '#' + String(data.numero).padStart(3,'0');
    } else {
      alert('Erro ao salvar pedido: ' + (data.erro || 'Tente novamente.'));
    }
  } catch(e) {
    alert('Erro de conexão. Verifique o servidor.');
  }
}

// ── NOVO PEDIDO ──────────────────────────────────────────────
function novoPedido() {
  state = {
    tamanhoId:null, tamanhoNome:'', tamanhoML:0, tamanhoAcrescimo:0, gratis:0, caldasGratis:1,
    saborId:null, saborNome:'', saborEmoji:'', saborPreco:0, complementos:[],
    caldaId:null, caldaNome:'', caldaPreco:0,
    bebidaId:null, bebidaNome:'', bebidaPreco:0,
    itensConfirmados:[], totalPedidoAcumulado:0
  };
  document.querySelectorAll('.size-card,.type-card,.comp-card,.calda-card,.beb-card').forEach(c => c.classList.remove('selected','gratis'));
  ['btn-next1','btn-next2','btn-next4','btn-next5'].forEach(id => document.getElementById(id).disabled = true);
  document.getElementById('observacao').value = '';
  goTo('tamanho');
}

// ── NOTINHA ──────────────────────────────────────────────────
function gerarNota(numero) {
  const agora = new Date();
  const data  = agora.toLocaleDateString('pt-BR');
  const hora  = agora.toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
  const obs   = document.getElementById('observacao').value.trim();

  const todosCpos = [
    ...state.itensConfirmados,
    {
      tamanhoNome: state.tamanhoNome, tamanhoML: state.tamanhoML,
      saborNome: state.saborNome, caldaNome: state.caldaNome, caldaPreco: state.caldaPreco,
      complementos: state.complementos, saborPreco: state.saborPreco,
      acrescimo: state.tamanhoAcrescimo, bebidaNome: state.bebidaNome, bebidaPreco: state.bebidaPreco,
      valor: calcularTotal()
    }
  ];
  const totalFinal = state.totalPedidoAcumulado + calcularTotal();

  let htmlCopos = '';
  todosCpos.forEach((copo, idx) => {
    let linhasComps = '';
    if(!copo.complementos || copo.complementos.length === 0) {
      linhasComps = '<div class="nota-linha"><span>Sem acompanhamentos</span></div>';
    } else {
      linhasComps = copo.complementos.map(c =>
        `<div class="nota-linha"><span>${c.nome}</span><span class="${c.ehGratis?'nota-gratis':''}">${c.ehGratis?'GRATIS':fmt(c.preco)}</span></div>`
      ).join('');
    }
    const bebLinha = (copo.bebidaPreco > 0) ? `<div class="nota-linha"><span>${copo.bebidaNome}</span><span>${fmt(copo.bebidaPreco)}</span></div>` : '';
    htmlCopos += `
      <div class="nota-bold" style="margin-top:6px">COPO #${idx+1}</div>
      <div class="nota-linha"><span>Tamanho</span><span>${copo.tamanhoNome} (${copo.tamanhoML}ml)</span></div>
      <div class="nota-linha"><span>Sabor</span><span>${copo.saborNome}</span></div>
      <div class="nota-linha"><span>Calda</span><span>${copo.caldaNome}${copo.caldaPreco > 0 ? ' — ' + fmt(copo.caldaPreco) : ' — Incluída'}</span></div>
      <div class="nota-bold" style="margin-top:4px;font-size:11px">Acompanhamentos:</div>
      ${linhasComps}
      ${bebLinha}
      <div class="nota-linha" style="font-size:11px"><span>Subtotal</span><span>${fmt(copo.valor)}</span></div>
      ${idx < todosCpos.length - 1 ? '<hr class="nota-hr">' : ''}
    `;
  });

  document.getElementById('nota-impressao').innerHTML = `
    <div class="nota-grande">PontoAcai</div>
    <div class="nota-center" style="font-size:11px">${data} &nbsp;|&nbsp; ${hora}</div>
    <hr class="nota-hr">
    <div class="nota-center nota-bold">PEDIDO</div>
    <div class="nota-numero">#${String(numero).padStart(3,'0')}</div>
    <div class="nota-center" style="font-size:11px">${todosCpos.length} copo${todosCpos.length>1?'s':''}</div>
    <hr class="nota-hr">
    ${htmlCopos}
    <hr class="nota-hr">
    <div class="nota-total"><span>TOTAL</span><span>${fmt(totalFinal)}</span></div>
    ${obs ? `<hr class="nota-hr"><div class="nota-bold">OBS:</div><div class="nota-obs">${obs}</div>` : ''}
    <hr class="nota-hr">
    <div class="nota-rodape">Obrigado pela preferencia!</div>
    <div class="nota-rodape">Bom apetite! 💜</div>
  `;
}

function imprimirNota() {
  const conteudo = document.getElementById('nota-impressao').innerHTML;
  if(!conteudo.trim()) { alert('Nenhuma notinha gerada. Confirme um pedido primeiro.'); return; }
  const janela = window.open('', '_blank', 'width=350,height=600');
  janela.document.write(`<!DOCTYPE html>
<html lang="pt-BR"><head><meta charset="UTF-8"><title>Notinha PontoAcai</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Courier New',Courier,monospace;font-size:20px;color:#000;background:#fff;width:80mm;padding:5mm 4mm;line-height:1.6;}
  .nota-center{text-align:center;}.nota-bold{font-weight:bold;}
  .nota-grande{font-size:23px;font-weight:bold;text-align:center;letter-spacing:1px;}
  .nota-numero{font-size:30px;font-weight:bold;text-align:center;letter-spacing:3px;margin:4px 0;}
  .nota-hr{border:none;border-top:1px dashed #000;margin:6px 0;}
  .nota-linha{display:flex;justify-content:space-between;}
  .nota-gratis{font-style:italic;}
  .nota-total{display:flex;justify-content:space-between;font-size:15px;font-weight:bold;border-top:2px solid #000;padding-top:4px;margin-top:4px;}
  .nota-obs{font-style:italic;font-size:20px;}
  .nota-rodape{text-align:center;font-size:20px;margin-top:8px;}
  @page{size:80mm auto;margin:0;}
</style></head>
<body>${conteudo}<script>window.onload=function(){setTimeout(function(){window.print();window.onafterprint=function(){window.close();};},300);};<\/script></body></html>`);
  janela.document.close();
}

// ════════════════════════════════════════════════════════════
//  PAINEL ADMIN
// ════════════════════════════════════════════════════════════
let adminData = {
  tamanhos:   JSON.parse(JSON.stringify(CFG_TAMANHOS)),
  sabores:    JSON.parse(JSON.stringify(CFG_SABORES)),
  adicionais: JSON.parse(JSON.stringify(CFG_ADICIONAIS)),
  caldas:     JSON.parse(JSON.stringify(CFG_CALDAS)),
};
let vendas   = [];
let totalDia = 0;

async function toggleAdmin() {
  const panel = document.getElementById('admin-panel');
  panel.classList.toggle('open');
  if(panel.classList.contains('open')) await renderAdmin();
}

function switchTab(id, btn) {
  document.querySelectorAll('.admin-tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  btn.classList.add('active');
}

async function renderAdmin() {
  try {
    const res  = await fetch('../api_admin.php');
    const data = await res.json();
    if(data.ok) {
      adminData.adicionais = data.adicionais;
      adminData.sabores    = data.sabores;
      adminData.caldas     = data.caldas;
      adminData.tamanhos   = data.tamanhos;
      data.adicionais.forEach(a => { const o = CFG_ADICIONAIS.find(x => x.id==a.id); if(o) Object.assign(o,a); });
      data.sabores.forEach(s    => { const o = CFG_SABORES.find(x => x.id==s.id);    if(o) Object.assign(o,s); });
      data.caldas.forEach(c     => { const o = CFG_CALDAS.find(x => x.id==c.id);     if(o) Object.assign(o,c); });
      data.tamanhos.forEach(t   => { const o = CFG_TAMANHOS.find(x => x.id==t.id);   if(o) Object.assign(o,t); });
    }
  } catch(e) {}
  renderEstoque();
  renderPrecosSabores();
  renderPrecosAdicionais();
  renderPrecosCaldas();
  renderTamanhosAdmin();
  renderGratisAdmin();
  renderVendas();
}

function renderEstoque() {
  document.getElementById('adm-estoque').innerHTML = adminData.adicionais.map(a => {
    const badge = a.estoque_atual <= 0
      ? `<span class="estoque-badge estoque-zero">❌ Esgotado</span>`
      : a.estoque_atual <= a.estoque_minimo
        ? `<span class="estoque-badge estoque-baixo">⚠ Baixo</span>`
        : `<span class="estoque-badge estoque-ok">✓ OK</span>`;
    return `<div class="admin-row">
      <label>${a.emoji} ${a.nome}</label>
      <input type="number" min="0" value="${a.estoque_atual}" onchange="setEstoque(${a.id}, +this.value)">
      <span class="unit">unid.</span>${badge}</div>`;
  }).join('');
}

async function salvarAdmin(acao, id, valor) {
  try {
    const res  = await fetch('../api_admin.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({acao,id,valor}) });
    const data = await res.json();
    if(!data.ok) alert('Erro ao salvar: '+(data.erro||'Tente novamente.'));
  } catch(e) { alert('Erro de conexão ao salvar.'); }
}

function setEstoque(id, val) {
  const a = adminData.adicionais.find(x => x.id==id);
  if(a) { a.estoque_atual = val; renderEstoque(); }
  const orig = CFG_ADICIONAIS.find(x => x.id==id);
  if(orig) orig.estoque_atual = val;
  salvarAdmin('set_estoque', id, val);
}

async function reporTudo() {
  adminData.adicionais.forEach(a => a.estoque_atual = 50);
  CFG_ADICIONAIS.forEach(a => a.estoque_atual = 50);
  renderEstoque();
  await salvarAdmin('repor_tudo', null, null);
}

function renderPrecosSabores() {
  document.getElementById('adm-preco-sabores').innerHTML = adminData.sabores.map(s => `
    <div class="admin-row">
      <label>${s.emoji} ${s.nome}</label>
      <input type="number" min="0" step="0.1" value="${parseFloat(s.preco).toFixed(2)}" onchange="setPrecoSabor(${s.id}, +this.value)">
      <span class="unit">R$</span>
    </div>`).join('');
}

function setPrecoSabor(id, val) {
  const a = adminData.sabores.find(x => x.id==id); if(a) a.preco = val;
  const orig = CFG_SABORES.find(x => x.id==id); if(orig) orig.preco = val;
  salvarAdmin('set_preco_sabor', id, val);
}

function renderPrecosAdicionais() {
  document.getElementById('adm-preco-adicionais').innerHTML = adminData.adicionais.map(a => `
    <div class="admin-row">
      <label>${a.emoji} ${a.nome}</label>
      <input type="number" min="0" step="0.1" value="${parseFloat(a.preco).toFixed(2)}" onchange="setPrecoAdicional(${a.id}, +this.value)">
      <span class="unit">R$ (extra)</span>
    </div>`).join('');
}

function setPrecoAdicional(id, val) {
  const a = adminData.adicionais.find(x => x.id==id); if(a) a.preco = val;
  const orig = CFG_ADICIONAIS.find(x => x.id==id); if(orig) orig.preco = val;
  salvarAdmin('set_preco_adicional', id, val);
}

function renderPrecosCaldas() {
  document.getElementById('adm-preco-caldas').innerHTML = adminData.caldas.map(c => {
    const isNutella = c.nome.toLowerCase().includes('nutella');
    if(isNutella) {
      return `<div class="admin-row">
        <label>${c.emoji} ${c.nome}</label>
        <input type="number" min="0" step="0.1" value="${parseFloat(c.preco_300||0).toFixed(2)}" onchange="setPrecoNutella(${c.id}, 300, +this.value)">
        <span class="unit">300ml</span>
        <input type="number" min="0" step="0.1" value="${parseFloat(c.preco_400||0).toFixed(2)}" onchange="setPrecoNutella(${c.id}, 400, +this.value)">
        <span class="unit">400ml</span>
        <input type="number" min="0" step="0.1" value="${parseFloat(c.preco_500||0).toFixed(2)}" onchange="setPrecoNutella(${c.id}, 500, +this.value)">
        <span class="unit">500ml</span>
      </div>`;
    }
    return `<div class="admin-row">
      <label>${c.emoji} ${c.nome}</label>
      <input type="number" min="0" step="0.1" value="${parseFloat(c.preco).toFixed(2)}" onchange="setPrecoCaldas(${c.id}, +this.value)">
      <span class="unit">R$</span>
    </div>`;
  }).join('');
}

function setPrecoCaldas(id, val) {
  const a = adminData.caldas.find(x => x.id==id); if(a) a.preco = val;
  const orig = CFG_CALDAS.find(x => x.id==id); if(orig) orig.preco = val;
  salvarAdmin('set_preco_calda', id, val);
}

function setPrecoNutella(id, ml, val) {
  const a = adminData.caldas.find(x => x.id==id);
  if(a) a['preco_'+ml] = val;
  const orig = CFG_CALDAS.find(x => x.id==id);
  if(orig) orig['preco_'+ml] = val;
  salvarAdmin('set_preco_nutella', id, {ml, val});
}

function renderTamanhosAdmin() {
  document.getElementById('adm-tamanhos').innerHTML = adminData.tamanhos.map(t => `
    <div class="admin-row">
      <label>🥤 ${t.ml}ml — ${t.nome}</label>
      <input type="number" min="0" step="0.5" value="${parseFloat(t.acrescimo).toFixed(2)}" onchange="setAcrescimo(${t.id}, +this.value)">
      <span class="unit">R$ acréscimo</span>
    </div>`).join('');
}

function setAcrescimo(id, val) {
  const a = adminData.tamanhos.find(x => x.id==id); if(a) a.acrescimo = val;
  const orig = CFG_TAMANHOS.find(x => x.id==id); if(orig) orig.acrescimo = val;
  salvarAdmin('set_acrescimo', id, val);
}

function renderGratisAdmin() {
  document.getElementById('adm-gratis').innerHTML = adminData.tamanhos.map(t => `
    <div class="admin-row">
      <label>🥤 ${t.ml}ml — ${t.nome}</label>
      <input type="number" min="0" max="20" value="${t.complementos_gratis}" onchange="setGratis(${t.id}, +this.value)">
      <span class="unit">acomp. grátis</span>
      <input type="number" min="0" max="5" value="${t.caldas_gratis||1}" onchange="setCaldaGratis(${t.id}, +this.value)">
      <span class="unit">caldas grátis</span>
    </div>`).join('');
}

function setGratis(id, val) {
  const a = adminData.tamanhos.find(x => x.id==id); if(a) a.complementos_gratis = val;
  const orig = CFG_TAMANHOS.find(x => x.id==id); if(orig) orig.complementos_gratis = val;
  salvarAdmin('set_gratis', id, val);
}

function setCaldaGratis(id, val) {
  const a = adminData.tamanhos.find(x => x.id==id); if(a) a.caldas_gratis = val;
  const orig = CFG_TAMANHOS.find(x => x.id==id); if(orig) orig.caldas_gratis = val;
  salvarAdmin('set_caldas_gratis', id, val);
}

function renderVendas() {
  const el = document.getElementById('adm-vendas');
  if(vendas.length === 0) {
    el.innerHTML = '<p style="color:rgba(255,255,255,0.35);font-size:.88rem;text-align:center;padding:16px 0">Nenhuma venda registrada ainda.</p>';
  } else {
    el.innerHTML = vendas.slice(0,20).map(v => `
      <div class="vendas-item">
        <span>#${String(v.num).padStart(3,'0')} · ${v.hora} · ${v.itens} copo(s)</span>
        <span style="color:var(--amarelo);font-weight:800">${fmt(v.total)}</span>
      </div>`).join('');
  }
  document.getElementById('adm-total-dia').textContent = fmt(totalDia);
}

function registrarVendaAdmin(numero, total, itensQtd) {
  const hora = new Date().toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
  totalDia += total;
  vendas.unshift({ num: numero, hora, total, itens: itensQtd });
}
</script>

<!-- PAINEL ADMIN HTML -->
<div id="admin-panel">
  <div class="admin-box">
    <div class="admin-title">
      ⚙️ Painel Administrativo — PontoAçaí
      <button class="admin-close" onclick="toggleAdmin()">✕</button>
    </div>
    <div class="admin-tabs">
      <button class="admin-tab active" onclick="switchTab('tab-estoque',this)">📦 Estoque</button>
      <button class="admin-tab"        onclick="switchTab('tab-precos',this)">💰 Preços</button>
      <button class="admin-tab"        onclick="switchTab('tab-tamanhos',this)">🥤 Tamanhos</button>
      <button class="admin-tab"        onclick="switchTab('tab-gratis',this)">🎁 Grátis</button>
      <button class="admin-tab"        onclick="switchTab('tab-vendas',this)">📊 Vendas</button>
    </div>

    <div class="admin-tab-content active" id="tab-estoque">
      <div class="admin-section">
        <h3>📦 Estoque dos Acompanhamentos</h3>
        <div id="adm-estoque"></div>
        <div style="text-align:center;margin-top:12px">
          <button class="btn btn-secondary" style="font-size:.9rem;padding:10px 24px;min-width:0" onclick="reporTudo()">🔄 Repor tudo (50 unid.)</button>
        </div>
      </div>
    </div>

    <div class="admin-tab-content" id="tab-precos">
      <div class="admin-section">
        <h3>🍇 Preço base por Sabor</h3>
        <p class="hint">Preço final = sabor + acréscimo do tamanho.</p>
        <div id="adm-preco-sabores"></div>
      </div>
      <div class="admin-section">
        <h3>✨ Preço dos Acompanhamentos Extras</h3>
        <p class="hint">Cobrado quando ultrapassa a cota grátis. Padrão: R$ 1,90.</p>
        <div id="adm-preco-adicionais"></div>
      </div>
      <div class="admin-section">
        <h3>🍯 Preço das Caldas</h3>
        <p class="hint">Nutella tem preço diferente por tamanho (300ml / 400ml / 500ml).</p>
        <div id="adm-preco-caldas"></div>
      </div>
    </div>

    <div class="admin-tab-content" id="tab-tamanhos">
      <div class="admin-section">
        <h3>🥤 Acréscimo por Tamanho</h3>
        <p class="hint">Valor adicionado ao preço base do sabor.</p>
        <div id="adm-tamanhos"></div>
      </div>
    </div>

    <div class="admin-tab-content" id="tab-gratis">
      <div class="admin-section">
        <h3>🎁 Grátis por Tamanho</h3>
        <p class="hint">Acompanhamentos e caldas incluídos sem custo em cada tamanho.</p>
        <div id="adm-gratis"></div>
      </div>
    </div>

    <div class="admin-tab-content" id="tab-vendas">
      <div class="admin-section">
        <h3>📊 Vendas de Hoje</h3>
        <div id="adm-vendas"></div>
        <div class="vendas-total-row">
          <span class="label">💜 Total do Dia</span>
          <span class="vendas-total-val" id="adm-total-dia">R$ 0,00</span>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
