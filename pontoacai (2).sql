-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/05/2026 às 01:12
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
  `emoji` varchar(10) DEFAULT '✨',
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `estoque_atual` int(11) DEFAULT 0,
  `estoque_minimo` int(11) DEFAULT 5,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adicionais`
--

INSERT INTO `adicionais` (`id`, `nome`, `emoji`, `preco`, `estoque_atual`, `estoque_minimo`, `ativo`) VALUES
(1, 'Banana', '🍌', 1.90, 41, 5, 1),
(2, 'Uva', '🍇', 1.90, 46, 5, 1),
(3, 'Paçoca', '🟤', 1.90, 42, 5, 1),
(4, 'Leite em Pó', '🥛', 1.90, 45, 5, 1),
(5, 'Granola', '🌰', 1.90, 41, 5, 1),
(6, 'Farinha Láctea', '🥛', 1.90, 42, 5, 1),
(7, 'Bolinhas de Chocolate', '🍫', 1.90, 42, 5, 1),
(8, 'Amendoim Triturado', '🥜', 1.90, 48, 5, 1),
(9, 'Confetes', '🎊', 1.90, 48, 5, 1),
(10, 'Ovomaltine', '🟫', 2.50, 45, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bebidas`
--

CREATE TABLE `bebidas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `emoji` varchar(10) NOT NULL DEFAULT '?',
  `descricao` varchar(120) DEFAULT NULL,
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bebidas`
--

INSERT INTO `bebidas` (`id`, `nome`, `emoji`, `descricao`, `preco`, `ativo`) VALUES
(1, 'Água Mineral s/ Gás 500ml', '💧', 'Garrafa 500ml sem gás', 2.80, 1),
(2, 'Água Mineral c/ Gás 500ml', '💦', 'Garrafa 500ml com gás', 3.00, 1),
(3, 'Água Mineral 200ml (Combo)', '🫙', 'Copo 200ml — acréscimo combo', 1.50, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `caldas`
--

CREATE TABLE `caldas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `emoji` varchar(10) DEFAULT '?',
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_300` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_400` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_500` decimal(8,2) NOT NULL DEFAULT 0.00,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `caldas`
--

INSERT INTO `caldas` (`id`, `nome`, `emoji`, `preco`, `preco_300`, `preco_400`, `preco_500`, `ativo`) VALUES
(1, 'Leite Condensado', '🥛', 0.00, 0.00, 0.00, 0.00, 1),
(2, 'Chocolate', '🍫', 0.00, 0.00, 0.00, 0.00, 1),
(3, 'Morango', '🍓', 0.00, 0.00, 0.00, 0.00, 1),
(4, 'Nutella', '🟫', 0.00, 4.90, 5.90, 6.90, 1),
(5, 'Sem Calda', '🚫', 0.00, 0.00, 0.00, 0.00, 1);

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
  `preco_unitario` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item_adicional`
--

INSERT INTO `item_adicional` (`id`, `item_pedido_id`, `adicional_id`, `ordem_escolha`, `foi_gratis`, `preco_unitario`) VALUES
(11, 3, 7, 1, 1, 1.90),
(12, 3, 1, 2, 1, 1.90),
(13, 4, 6, 1, 1, 1.90),
(14, 4, 1, 2, 1, 1.90),
(15, 4, 8, 3, 1, 1.90),
(16, 4, 7, 4, 0, 1.90),
(17, 5, 1, 1, 1, 1.90),
(18, 5, 6, 2, 1, 1.90),
(19, 5, 7, 3, 1, 1.90),
(20, 5, 5, 4, 0, 1.90),
(21, 6, 4, 1, 1, 1.90),
(22, 6, 3, 2, 1, 1.90),
(23, 7, 1, 1, 1, 1.90),
(24, 7, 4, 2, 1, 1.90),
(25, 7, 3, 3, 1, 1.90),
(26, 7, 9, 4, 1, 1.90),
(27, 8, 6, 1, 1, 1.90),
(28, 8, 5, 2, 1, 1.90),
(29, 8, 2, 3, 0, 1.90),
(30, 9, 10, 1, 1, 2.50),
(31, 9, 3, 2, 1, 1.90),
(32, 9, 6, 3, 1, 1.90),
(33, 9, 5, 4, 1, 1.90),
(34, 9, 7, 5, 0, 1.90),
(35, 10, 6, 1, 1, 1.90),
(36, 10, 5, 2, 1, 1.90),
(37, 10, 7, 3, 1, 1.90),
(38, 10, 1, 4, 1, 1.90),
(39, 10, 10, 5, 0, 2.50),
(40, 11, 10, 1, 1, 2.50),
(41, 11, 6, 2, 1, 1.90),
(42, 11, 3, 3, 1, 1.90),
(43, 12, 7, 1, 1, 1.90),
(44, 12, 5, 2, 1, 1.90),
(45, 12, 3, 3, 1, 1.90),
(46, 12, 10, 4, 1, 2.50),
(47, 13, 10, 1, 1, 2.50),
(48, 13, 3, 2, 1, 1.90),
(49, 13, 5, 3, 1, 1.90),
(50, 13, 6, 4, 1, 1.90),
(51, 13, 1, 5, 0, 1.90),
(52, 13, 7, 6, 0, 1.90),
(53, 13, 8, 7, 0, 1.90),
(54, 13, 9, 8, 0, 1.90),
(55, 13, 4, 9, 0, 1.90),
(56, 13, 2, 10, 0, 1.90),
(57, 14, 5, 1, 1, 1.90),
(58, 14, 6, 2, 1, 1.90),
(59, 14, 1, 3, 1, 1.90),
(60, 14, 7, 4, 1, 1.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `sabor_id` int(10) UNSIGNED DEFAULT NULL,
  `sabor_id2` int(10) UNSIGNED DEFAULT NULL,
  `sabor2_nome` varchar(60) DEFAULT NULL,
  `tamanho_id` int(10) UNSIGNED DEFAULT NULL,
  `calda_id` int(10) UNSIGNED DEFAULT NULL,
  `calda_id2` int(10) UNSIGNED DEFAULT NULL,
  `bebida_id` int(10) UNSIGNED DEFAULT NULL,
  `preco_unidade` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_bebida` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_sabor` decimal(8,2) NOT NULL DEFAULT 0.00,
  `acrescimo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_calda` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_calda2` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `sabor_id`, `sabor_id2`, `sabor2_nome`, `tamanho_id`, `calda_id`, `calda_id2`, `bebida_id`, `preco_unidade`, `preco_bebida`, `preco_sabor`, `acrescimo`, `preco_calda`, `preco_calda2`, `subtotal`) VALUES
(3, 4, 2, NULL, NULL, 1, 4, NULL, 2, 0.00, 3.00, 14.90, 0.00, 0.00, 0.00, 43.70),
(4, 5, 1, NULL, NULL, 2, 3, NULL, 2, 0.00, 3.00, 12.90, 0.00, 5.90, 0.00, 49.50),
(5, 6, 3, NULL, NULL, 2, 3, NULL, 2, 0.00, 3.00, 15.90, 0.00, 5.90, 0.00, 52.50),
(6, 7, 1, NULL, NULL, 1, 1, NULL, 3, 0.00, 1.50, 12.90, 0.00, 0.00, 0.00, 47.90),
(7, 8, 1, NULL, NULL, 3, 1, NULL, 1, 0.00, 2.80, 19.90, 0.00, 0.00, 0.00, 22.70),
(8, 9, 2, NULL, NULL, 1, 4, NULL, NULL, 0.00, 0.00, 14.90, 0.00, 0.00, 0.00, 41.50),
(9, 10, 2, 6, NULL, 3, 1, 3, 2, 0.00, 3.00, 22.90, 0.00, 0.00, 6.90, 54.10),
(10, 11, 6, 1, NULL, 3, 3, 1, 2, 0.00, 3.00, 22.90, 0.00, 6.90, 0.00, 77.00),
(11, 12, 2, 5, NULL, 3, 3, 3, 2, 0.00, 3.00, 22.90, 0.00, 6.90, 6.90, 32.80),
(12, 13, 6, NULL, NULL, 3, 1, 2, 2, 0.00, 3.00, 22.90, 0.00, 0.00, 0.00, 51.80),
(13, 14, 6, 5, NULL, 3, 3, 2, 2, 0.00, 3.00, 22.90, 0.00, 0.00, 0.00, 70.00),
(14, 15, 2, 6, NULL, 3, 2, 3, 2, 0.00, 3.00, 22.90, 0.00, 0.00, 0.00, 25.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentos_estoque`
--

CREATE TABLE `movimentos_estoque` (
  `id` int(10) UNSIGNED NOT NULL,
  `adicional_id` int(10) UNSIGNED NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `motivo` varchar(255) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentos_estoque`
--

INSERT INTO `movimentos_estoque` (`id`, `adicional_id`, `tipo`, `quantidade`, `motivo`, `criado_em`) VALUES
(1, 4, 'saida', 1, 'Pedido #1:2', '2026-04-24 21:41:36'),
(2, 5, 'saida', 1, 'Pedido #1:2', '2026-04-24 21:41:36'),
(3, 2, 'saida', 1, 'Pedido #1:2', '2026-04-24 21:41:36'),
(4, 3, 'saida', 1, 'Pedido #1:2', '2026-04-24 21:41:36'),
(5, 1, 'saida', 1, 'Pedido #1:2', '2026-04-24 21:41:36'),
(6, 4, 'saida', 1, 'Pedido #2:3', '2026-04-24 21:46:13'),
(7, 2, 'saida', 1, 'Pedido #2:3', '2026-04-24 21:46:13'),
(8, 5, 'saida', 1, 'Pedido #2:3', '2026-04-24 21:46:13'),
(9, 3, 'saida', 1, 'Pedido #2:3', '2026-04-24 21:46:13'),
(10, 1, 'saida', 1, 'Pedido #2:3', '2026-04-24 21:46:13'),
(11, 7, 'saida', 1, 'Pedido #3:4', '2026-04-24 22:02:27'),
(12, 1, 'saida', 1, 'Pedido #3:4', '2026-04-24 22:02:27'),
(13, 6, 'saida', 1, 'Pedido #4:5', '2026-04-24 22:04:01'),
(14, 1, 'saida', 1, 'Pedido #4:5', '2026-04-24 22:04:01'),
(15, 8, 'saida', 1, 'Pedido #4:5', '2026-04-24 22:04:01'),
(16, 7, 'saida', 1, 'Pedido #4:5', '2026-04-24 22:04:01'),
(17, 1, 'saida', 1, 'Pedido #5:6', '2026-04-24 22:05:49'),
(18, 6, 'saida', 1, 'Pedido #5:6', '2026-04-24 22:05:49'),
(19, 7, 'saida', 1, 'Pedido #5:6', '2026-04-24 22:05:49'),
(20, 5, 'saida', 1, 'Pedido #5:6', '2026-04-24 22:05:49'),
(21, 4, 'saida', 1, 'Pedido #6:7', '2026-04-24 22:10:21'),
(22, 3, 'saida', 1, 'Pedido #6:7', '2026-04-24 22:10:21'),
(23, 1, 'saida', 1, 'Pedido #7:8', '2026-04-24 22:12:04'),
(24, 4, 'saida', 1, 'Pedido #7:8', '2026-04-24 22:12:04'),
(25, 3, 'saida', 1, 'Pedido #7:8', '2026-04-24 22:12:04'),
(26, 9, 'saida', 1, 'Pedido #7:8', '2026-04-24 22:12:04'),
(27, 6, 'saida', 1, 'Pedido #1:9', '2026-05-01 19:03:02'),
(28, 5, 'saida', 1, 'Pedido #1:9', '2026-05-01 19:03:02'),
(29, 2, 'saida', 1, 'Pedido #1:9', '2026-05-01 19:03:02'),
(30, 10, 'saida', 1, 'Pedido #1:10', '2026-05-02 19:15:23'),
(31, 3, 'saida', 1, 'Pedido #1:10', '2026-05-02 19:15:23'),
(32, 6, 'saida', 1, 'Pedido #1:10', '2026-05-02 19:15:23'),
(33, 5, 'saida', 1, 'Pedido #1:10', '2026-05-02 19:15:23'),
(34, 7, 'saida', 1, 'Pedido #1:10', '2026-05-02 19:15:23'),
(35, 6, 'saida', 1, 'Pedido #2:11', '2026-05-02 19:20:31'),
(36, 5, 'saida', 1, 'Pedido #2:11', '2026-05-02 19:20:31'),
(37, 7, 'saida', 1, 'Pedido #2:11', '2026-05-02 19:20:31'),
(38, 1, 'saida', 1, 'Pedido #2:11', '2026-05-02 19:20:31'),
(39, 10, 'saida', 1, 'Pedido #2:11', '2026-05-02 19:20:31'),
(40, 10, 'saida', 1, 'Pedido #3:12', '2026-05-02 19:23:06'),
(41, 6, 'saida', 1, 'Pedido #3:12', '2026-05-02 19:23:06'),
(42, 3, 'saida', 1, 'Pedido #3:12', '2026-05-02 19:23:06'),
(43, 7, 'saida', 1, 'Pedido #4:13', '2026-05-02 19:26:02'),
(44, 5, 'saida', 1, 'Pedido #4:13', '2026-05-02 19:26:02'),
(45, 3, 'saida', 1, 'Pedido #4:13', '2026-05-02 19:26:02'),
(46, 10, 'saida', 1, 'Pedido #4:13', '2026-05-02 19:26:02'),
(47, 10, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(48, 3, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(49, 5, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(50, 6, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(51, 1, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(52, 7, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(53, 8, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(54, 9, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(55, 4, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(56, 2, 'saida', 1, 'Pedido #5:14', '2026-05-02 19:29:08'),
(57, 5, 'saida', 1, 'Pedido #6:15', '2026-05-02 19:53:20'),
(58, 6, 'saida', 1, 'Pedido #6:15', '2026-05-02 19:53:20'),
(59, 1, 'saida', 1, 'Pedido #6:15', '2026-05-02 19:53:20'),
(60, 7, 'saida', 1, 'Pedido #6:15', '2026-05-02 19:53:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_hora` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pendente','preparando','concluido','cancelado') DEFAULT 'pendente',
  `numero` int(11) NOT NULL DEFAULT 0,
  `observacao` varchar(500) NOT NULL DEFAULT '',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `data_hora`, `total`, `status`, `numero`, `observacao`, `criado_em`) VALUES
(4, '2026-04-24 22:02:27', 43.70, 'pendente', 3, '', '2026-04-24 22:02:27'),
(5, '2026-04-24 22:04:01', 49.50, 'pendente', 4, '', '2026-04-24 22:04:01'),
(6, '2026-04-24 22:05:49', 52.50, 'pendente', 5, '', '2026-04-24 22:05:49'),
(7, '2026-04-24 22:10:21', 47.90, 'pendente', 6, '', '2026-04-24 22:10:21'),
(8, '2026-04-24 22:12:04', 22.70, 'pendente', 7, '', '2026-04-24 22:12:04'),
(9, '2026-05-01 19:03:02', 41.50, 'pendente', 1, '', '2026-05-01 19:03:02'),
(10, '2026-05-02 19:15:23', 54.10, 'pendente', 1, '', '2026-05-02 19:15:23'),
(11, '2026-05-02 19:20:31', 77.00, 'pendente', 2, '', '2026-05-02 19:20:31'),
(12, '2026-05-02 19:23:06', 32.80, 'pendente', 3, '', '2026-05-02 19:23:06'),
(13, '2026-05-02 19:26:02', 51.80, 'pendente', 4, '', '2026-05-02 19:26:02'),
(14, '2026-05-02 19:29:08', 70.00, 'pendente', 5, '', '2026-05-02 19:29:08'),
(15, '2026-05-02 19:53:20', 25.90, 'pendente', 6, '', '2026-05-02 19:53:20');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sabores`
--

CREATE TABLE `sabores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `emoji` varchar(10) DEFAULT '?',
  `descricao` varchar(255) DEFAULT NULL,
  `preco` decimal(8,2) NOT NULL DEFAULT 0.00,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `preco_300` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_400` decimal(8,2) NOT NULL DEFAULT 0.00,
  `preco_500` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sabores`
--

INSERT INTO `sabores` (`id`, `nome`, `emoji`, `descricao`, `preco`, `ativo`, `preco_300`, `preco_400`, `preco_500`) VALUES
(1, 'Tradicional', '🫐', 'Açaí puro cremoso, sabor clássico', 12.90, 1, 12.90, 16.90, 19.90),
(2, 'Batido com Morango', '🍓', 'Açaí batido com morango fresco', 14.90, 1, 14.90, 17.90, 20.90),
(3, 'Zero Açúcar', '🌿', 'Natural, sem adição de açúcar', 15.90, 1, 15.90, 18.90, 22.90),
(5, 'Creme de Cupuaçu', '🟡', 'Açaí cremoso com sabor intenso de cupuaçu', 15.90, 1, 15.90, 18.90, 21.90),
(6, 'Creme de Maracujá', '🟠', 'Açaí suave com toque tropical de maracujá', 15.90, 1, 15.90, 18.90, 21.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tamanhos`
--

CREATE TABLE `tamanhos` (
  `id` int(10) UNSIGNED NOT NULL,
  `ml` int(11) NOT NULL,
  `acrescimo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `complementos_gratis` tinyint(4) NOT NULL DEFAULT 0,
  `caldas_gratis` tinyint(4) NOT NULL DEFAULT 1,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `popular` tinyint(1) NOT NULL DEFAULT 0,
  `nome` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tamanhos`
--

INSERT INTO `tamanhos` (`id`, `ml`, `acrescimo`, `complementos_gratis`, `caldas_gratis`, `ativo`, `popular`, `nome`) VALUES
(1, 300, 0.00, 2, 1, 1, 0, 'Pequeno'),
(2, 400, 0.00, 3, 1, 1, 1, 'Médio'),
(3, 500, 0.00, 4, 2, 1, 0, 'Grande');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adicionais`
--
ALTER TABLE `adicionais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bebidas`
--
ALTER TABLE `bebidas`
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
  ADD KEY `item_pedido_id` (`item_pedido_id`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Índices de tabela `movimentos_estoque`
--
ALTER TABLE `movimentos_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `bebidas`
--
ALTER TABLE `bebidas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `caldas`
--
ALTER TABLE `caldas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `item_adicional`
--
ALTER TABLE `item_adicional`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `movimentos_estoque`
--
ALTER TABLE `movimentos_estoque`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `sabores`
--
ALTER TABLE `sabores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `item_adicional_ibfk_1` FOREIGN KEY (`item_pedido_id`) REFERENCES `itens_pedido` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
