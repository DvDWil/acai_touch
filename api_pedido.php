<?php
// ============================================================
//  api/pedido.php  –  Salva um pedido no banco
//  Coloque em: C:\xampp\htdocs\acai-sistema\public\api\pedido.php
// ============================================================
header('Content-Type: application/json; charset=utf-8');
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    echo json_encode(['ok' => false, 'erro' => 'Payload inválido']);
    exit;
}

try {
    $pdo = conectar();
    $pdo->beginTransaction();

    // 1. Número do pedido (sequencial do dia)
    $stmt = $pdo->query("SELECT IFNULL(MAX(numero), 0) + 1 AS proximo FROM pedidos WHERE DATE(criado_em) = CURDATE()");
    $numero = $stmt->fetchColumn();

    // 2. Inserir pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (numero, total, observacao) VALUES (?, ?, ?)");
    $stmt->execute([$numero, $body['total'], $body['observacao'] ?? '']);
    $pedidoId = $pdo->lastInsertId();

    // 3. Inserir item do pedido
    $stmt = $pdo->prepare("
        INSERT INTO itens_pedido
            (pedido_id, sabor_id, tamanho_id, preco_sabor, acrescimo, calda_id, preco_calda, subtotal)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $pedidoId,
        $body['sabor_id'],
        $body['tamanho_id'],
        $body['preco_sabor'],
        $body['acrescimo'],
        $body['calda_id'] ?: null,
        $body['preco_calda'],
        $body['total'],
    ]);
    $itemId = $pdo->lastInsertId();

    // 4. Inserir adicionais + baixar estoque
    if (!empty($body['complementos'])) {
        $stmtAdc = $pdo->prepare("
            INSERT INTO item_adicional
                (item_pedido_id, adicional_id, ordem_escolha, foi_gratis, preco_unitario)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtEst = $pdo->prepare("
            UPDATE adicionais SET estoque_atual = estoque_atual - 1 WHERE id = ?
        ");
        $stmtMov = $pdo->prepare("
            INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo)
            VALUES (?, 'saida', 1, ?)
        ");

        foreach ($body['complementos'] as $ordem => $comp) {
            $stmtAdc->execute([$itemId, $comp['id'], $ordem + 1, $comp['foi_gratis'], $comp['preco']]);
            $stmtEst->execute([$comp['id']]);
            $stmtMov->execute([$comp['id'], "Pedido #{$numero}:{$pedidoId}"]);
        }
    }

    $pdo->commit();
    echo json_encode(['ok' => true, 'pedido_id' => $pedidoId, 'numero' => $numero]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['ok' => false, 'erro' => $e->getMessage()]);
}
