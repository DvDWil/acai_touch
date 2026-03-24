-- ============================================================
--  PONTOAÇAÍ – Banco de Dados
--  Cole este conteúdo no phpMyAdmin > aba SQL > Executar
-- ============================================================

CREATE DATABASE IF NOT EXISTS pontoAcai
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE pontoAcai;

-- ── Tamanhos ─────────────────────────────────────────────────
-- acrescimo = valor adicionado ao preço do sabor
-- complementos_gratis = quantos complementos são sem custo
CREATE TABLE tamanhos (
  id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome                  VARCHAR(30)  NOT NULL,
  ml                    SMALLINT     NOT NULL,
  acrescimo             DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  complementos_gratis   TINYINT      NOT NULL DEFAULT 0,
  popular               TINYINT(1)   NOT NULL DEFAULT 0,
  ativo                 TINYINT(1)   NOT NULL DEFAULT 1
);

INSERT INTO tamanhos (nome, ml, acrescimo, complementos_gratis, popular) VALUES
  ('Pequeno', 300, 0.00, 2, 0),
  ('Médio',   400, 3.00, 3, 1),
  ('Grande',  500, 6.00, 4, 0);

-- ── Sabores de Açaí ──────────────────────────────────────────
-- Cada sabor tem seu próprio preço base
-- O preço final do copo = sabor.preco + tamanho.acrescimo
CREATE TABLE sabores (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome    VARCHAR(60)  NOT NULL,
  emoji   VARCHAR(10)  NOT NULL DEFAULT '🍇',
  descricao VARCHAR(120),
  preco   DECIMAL(8,2) NOT NULL,
  ativo   TINYINT(1)   NOT NULL DEFAULT 1
);

INSERT INTO sabores (nome, emoji, descricao, preco) VALUES
  ('Açaí com Banana',   '🍌', 'Clássico e cremoso, levemente adocicado',      10.00),
  ('Açaí com Morango',  '🍓', 'Mais azedinho, com sabor marcante',             10.00),
  ('Açaí Tradicional',  '🍇', 'Puro açaí, intenso e encorpado',                10.00),
  ('Açaí sem Açúcar',   '🌿', 'Natural, sem adição de açúcar',                 10.00),
  ('Creme de Cupuaçu',  '🌰', 'Sabor amazônico intenso e único',               13.00),
  ('Creme de Maracujá', '💛', 'Tropical, refrescante e surpreendente',          12.00);

-- ── Adicionais (complementos) ────────────────────────────────
CREATE TABLE adicionais (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome            VARCHAR(60)  NOT NULL,
  emoji           VARCHAR(10)  NOT NULL DEFAULT '🍬',
  preco           DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  estoque_atual   INT          NOT NULL DEFAULT 0,
  estoque_minimo  INT          NOT NULL DEFAULT 5,
  ativo           TINYINT(1)   NOT NULL DEFAULT 1
);

INSERT INTO adicionais (nome, emoji, preco, estoque_atual, estoque_minimo) VALUES
  ('Granola',          '🌾', 2.00, 50, 5),
  ('Banana',           '🍌', 2.00, 40, 5),
  ('Leite em Pó',      '🥛', 2.50, 30, 3),
  ('Leite Condensado', '🍯', 2.50, 25, 3),
  ('Mel',              '🍀', 3.00, 20, 3),
  ('Coco Ralado',      '🥥', 2.00, 30, 5),
  ('Morango Fresco',   '🍓', 3.00,  0, 5),
  ('Castanha',         '🌰', 3.50, 15, 3),
  ('Chocolate',        '🍫', 2.50, 20, 5);

-- ── Caldas ───────────────────────────────────────────────────
CREATE TABLE caldas (
  id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome  VARCHAR(40)  NOT NULL,
  emoji VARCHAR(10)  NOT NULL DEFAULT '🍯',
  preco DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  ativo TINYINT(1)   NOT NULL DEFAULT 1
);

INSERT INTO caldas (nome, emoji, preco) VALUES
  ('Leite Condensado', '🥛', 0.00),
  ('Mel',              '🍯', 0.00),
  ('Nutella',          '🍫', 4.00),
  ('Sem Calda',        '🚫', 0.00);

-- ── Pedidos ──────────────────────────────────────────────────
CREATE TABLE pedidos (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero       SMALLINT UNSIGNED NOT NULL,
  total        DECIMAL(8,2)      NOT NULL DEFAULT 0.00,
  observacao   TEXT,
  status       ENUM('aberto','impresso','cancelado') NOT NULL DEFAULT 'aberto',
  criado_em    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  impresso_em  DATETIME NULL
);

CREATE INDEX idx_pedidos_data ON pedidos (criado_em);

-- ── Itens do pedido (cada copo) ───────────────────────────────
CREATE TABLE itens_pedido (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id   INT UNSIGNED NOT NULL,
  sabor_id    INT UNSIGNED NOT NULL,
  tamanho_id  INT UNSIGNED NOT NULL,
  preco_sabor DECIMAL(8,2) NOT NULL,   -- preço base do sabor no momento
  acrescimo   DECIMAL(8,2) NOT NULL,   -- acréscimo do tamanho no momento
  calda_id    INT UNSIGNED NULL,
  preco_calda DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  subtotal    DECIMAL(8,2) NOT NULL,
  observacao  TEXT,
  FOREIGN KEY (pedido_id)  REFERENCES pedidos(id)   ON DELETE CASCADE,
  FOREIGN KEY (sabor_id)   REFERENCES sabores(id),
  FOREIGN KEY (tamanho_id) REFERENCES tamanhos(id),
  FOREIGN KEY (calda_id)   REFERENCES caldas(id)
);

-- ── Adicionais de cada copo ───────────────────────────────────
-- ordem_escolha: posição do complemento (define se é grátis ou pago)
-- foi_gratis: 1 se entrou na cota grátis, 0 se foi cobrado
CREATE TABLE item_adicional (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_pedido_id  INT UNSIGNED NOT NULL,
  adicional_id    INT UNSIGNED NOT NULL,
  ordem_escolha   TINYINT      NOT NULL DEFAULT 1,
  foi_gratis      TINYINT(1)   NOT NULL DEFAULT 0,
  preco_unitario  DECIMAL(8,2) NOT NULL,
  FOREIGN KEY (item_pedido_id) REFERENCES itens_pedido(id) ON DELETE CASCADE,
  FOREIGN KEY (adicional_id)   REFERENCES adicionais(id)
);

-- ── Log de movimentos de estoque ─────────────────────────────
CREATE TABLE movimentos_estoque (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  adicional_id  INT UNSIGNED NOT NULL,
  tipo          ENUM('entrada','saida') NOT NULL,
  quantidade    INT          NOT NULL,
  motivo        VARCHAR(100),
  criado_em     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (adicional_id) REFERENCES adicionais(id)
);

-- ── View: estoque baixo ───────────────────────────────────────
CREATE VIEW vw_adicionais_estoque_baixo AS
  SELECT id, nome, emoji, estoque_atual, estoque_minimo
  FROM   adicionais
  WHERE  ativo = 1 AND estoque_atual <= estoque_minimo
  ORDER  BY estoque_atual ASC;

-- ── View: resumo de vendas do dia ────────────────────────────
CREATE VIEW vw_resumo_vendas_hoje AS
  SELECT
    COUNT(*)       AS total_pedidos,
    SUM(total)     AS faturamento_total,
    AVG(total)     AS ticket_medio,
    MIN(criado_em) AS primeiro_pedido,
    MAX(criado_em) AS ultimo_pedido
  FROM pedidos
  WHERE DATE(criado_em) = CURDATE()
    AND status != 'cancelado';

-- ── View: sabores com preço por tamanho ──────────────────────
CREATE VIEW vw_cardapio AS
  SELECT
    s.nome   AS sabor,
    s.emoji,
    t.nome   AS tamanho,
    t.ml,
    (s.preco + t.acrescimo)  AS preco_final,
    t.complementos_gratis
  FROM sabores s
  CROSS JOIN tamanhos t
  WHERE s.ativo = 1 AND t.ativo = 1
  ORDER BY s.nome, t.ml;
