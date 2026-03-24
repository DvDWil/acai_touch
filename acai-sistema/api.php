<?php
// ============================================================
//  api.php  —  raiz do projeto
//  C:\xampp\htdocs\acai-sistema\api.php
//
//  Endpoints:
//  GET  ?acao=tamanhos
//  GET  ?acao=adicionais
//  GET  ?acao=estoque_baixo
//  GET  ?acao=relatorio_hoje
//  GET  ?acao=pedidos_do_dia
//  POST ?acao=criar_pedido
//  POST ?acao=atualizar_status   { pedido_id, status }
//  POST ?acao=imprimir           { pedido_id }
//  POST ?acao=cancelar           { pedido_id }
//  POST ?acao=repor_estoque      { adicional_id, quantidade }
// ============================================================

require_once __DIR__ . '/config/conexao.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$acao = $_GET['acao'] ?? '';

try {
    $pdo = conectar();

    switch ($acao) {
        case 'tamanhos':        listarTamanhos($pdo);      break;
        case 'adicionais':      listarAdicionais($pdo);    break;
        case 'criar_pedido':    criarPedido($pdo);         break;
        case 'atualizar_status':atualizarStatus($pdo);     break;
        case 'imprimir':        marcarImpresso($pdo);      break;
        case 'cancelar':        cancelarPedido($pdo);      break;
        case 'estoque_baixo':   estoqueBaixo($pdo);        break;
        case 'repor_estoque':   reporEstoque($pdo);        break;
        case 'relatorio_hoje':  relatorioHoje($pdo);       break;
        case 'pedidos_do_dia':  pedidosDoDia($pdo);        break;
        case 'novo_adicional':  novoAdicional($pdo);       break;
        case 'editar_adicional':editarAdicional($pdo);     break;
        case 'historico_estoque':historicoEstoque($pdo);   break;
        default:
            responder(false, 'Ação inválida: ' . htmlspecialchars($acao));
    }

} catch (PDOException $e) {
    responder(false, 'Erro de banco: ' . $e->getMessage());
} catch (Exception $e) {
    responder(false, $e->getMessage());
}


// ============================================================
//  GET: tamanhos ativos
// ============================================================
function listarTamanhos(PDO $pdo): void {
    $rows = $pdo->query("
        SELECT id, nome, ml, preco
        FROM   tamanhos
        WHERE  ativo = 1
        ORDER  BY ml ASC
    ")->fetchAll();

    foreach ($rows as &$r) {
        $r['preco'] = (float)$r['preco'];
        $r['ml']    = (int)$r['ml'];
    }

    responder(true, 'OK', $rows);
}


// ============================================================
//  GET: adicionais ativos com flags de estoque
// ============================================================
function listarAdicionais(PDO $pdo): void {
    $rows = $pdo->query("
        SELECT id, nome, icone, preco, estoque_atual, estoque_minimo
        FROM   adicionais
        WHERE  ativo = 1
        ORDER  BY nome ASC
    ")->fetchAll();

    foreach ($rows as &$r) {
        $r['preco']         = (float)$r['preco'];
        $r['estoque_atual'] = (int)$r['estoque_atual'];
        $r['sem_estoque']   = $r['estoque_atual'] === 0;
        $r['estoque_baixo'] = $r['estoque_atual'] <= (int)$r['estoque_minimo'];
    }

    responder(true, 'OK', $rows);
}


// ============================================================
//  POST: criar pedido completo
//
//  Body JSON:
//  {
//    "observacao": "...",
//    "itens": [
//      {
//        "tamanho_id": 1,
//        "observacao": "...",
//        "adicionais": [
//          { "id": 1, "quantidade": 1 },
//          { "id": 3, "quantidade": 2 }
//        ]
//      }
//    ]
//  }
//
//  Compatibilidade: também aceita adicionais como array de IDs simples
//  ex: "adicionais": [1, 3, 5]
// ============================================================
function criarPedido(PDO $pdo): void {
    $dados = lerJSON();

    if (empty($dados['itens']) || !is_array($dados['itens'])) {
        throw new Exception('O pedido precisa ter ao menos 1 item.');
    }

    // Próximo número amigável
    $numero = (int)$pdo->query(
        "SELECT IFNULL(MAX(numero), 0) + 1 FROM pedidos"
    )->fetchColumn();

    $pdo->beginTransaction();

    try {
        // 1. Insere o pedido
        $pdo->prepare("
            INSERT INTO pedidos (numero, total, observacao, status)
            VALUES (?, 0.00, ?, 'aberto')
        ")->execute([$numero, $dados['observacao'] ?? null]);

        $pedidoId    = (int)$pdo->lastInsertId();
        $totalPedido = 0.0;

        foreach ($dados['itens'] as $item) {

            // 2. Valida e busca tamanho
            $stmtT = $pdo->prepare(
                "SELECT preco FROM tamanhos WHERE id = ? AND ativo = 1"
            );
            $stmtT->execute([$item['tamanho_id']]);
            $tam = $stmtT->fetch();

            if (!$tam) {
                throw new Exception("Tamanho ID {$item['tamanho_id']} não encontrado.");
            }

            $precoBase = (float)$tam['preco'];
            $subtotalItem = $precoBase;

            // 3. Insere o item (copo)
            $pdo->prepare("
                INSERT INTO itens_pedido (pedido_id, tamanho_id, preco_base, subtotal, observacao)
                VALUES (?, ?, ?, 0.00, ?)
            ")->execute([$pedidoId, $item['tamanho_id'], $precoBase, $item['observacao'] ?? null]);

            $itemId  = (int)$pdo->lastInsertId();

            // 4. Normaliza adicionais — aceita [1,2,3] ou [{id:1,quantidade:1}]
            $adicionaisRaw = $item['adicionais'] ?? [];
            $adicionaisNorm = [];

            foreach ($adicionaisRaw as $a) {
                if (is_array($a)) {
                    $adicionaisNorm[] = [
                        'id'         => (int)$a['id'],
                        'quantidade' => (int)($a['quantidade'] ?? 1),
                    ];
                } else {
                    $adicionaisNorm[] = [
                        'id'         => (int)$a,
                        'quantidade' => 1,
                    ];
                }
            }

            if (!empty($adicionaisNorm)) {
                $ids = array_column($adicionaisNorm, 'id');
                $ph  = implode(',', array_fill(0, count($ids), '?'));

                $stmtA = $pdo->prepare("
                    SELECT id, nome, preco, estoque_atual
                    FROM   adicionais
                    WHERE  id IN ($ph) AND ativo = 1
                ");
                $stmtA->execute($ids);
                $adicionaisBD = $stmtA->fetchAll(PDO::FETCH_UNIQUE);

                foreach ($adicionaisNorm as $addReq) {
                    $add = $adicionaisBD[$addReq['id']] ?? null;

                    if (!$add) {
                        throw new Exception("Adicional ID {$addReq['id']} não encontrado.");
                    }

                    $qtd = $addReq['quantidade'];

                    // Verifica estoque suficiente
                    if ((int)$add['estoque_atual'] < $qtd) {
                        throw new Exception("\"{$add['nome']}\" tem apenas {$add['estoque_atual']} em estoque.");
                    }

                    $precoUn  = (float)$add['preco'];
                    $subAdd   = $precoUn * $qtd;
                    $subtotalItem += $subAdd;

                    // Insere na tabela item_adicional (com quantidade e subtotal)
                    $pdo->prepare("
                        INSERT INTO item_adicional
                               (item_pedido_id, adicional_id, quantidade, preco_unitario, subtotal)
                        VALUES (?, ?, ?, ?, ?)
                    ")->execute([$itemId, $addReq['id'], $qtd, $precoUn, $subAdd]);

                    // Baixa estoque
                    $pdo->prepare(
                        "UPDATE adicionais SET estoque_atual = estoque_atual - ? WHERE id = ?"
                    )->execute([$qtd, $addReq['id']]);

                    // Log
                    $numFmt = str_pad($numero, 3, '0', STR_PAD_LEFT);
                    $pdo->prepare("
                        INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo)
                        VALUES (?, 'saida', ?, ?)
                    ")->execute([$addReq['id'], $qtd, "Venda pedido #$numFmt"]);
                }
            }

            // Atualiza subtotal do item
            $pdo->prepare(
                "UPDATE itens_pedido SET subtotal = ? WHERE id = ?"
            )->execute([$subtotalItem, $itemId]);

            $totalPedido += $subtotalItem;
        }

        // Atualiza total do pedido
        $pdo->prepare(
            "UPDATE pedidos SET total = ? WHERE id = ?"
        )->execute([$totalPedido, $pedidoId]);

        $pdo->commit();

        responder(true, 'Pedido criado!', [
            'pedido_id' => $pedidoId,
            'numero'    => $numero,
            'total'     => $totalPedido,
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}


// ============================================================
//  POST: atualizar status do pedido
//
//  Body: { "pedido_id": 42, "status": "em_preparo" }
//
//  Status válidos:
//  aberto → em_preparo → pronto → entregue
//  qualquer → impresso
//  qualquer → cancelado
// ============================================================
function atualizarStatus(PDO $pdo): void {
    $dados  = lerJSON();
    $id     = (int)($dados['pedido_id'] ?? 0);
    $status = $dados['status'] ?? '';

    $statusValidos = ['aberto','em_preparo','pronto','impresso','entregue','cancelado'];

    if (!$id) throw new Exception('pedido_id inválido.');
    if (!in_array($status, $statusValidos)) {
        throw new Exception("Status inválido: $status. Válidos: " . implode(', ', $statusValidos));
    }

    // Se for cancelado, devolve estoque
    if ($status === 'cancelado') {
        cancelarPedidoPorId($pdo, $id);
        return;
    }

    $pdo->prepare(
        "UPDATE pedidos SET status = ? WHERE id = ?"
    )->execute([$status, $id]);

    responder(true, "Status atualizado para '$status'.");
}


// ============================================================
//  POST: marcar como impresso
//  Body: { "pedido_id": 42 }
// ============================================================
function marcarImpresso(PDO $pdo): void {
    $dados = lerJSON();
    $id    = (int)($dados['pedido_id'] ?? 0);

    if (!$id) throw new Exception('pedido_id inválido.');

    $pdo->prepare("
        UPDATE pedidos
        SET    status = 'impresso', impresso_em = NOW()
        WHERE  id = ?
    ")->execute([$id]);

    responder(true, 'Pedido marcado como impresso.');
}


// ============================================================
//  POST: cancelar pedido e devolver estoque
//  Body: { "pedido_id": 42 }
// ============================================================
function cancelarPedido(PDO $pdo): void {
    $dados = lerJSON();
    $id    = (int)($dados['pedido_id'] ?? 0);
    if (!$id) throw new Exception('pedido_id inválido.');
    cancelarPedidoPorId($pdo, $id);
}

// Função interna reutilizável (usada também por atualizarStatus)
function cancelarPedidoPorId(PDO $pdo, int $id): void {
    $pdo->beginTransaction();

    try {
        // Busca adicionais usados com quantidade
        $stmt = $pdo->prepare("
            SELECT ia.adicional_id, SUM(ia.quantidade) AS qtd
            FROM   item_adicional ia
            JOIN   itens_pedido ip ON ip.id = ia.item_pedido_id
            WHERE  ip.pedido_id = ?
            GROUP  BY ia.adicional_id
        ");
        $stmt->execute([$id]);

        foreach ($stmt->fetchAll() as $u) {
            // Devolve estoque
            $pdo->prepare(
                "UPDATE adicionais SET estoque_atual = estoque_atual + ? WHERE id = ?"
            )->execute([$u['qtd'], $u['adicional_id']]);

            // Log
            $pdo->prepare("
                INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo)
                VALUES (?, 'entrada', ?, 'Cancelamento de pedido')
            ")->execute([$u['adicional_id'], $u['qtd']]);
        }

        $pdo->prepare(
            "UPDATE pedidos SET status = 'cancelado' WHERE id = ?"
        )->execute([$id]);

        $pdo->commit();
        responder(true, 'Pedido cancelado e estoque devolvido.');

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}


// ============================================================
//  GET: adicionais com estoque baixo ou zerado
// ============================================================
function estoqueBaixo(PDO $pdo): void {
    $rows = $pdo->query(
        "SELECT * FROM vw_adicionais_estoque_baixo"
    )->fetchAll();

    responder(true, 'OK', $rows);
}


// ============================================================
//  POST: repor estoque
//  Body: { "adicional_id": 3, "quantidade": 20 }
// ============================================================
function reporEstoque(PDO $pdo): void {
    $dados = lerJSON();
    $addId = (int)($dados['adicional_id'] ?? 0);
    $qtd   = (int)($dados['quantidade']   ?? 0);

    if (!$addId || $qtd <= 0) {
        throw new Exception('adicional_id e quantidade são obrigatórios.');
    }

    $pdo->prepare(
        "UPDATE adicionais SET estoque_atual = estoque_atual + ? WHERE id = ?"
    )->execute([$qtd, $addId]);

    $pdo->prepare("
        INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo)
        VALUES (?, 'entrada', ?, 'Reposição manual')
    ")->execute([$addId, $qtd]);

    responder(true, "Estoque atualizado (+{$qtd} unidades).");
}


// ============================================================
//  GET: pedidos do dia com itens e adicionais
// ============================================================
function pedidosDoDia(PDO $pdo): void {
    // Pedidos de hoje
    $pedidos = $pdo->query("
        SELECT id, numero, total, observacao, status,
               criado_em, impresso_em
        FROM   pedidos
        WHERE  DATE(criado_em) = CURDATE()
        ORDER  BY criado_em DESC
    ")->fetchAll();

    if (empty($pedidos)) {
        responder(true, 'OK', []);
        return;
    }

    // Busca itens e adicionais de todos os pedidos de uma vez
    $ids = array_column($pedidos, 'id');
    $ph  = implode(',', array_fill(0, count($ids), '?'));

    $itens = $pdo->prepare("
        SELECT ip.id, ip.pedido_id, ip.subtotal, ip.observacao,
               t.nome AS tamanho_nome, t.ml
        FROM   itens_pedido ip
        JOIN   tamanhos t ON t.id = ip.tamanho_id
        WHERE  ip.pedido_id IN ($ph)
        ORDER  BY ip.id
    ");
    $itens->execute($ids);
    $itensList = $itens->fetchAll();

    $itemIds = array_column($itensList, 'id');

    $addsList = [];
    if (!empty($itemIds)) {
        $phI = implode(',', array_fill(0, count($itemIds), '?'));
        $stmtA = $pdo->prepare("
            SELECT ia.item_pedido_id, a.nome, a.icone,
                   ia.quantidade, ia.preco_unitario, ia.subtotal
            FROM   item_adicional ia
            JOIN   adicionais a ON a.id = ia.adicional_id
            WHERE  ia.item_pedido_id IN ($phI)
        ");
        $stmtA->execute($itemIds);
        $addsList = $stmtA->fetchAll();
    }

    // Monta estrutura agrupada
    $addsByItem = [];
    foreach ($addsList as $a) {
        $addsByItem[$a['item_pedido_id']][] = $a;
    }

    $itensByPedido = [];
    foreach ($itensList as $i) {
        $i['adicionais'] = $addsByItem[$i['id']] ?? [];
        $itensByPedido[$i['pedido_id']][] = $i;
    }

    foreach ($pedidos as &$p) {
        $p['itens'] = $itensByPedido[$p['id']] ?? [];
        $p['total'] = (float)$p['total'];
    }

    responder(true, 'OK', $pedidos);
}


// ============================================================
//  GET: relatório de vendas do dia
// ============================================================
function relatorioHoje(PDO $pdo): void {
    $resumo = $pdo->query(
        "SELECT * FROM vw_resumo_vendas_hoje"
    )->fetch();

    // Converte para float
    $resumo['faturamento_total'] = (float)($resumo['faturamento_total'] ?? 0);
    $resumo['ticket_medio']      = (float)($resumo['ticket_medio']      ?? 0);
    $resumo['total_pedidos']     = (int)($resumo['total_pedidos']        ?? 0);

    $maisVendidos = $pdo->query("
        SELECT a.nome, a.icone, SUM(ia.quantidade) AS quantidade
        FROM   item_adicional ia
        JOIN   adicionais a    ON a.id  = ia.adicional_id
        JOIN   itens_pedido ip ON ip.id = ia.item_pedido_id
        JOIN   pedidos p       ON p.id  = ip.pedido_id
        WHERE  DATE(p.criado_em) = CURDATE()
          AND  p.status <> 'cancelado'
        GROUP  BY a.id
        ORDER  BY quantidade DESC
        LIMIT  5
    ")->fetchAll();

    responder(true, 'OK', [
        'resumo'        => $resumo,
        'mais_vendidos' => $maisVendidos,
    ]);
}


// ============================================================
//  POST: criar novo adicional
//  Body: { nome, icone, preco, estoque_atual, estoque_minimo, ativo }
// ============================================================
function novoAdicional(PDO $pdo): void {
    $d = lerJSON();

    if (empty($d['nome']) || !isset($d['preco'])) {
        throw new Exception('nome e preco são obrigatórios.');
    }

    $pdo->prepare("
        INSERT INTO adicionais (nome, icone, preco, estoque_atual, estoque_minimo, ativo)
        VALUES (?, ?, ?, ?, ?, ?)
    ")->execute([
        trim($d['nome']),
        $d['icone']          ?? '🍬',
        (float)$d['preco'],
        (int)($d['estoque_atual']  ?? 0),
        (int)($d['estoque_minimo'] ?? 5),
        (int)($d['ativo']          ?? 1),
    ]);

    responder(true, 'Adicional criado com sucesso!');
}


// ============================================================
//  POST: editar adicional existente
//  Body: { id, nome, icone, preco, estoque_atual, estoque_minimo, ativo }
// ============================================================
function editarAdicional(PDO $pdo): void {
    $d  = lerJSON();
    $id = (int)($d['id'] ?? 0);

    if (!$id || empty($d['nome'])) {
        throw new Exception('id e nome são obrigatórios.');
    }

    // Verifica diferença de estoque pra registrar no log
    $stmtAtual = $pdo->prepare("SELECT estoque_atual FROM adicionais WHERE id = ?");
    $stmtAtual->execute([$id]);
    $atual = (int)($stmtAtual->fetchColumn() ?: 0);

    $novoEstoque = (int)($d['estoque_atual'] ?? 0);
    $diff        = $novoEstoque - (int)$atual;

    $pdo->prepare("
        UPDATE adicionais
        SET nome = ?, icone = ?, preco = ?,
            estoque_atual = ?, estoque_minimo = ?, ativo = ?
        WHERE id = ?
    ")->execute([
        trim($d['nome']),
        $d['icone']          ?? '🍬',
        (float)$d['preco'],
        $novoEstoque,
        (int)($d['estoque_minimo'] ?? 5),
        (int)($d['ativo']          ?? 1),
        $id,
    ]);

    // Registra no log se o estoque foi alterado manualmente
    if ($diff !== 0) {
        $pdo->prepare("
            INSERT INTO movimentos_estoque (adicional_id, tipo, quantidade, motivo)
            VALUES (?, ?, ?, 'Ajuste manual via estoque')
        ")->execute([$id, $diff > 0 ? 'entrada' : 'saida', abs($diff)]);
    }

    responder(true, 'Adicional atualizado!');
}


// ============================================================
//  GET: histórico de movimentos de estoque (últimos 50)
// ============================================================
function historicoEstoque(PDO $pdo): void {
    $rows = $pdo->query("
        SELECT m.tipo, m.quantidade, m.motivo, m.criado_em,
               a.nome, a.icone
        FROM   movimentos_estoque m
        JOIN   adicionais a ON a.id = m.adicional_id
        ORDER  BY m.criado_em DESC
        LIMIT  50
    ")->fetchAll();

    responder(true, 'OK', $rows);
}


// ============================================================
// ============================================================
function lerJSON(): array {
    $raw   = file_get_contents('php://input');
    $dados = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido: ' . json_last_error_msg());
    }

    return $dados ?? [];
}

function responder(bool $ok, string $mensagem, mixed $dados = null): void {
    echo json_encode([
        'ok'       => $ok,
        'mensagem' => $mensagem,
        'dados'    => $dados,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}