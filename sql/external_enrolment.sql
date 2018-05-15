-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 15-Maio-2018 às 21:15
-- Versão do servidor: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `external_enrolment`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `shortname` varchar(100) NOT NULL,
  `idnumber` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `courses`
--

INSERT INTO `courses` (`id`, `fullname`, `shortname`, `idnumber`) VALUES
(1, 'Software Engeneering', 'SE', 'SI1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `enrolments`
--

CREATE TABLE `enrolments` (
  `id` int(11) NOT NULL,
  `shortnamecourse` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `shortnamerole` varchar(100) NOT NULL DEFAULT 'student'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `enrol_matriz`
--

CREATE TABLE `enrol_matriz` (
  `id` int(11) NOT NULL,
  `matriz` int(11) NOT NULL,
  `username` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `matrizes`
--

CREATE TABLE `matrizes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `matrizes`
--

INSERT INTO `matrizes` (`id`, `nome`) VALUES
(72, 'Engenharia Civil (2018)'),
(71, 'Sistemas de Informação (2018)'),
(75, 'Sistemas de Informação (2019)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `matriz` int(11) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `shortnamecourse` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `modulos`
--

INSERT INTO `modulos` (`id`, `matriz`, `sortorder`, `shortnamecourse`) VALUES
(45, 71, 0, 'MD'),
(46, 71, 1, 'PI'),
(47, 71, 2, 'ADA'),
(48, 71, 3, 'BDD'),
(49, 71, 4, 'IA'),
(50, 72, 0, 'MAT'),
(51, 72, 1, 'FIS'),
(52, 75, 0, 'ADA'),
(53, 75, 1, 'BDD'),
(54, 75, 2, 'MD'),
(55, 75, 3, 'PI'),
(56, 75, 4, 'IA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrolments`
--
ALTER TABLE `enrolments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrol_matriz`
--
ALTER TABLE `enrol_matriz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrizes`
--
ALTER TABLE `matrizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matriz` (`matriz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `enrolments`
--
ALTER TABLE `enrolments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enrol_matriz`
--
ALTER TABLE `enrol_matriz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matrizes`
--
ALTER TABLE `matrizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`matriz`) REFERENCES `matrizes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
