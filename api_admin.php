<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']); exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    echo json_encode(['ok' => false, 'erro' => 'Payload inválido']); exit;
}

try {
    $pdo   = conectar();
    $acao  = $body['acao']  ?? '';
    $id    = $body['id']    ?? null;
    $valor = $body['valor'] ?? null;

    switch ($acao) {

        case 'set_estoque':
            $pdo->prepare("UPDATE adicionais SET estoque_atual = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'repor_tudo':
            $pdo->exec("UPDATE adicionais SET estoque_atual = 50");
            break;

        case 'set_preco_sabor':
            $pdo->prepare("UPDATE sabores SET preco = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'set_preco_adicional':
            $pdo->prepare("UPDATE adicionais SET preco = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'set_preco_calda':
            $pdo->prepare("UPDATE caldas SET preco = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'set_preco_nutella':
            $ml  = $valor['ml'];
            $val = $valor['val'];
            $pdo->prepare("UPDATE caldas SET preco_{$ml} = ? WHERE id = ?")
                ->execute([$val, $id]);
            break;

        case 'set_acrescimo':
            $pdo->prepare("UPDATE tamanhos SET acrescimo = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'set_gratis':
            $pdo->prepare("UPDATE tamanhos SET complementos_gratis = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'set_caldas_gratis':
            $pdo->prepare("UPDATE tamanhos SET caldas_gratis = ? WHERE id = ?")
                ->execute([$valor, $id]);
            break;

        case 'get_config':
            $result = [
                'adicionais' => $pdo->query("SELECT * FROM adicionais WHERE ativo=1")->fetchAll(),
                'sabores'    => $pdo->query("SELECT * FROM sabores    WHERE ativo=1")->fetchAll(),
                'caldas'     => $pdo->query("SELECT * FROM caldas     WHERE ativo=1")->fetchAll(),
                'tamanhos'   => $pdo->query("SELECT * FROM tamanhos   WHERE ativo=1")->fetchAll(),
            ];
            echo json_encode(['ok' => true] + $result);
            exit;

        default:
            echo json_encode(['ok' => false, 'erro' => 'Ação desconhecida: '.$acao]);
            exit;
    }

    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'erro' => $e->getMessage()]);
}