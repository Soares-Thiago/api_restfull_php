-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30-Out-2020 às 14:14
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `stautrh_apirest`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `drink`
--

CREATE TABLE `drink` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ml_water` decimal(15,2) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `drink`
--

INSERT INTO `drink` (`id`, `user_id`, `ml_water`, `date`) VALUES
(3, 7, '11.11', '2020-10-30 08:56:11'),
(9, 12, '11.00', '2020-10-30 09:53:54'),
(10, 12, '11.00', '2020-10-30 09:53:56'),
(11, 15, '12.00', '2020-10-30 10:10:34'),
(12, 15, '12.00', '2020-10-30 10:12:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`) VALUES
(7, 'thiago.devps@outlook.com', '123', '1234'),
(12, 'thiago2.devps@outlook.com', '123', '1234'),
(15, 'thiago5.devps@outlook.com', 'thiago pontes', '12346');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `drink`
--
ALTER TABLE `drink`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `drink`
--
ALTER TABLE `drink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
