-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 13/11/2024 às 18:13
-- Versão do servidor: 10.5.26-MariaDB-cll-lve
-- Versão do PHP: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ifhostgru_feitoamaop`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `usuario_pedido` varchar(100) NOT NULL,
  `usuario_contato` varchar(100) NOT NULL,
  `produto` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `id_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `usuario_pedido`, `usuario_contato`, `produto`, `descricao`, `valor_total`, `data_criacao`, `quantidade`, `id_prod`) VALUES
(136, 'pedro', 'pedro@gmail.com 111111111111111', '1', '1', 4.00, '2024-11-13 16:05:53', 4, 2896),
(137, 'pedro', 'pedro@gmail.com 111111111111111', '8', '8', 32.00, '2024-11-13 16:05:53', 4, 2903),
(138, 'pedro', 'pedro@gmail.com 111111111111111', '9', '9', 9.00, '2024-11-13 16:05:53', 1, 2904);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `usuario_pedido` varchar(255) NOT NULL,
  `usuario_contato` varchar(255) NOT NULL,
  `produtos` text NOT NULL,
  `preco_total` decimal(10,2) NOT NULL,
  `status` enum('pendente','processando','pago','produção','finalizado','cancelado','reembolsado','processando reembolso') NOT NULL DEFAULT 'pendente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_prod` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `usuario_pedido`, `usuario_contato`, `produtos`, `preco_total`, `status`, `created_at`, `updated_at`, `id_prod`) VALUES
(459, 'pedro', 'pedro@gmail.com 111111111111111', 'Produto: 1 (Descrição: 1, Quantidade: 4) // Produto: 8 (Descrição: 8, Quantidade: 4) // Produto: 9 (Descrição: 9, Quantidade: 1) // Produto: 10 (Descrição: 10, Quantidade: 4)', 85.00, 'cancelado', '2024-11-13 16:05:51', '2024-11-13 16:07:36', '2896 // 2903 // 2904 // 2905'),
(460, 'pedro', 'pedro@gmail.com 111111111111111', 'Produto: 8 (Descrição: 8, Quantidade: 4) // Produto: 9 (Descrição: 9, Quantidade: 1)', 137.00, 'pendente', '2024-11-13 16:06:20', '2024-11-13 16:06:20', '2903 // 2904');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_prod` int(11) NOT NULL,
  `nome_prod` varchar(100) NOT NULL,
  `desc_prod` text DEFAULT NULL,
  `img_prod` varchar(255) DEFAULT NULL,
  `tipo_prod` varchar(50) DEFAULT NULL,
  `preco_prod` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_prod`, `nome_prod`, `desc_prod`, `img_prod`, `tipo_prod`, `preco_prod`) VALUES
(2896, '1', '1', 'uploads/download (1).jpg', 'bubble_bubble', 1.00),
(2897, '2', '2', 'uploads/download (2).png', 'bubble_box', 2.00),
(2898, '3', '3', 'uploads/download (3).png', 'bubble_acrilico', 3.00),
(2899, '4', '4', 'uploads/download (4).png', 'box_luxo', 4.00),
(2900, '5', '5', 'uploads/download (5).png', 'natal', 5.00),
(2901, '6', '6', 'uploads/download (6).png', 'dia_das_maes', 6.00),
(2902, '7', '7', 'uploads/download.png', 'caneca', 7.00),
(2903, '8', '8', 'uploads/download (4).png', 'bubble_bubble', 8.00),
(2904, '9', '9', 'uploads/download (5).png', 'bubble_bubble', 9.00),
(2905, '10', '10', 'uploads/download (6).png', 'bubble_bubble', 10.00),
(2906, '1', '1', 'uploads/download (4).png', 'caneca', 1.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `tipo_usuario` enum('comum','administrador') DEFAULT 'comum',
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `tipo_usuario`, `nome`, `telefone`, `email`, `senha`, `token`) VALUES
(2147483649, 'administrador', 'pedro', '111111111111111', 'pedro@gmail.com', '$2y$10$JFuD3B05IhNzxi0W5AAeB.QgrfBElkwECDVEZs.Qg0hT2f/hmTpxC', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_prod`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=461;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2908;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483651;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
