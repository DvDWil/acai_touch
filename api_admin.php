<?php
// ============================================================
//  api_admin.php  –  Salva alterações do painel admin no banco
//  Coloque em: C:\xampp\htdocs\acai-sistema\public\api_admin.php
// ============================================================
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $pdo = conectar();
        echo json_encode([
            'ok'        => true,
            'adicionais' => $pdo->query("SELECT * FROM adicionais WHERE ativo = 1 ORDER BY nome ASC")->fetchAll(),
            'sabores'    => $pdo->query("SELECT * FROM sabores WHERE ativo = 1 ORDER BY preco ASC, nome ASC")->fetchAll(),
            'caldas'     => $pdo->query("SELECT * FROM caldas WHERE ativo = 1 ORDER BY preco ASC")->fetchAll(),
            'tamanhos'   => $pdo->query("SELECT * FROM tamanhos WHERE ativo = 1 ORDER BY ml ASC")->fetchAll(),
        ]);
    } catch (Exception $e) {
        echo json_encode(['ok' => false, 'erro' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body || empty($body['acao'])) {
    echo json_encode(['ok' => false, 'erro' => 'Payload inválido']);
    exit;
}

try {
    $pdo = conectar();

    switch ($body['acao']) {

        // ── Estoque individual ────────────────────────────────
        case 'set_estoque':
            $stmt = $pdo->prepare("UPDATE adicionais SET estoque_atual = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);

            // registra movimento
            $tipo = $body['valor'] > 0 ? 'entrada' : 'saida';
            $mov  = $pdo->prepare("INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo) VALUES (?, ?, ?, 'Ajuste manual admin')");
            $mov->execute([$body['id'], $tipo, abs($body['valor'])]);
            break;

        // ── Repor tudo (50 unid.) ─────────────────────────────
        case 'repor_tudo':
            $pdo->exec("UPDATE adicionais SET estoque_atual = 50 WHERE ativo = 1");

            // registra movimento para cada adicional
            $ids = $pdo->query("SELECT id FROM adicionais WHERE ativo = 1")->fetchAll(PDO::FETCH_COLUMN);
            $mov = $pdo->prepare("INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo) VALUES (?, 'entrada', 50, 'Reposição total admin')");
            foreach ($ids as $id) $mov->execute([$id]);
            break;

        // ── Preço de sabor ────────────────────────────────────
        case 'set_preco_sabor':
            $stmt = $pdo->prepare("UPDATE sabores SET preco = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);
            break;

        // ── Preço de adicional ────────────────────────────────
        case 'set_preco_adicional':
            $stmt = $pdo->prepare("UPDATE adicionais SET preco = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);
            break;

        // ── Preço de calda ────────────────────────────────────
        case 'set_preco_calda':
            $stmt = $pdo->prepare("UPDATE caldas SET preco = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);
            break;

        // ── Acréscimo de tamanho ──────────────────────────────
        case 'set_acrescimo':
            $stmt = $pdo->prepare("UPDATE tamanhos SET acrescimo = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);
            break;

        // ── Complementos grátis por tamanho ──────────────────
        case 'set_gratis':
            $stmt = $pdo->prepare("UPDATE tamanhos SET complementos_gratis = ? WHERE id = ?");
            $stmt->execute([$body['valor'], $body['id']]);
            break;

        default:
            echo json_encode(['ok' => false, 'erro' => 'Ação desconhecida']);
            exit;
    }

    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'erro' => $e->getMessage()]);
}