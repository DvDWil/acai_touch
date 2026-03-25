-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/03/2026 às 19:54
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pontoacai`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adicionais`
--

CREATE TABLE `adicionais` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `estoque_atual` int(11) NOT NULL DEFAULT 0,
  `estoque_minimo` int(11) NOT NULL DEFAULT 5,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adicionais`
--

INSERT INTO `adicionais` (`id`, `nome`, `emoji`, `preco`, `estoque_atual`, `estoque_minimo`, `ativo`) VALUES
(1, 'Granola', '🌾', 2.00, 46, 5, 1),
(2, 'Banana', '🍌', 2.00, 47, 5, 1),
(3, 'Leite em Pó', '🥛', 2.50, 47, 3, 1),
(4, 'Leite Condensado', '🍯', 2.50, 45, 3, 1),
(5, 'Mel', '🍀', 3.00, 46, 3, 1),
(6, 'Coco Ralado', '🥥', 2.00, 46, 5, 1),
(7, 'Morango Fresco', '🍓', 3.00, 46, 5, 1),
(8, 'Castanha', '🌰', 3.50, 46, 3, 1),
(9, 'Chocolate', '🍫', 2.50, 46, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `caldas`
--

CREATE TABLE `caldas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(40) NOT NULL,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `caldas`
--

INSERT INTO `caldas` (`id`, `nome`, `emoji`, `preco`, `ativo`) VALUES
(1, 'Leite Condensado', '🥛', 0.00, 1),
(2, 'Mel', '🍯', 0.00, 1),
(3, 'Nutella', '🍫', 4.00, 1),
(4, 'Sem Calda', '🚫', 0.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_adicional`
--

CREATE TABLE `item_adicional` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_pedido_id` int(10) UNSIGNED NOT NULL,
  `adicional_id` int(10) UNSIGNED NOT NULL,
  `ordem_escolha` tinyint(4) NOT NULL DEFAULT 1,
  `foi_gratis` tinyint(1) NOT NULL DEFAULT 0,
  `preco_unitario` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `item_adicional`
--

INSERT INTO `item_adicional` (`id`, `item_pedido_id`, `adicional_id`, `ordem_escolha`, `foi_gratis`, `preco_unitario`) VALUES
(1, 1, 8, 1, 1, 3.50),
(2, 1, 2, 2, 1, 2.00),
(3, 1, 6, 3, 1, 2.00),
(4, 2, 4, 1, 1, 2.50),
(5, 2, 1, 2, 1, 2.00),
(6, 2, 8, 3, 1, 3.50),
(7, 2, 6, 4, 0, 2.00),
(8, 2, 3, 5, 0, 2.50),
(9, 3, 8, 1, 1, 3.50),
(10, 3, 1, 2, 1, 2.00),
(11, 3, 4, 3, 1, 2.50),
(12, 3, 9, 4, 0, 2.50),
(13, 3, 2, 5, 0, 2.00),
(14, 3, 6, 6, 0, 2.00),
(15, 3, 3, 7, 0, 2.50),
(16, 3, 5, 8, 0, 3.00),
(17, 3, 7, 9, 0, 3.00),
(18, 4, 9, 1, 1, 2.50),
(19, 4, 4, 2, 1, 2.50),
(20, 4, 7, 3, 1, 3.00),
(21, 4, 5, 4, 0, 3.00),
(22, 5, 9, 1, 1, 2.50),
(23, 5, 4, 2, 1, 2.50),
(24, 5, 7, 3, 1, 3.00),
(25, 5, 5, 4, 1, 3.00),
(26, 5, 1, 5, 0, 2.00),
(27, 6, 9, 1, 1, 2.50),
(28, 6, 4, 2, 1, 2.50),
(29, 6, 7, 3, 1, 3.00),
(30, 6, 5, 4, 1, 3.00),
(31, 6, 1, 5, 0, 2.00),
(32, 6, 8, 6, 0, 3.50),
(33, 6, 2, 7, 0, 2.00),
(34, 6, 6, 8, 0, 2.00),
(35, 6, 3, 9, 0, 2.50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `sabor_id` int(10) UNSIGNED NOT NULL,
  `tamanho_id` int(10) UNSIGNED NOT NULL,
  `preco_sabor` decimal(8,2) NOT NULL,
  `acrescimo` decimal(8,2) NOT NULL,
  `calda_id` int(10) UNSIGNED DEFAULT NULL,
  `preco_calda` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(8,2) NOT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `sabor_id`, `tamanho_id`, `preco_sabor`, `acrescimo`, `calda_id`, `preco_calda`, `subtotal`, `observacao`) VALUES
(1, 1, 2, 2, 10.00, 3.00, 3, 4.00, 17.00, NULL),
(2, 2, 2, 2, 16.00, 2.00, 4, 0.00, 22.50, NULL),
(3, 3, 2, 2, 16.00, 2.00, 3, 4.00, 37.00, NULL),
(4, 4, 5, 2, 15.00, 2.00, 4, 0.00, 20.00, NULL),
(5, 5, 5, 3, 15.00, 4.00, 4, 0.00, 21.00, NULL),
(6, 6, 2, 3, 16.00, 4.00, 4, 0.00, 50.00, NULL),
(7, 7, 1, 2, 14.00, 2.00, 4, 0.00, 30.00, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentos_estoque`
--

CREATE TABLE `movimentos_estoque` (
  `id` int(10) UNSIGNED NOT NULL,
  `adicional_id` int(10) UNSIGNED NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `movimentos_estoque`
--

INSERT INTO `movimentos_estoque` (`id`, `adicional_id`, `tipo`, `quantidade`, `motivo`, `criado_em`) VALUES
(1, 1, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(2, 2, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(3, 3, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(4, 4, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(5, 5, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(6, 6, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(7, 7, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(8, 8, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(9, 9, 'entrada', 50, 'Reposição total admin', '2026-03-24 20:30:11'),
(10, 8, 'saida', 1, 'Pedido #1:1', '2026-03-24 20:33:01'),
(11, 2, 'saida', 1, 'Pedido #1:1', '2026-03-24 20:33:01'),
(12, 6, 'saida', 1, 'Pedido #1:1', '2026-03-24 20:33:01'),
(13, 4, 'saida', 1, 'Pedido #2:2', '2026-03-24 20:48:45'),
(14, 1, 'saida', 1, 'Pedido #2:2', '2026-03-24 20:48:45'),
(15, 8, 'saida', 1, 'Pedido #2:2', '2026-03-24 20:48:45'),
(16, 6, 'saida', 1, 'Pedido #2:2', '2026-03-24 20:48:45'),
(17, 3, 'saida', 1, 'Pedido #2:2', '2026-03-24 20:48:45'),
(18, 8, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(19, 1, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(20, 4, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(21, 9, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(22, 2, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(23, 6, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(24, 3, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(25, 5, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(26, 7, 'saida', 1, 'Pedido #3:3', '2026-03-24 20:55:22'),
(27, 9, 'saida', 1, 'Pedido #4:4', '2026-03-24 21:08:52'),
(28, 4, 'saida', 1, 'Pedido #4:4', '2026-03-24 21:08:52'),
(29, 7, 'saida', 1, 'Pedido #4:4', '2026-03-24 21:08:52'),
(30, 5, 'saida', 1, 'Pedido #4:4', '2026-03-24 21:08:52'),
(31, 9, 'saida', 1, 'Pedido #5:5', '2026-03-24 21:16:56'),
(32, 4, 'saida', 1, 'Pedido #5:5', '2026-03-24 21:16:56'),
(33, 7, 'saida', 1, 'Pedido #5:5', '2026-03-24 21:16:56'),
(34, 5, 'saida', 1, 'Pedido #5:5', '2026-03-24 21:16:56'),
(35, 1, 'saida', 1, 'Pedido #5:5', '2026-03-24 21:16:56'),
(36, 9, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(37, 4, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(38, 7, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(39, 5, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(40, 1, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(41, 8, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(42, 2, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(43, 6, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09'),
(44, 3, 'saida', 1, 'Pedido #1:6', '2026-03-25 15:47:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `numero` smallint(5) UNSIGNED NOT NULL,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `observacao` text DEFAULT NULL,
  `status` enum('aberto','impresso','cancelado') NOT NULL DEFAULT 'aberto',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `impresso_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `numero`, `total`, `observacao`, `status`, `criado_em`, `impresso_em`) VALUES
(1, 1, 17.00, 'teste', 'aberto', '2026-03-24 20:33:01', NULL),
(2, 2, 22.50, '', 'aberto', '2026-03-24 20:48:45', NULL),
(3, 3, 37.00, '', 'aberto', '2026-03-24 20:55:22', NULL),
(4, 4, 20.00, '', 'aberto', '2026-03-24 21:08:52', NULL),
(5, 5, 21.00, '', 'aberto', '2026-03-24 21:16:56', NULL),
(6, 1, 50.00, '', 'aberto', '2026-03-25 15:47:09', NULL),
(7, 2, 30.00, '', 'aberto', '2026-03-25 15:49:51', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sabores`
--

CREATE TABLE `sabores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `descricao` varchar(120) DEFAULT NULL,
  `preco` decimal(8,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sabores`
--

INSERT INTO `sabores` (`id`, `nome`, `emoji`, `descricao`, `preco`, `ativo`) VALUES
(1, 'Açaí com Banana', '🍌', 'Clássico e cremoso, levemente adocicado', 14.00, 1),
(2, 'Açaí com Morango', '🍓', 'Mais azedinho, com sabor marcante', 16.00, 1),
(4, 'Açaí sem Açúcar', '🌿', 'Natural, sem adição de açúcar', 16.00, 1),
(5, 'Creme de Cupuaçu', '🌰', 'Sabor amazônico intenso e único', 15.00, 1),
(6, 'Creme de Maracujá', '🟡', 'Tropical, refrescante e surpreendente', 15.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tamanhos`
--

CREATE TABLE `tamanhos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(30) NOT NULL,
  `ml` smallint(6) NOT NULL,
  `acrescimo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `complementos_gratis` tinyint(4) NOT NULL DEFAULT 0,
  `popular` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tamanhos`
--

INSERT INTO `tamanhos` (`id`, `nome`, `ml`, `acrescimo`, `complementos_gratis`, `popular`, `ativo`) VALUES
(1, 'Pequeno', 300, 0.00, 2, 0, 1),
(2, 'Médio', 400, 2.00, 3, 1, 1),
(3, 'Grande', 500, 4.00, 4, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_adicionais_estoque_baixo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_adicionais_estoque_baixo` (
`id` int(10) unsigned
,`nome` varchar(60)
,`emoji` varchar(10)
,`estoque_atual` int(11)
,`estoque_minimo` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_cardapio`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_cardapio` (
`sabor` varchar(60)
,`emoji` varchar(10)
,`tamanho` varchar(30)
,`ml` smallint(6)
,`preco_final` decimal(9,2)
,`complementos_gratis` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_resumo_vendas_hoje`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_resumo_vendas_hoje` (
`total_pedidos` bigint(21)
,`faturamento_total` decimal(30,2)
,`ticket_medio` decimal(12,6)
,`primeiro_pedido` datetime
,`ultimo_pedido` datetime
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_adicionais_estoque_baixo`
--
DROP TABLE IF EXISTS `vw_adicionais_estoque_baixo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_adicionais_estoque_baixo`  AS SELECT `adicionais`.`id` AS `id`, `adicionais`.`nome` AS `nome`, `adicionais`.`emoji` AS `emoji`, `adicionais`.`estoque_atual` AS `estoque_atual`, `adicionais`.`estoque_minimo` AS `estoque_minimo` FROM `adicionais` WHERE `adicionais`.`ativo` = 1 AND `adicionais`.`estoque_atual` <= `adicionais`.`estoque_minimo` ORDER BY `adicionais`.`estoque_atual` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_cardapio`
--
DROP TABLE IF EXISTS `vw_cardapio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cardapio`  AS SELECT `s`.`nome` AS `sabor`, `s`.`emoji` AS `emoji`, `t`.`nome` AS `tamanho`, `t`.`ml` AS `ml`, `s`.`preco`+ `t`.`acrescimo` AS `preco_final`, `t`.`complementos_gratis` AS `complementos_gratis` FROM (`sabores` `s` join `tamanhos` `t`) WHERE `s`.`ativo` = 1 AND `t`.`ativo` = 1 ORDER BY `s`.`nome` ASC, `t`.`ml` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_resumo_vendas_hoje`
--
DROP TABLE IF EXISTS `vw_resumo_vendas_hoje`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_resumo_vendas_hoje`  AS SELECT count(0) AS `total_pedidos`, sum(`pedidos`.`total`) AS `faturamento_total`, avg(`pedidos`.`total`) AS `ticket_medio`, min(`pedidos`.`criado_em`) AS `primeiro_pedido`, max(`pedidos`.`criado_em`) AS `ultimo_pedido` FROM `pedidos` WHERE cast(`pedidos`.`criado_em` as date) = curdate() AND `pedidos`.`status` <> 'cancelado' ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adicionais`
--
ALTER TABLE `adicionais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `caldas`
--
ALTER TABLE `caldas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `item_adicional`
--
ALTER TABLE `item_adicional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_pedido_id` (`item_pedido_id`),
  ADD KEY `adicional_id` (`adicional_id`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `sabor_id` (`sabor_id`),
  ADD KEY `tamanho_id` (`tamanho_id`),
  ADD KEY `calda_id` (`calda_id`);

--
-- Índices de tabela `movimentos_estoque`
--
ALTER TABLE `movimentos_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adicional_id` (`adicional_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedidos_data` (`criado_em`);

--
-- Índices de tabela `sabores`
--
ALTER TABLE `sabores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tamanhos`
--
ALTER TABLE `tamanhos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adicionais`
--
ALTER TABLE `adicionais`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `caldas`
--
ALTER TABLE `caldas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `item_adicional`
--
ALTER TABLE `item_adicional`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `movimentos_estoque`
--
ALTER TABLE `movimentos_estoque`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `sabores`
--
ALTER TABLE `sabores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tamanhos`
--
ALTER TABLE `tamanhos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item_adicional`
--
ALTER TABLE `item_adicional`
  ADD CONSTRAINT `item_adicional_ibfk_1` FOREIGN KEY (`item_pedido_id`) REFERENCES `itens_pedido` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_adicional_ibfk_2` FOREIGN KEY (`adicional_id`) REFERENCES `adicionais` (`id`);

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`sabor_id`) REFERENCES `sabores` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_3` FOREIGN KEY (`tamanho_id`) REFERENCES `tamanhos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_4` FOREIGN KEY (`calda_id`) REFERENCES `caldas` (`id`);

--
-- Restrições para tabelas `movimentos_estoque`
--
ALTER TABLE `movimentos_estoque`
  ADD CONSTRAINT `movimentos_estoque_ibfk_1` FOREIGN KEY (`adicional_id`) REFERENCES `adicionais` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
