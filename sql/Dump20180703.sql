-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03-Jul-2018 às 17:26
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `shortname` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cursos`
--

INSERT INTO `cursos` (`id`, `nome`, `shortname`) VALUES
(23, 'Pós-Graduação em Educação Especial - EAD', 'POS-EES-EAD'),
(24, 'Pós-Graduação em Educação Infantil e Letramento - EAD', 'POS-EIL-EAD'),
(25, 'Pós-Graduação em Finanças - EAD', 'POS-FIN-EAD'),
(26, 'Pós-Graduação em Gestão de Projetos - EAD', 'POS-GPR-EAD'),
(27, 'Pós-Graduação em Gestão Integrada, Supervisão e Administração Escolar - EAD', 'POS-GSA-EAD'),
(28, 'Pós-Graduação em Gestão de Tecnologia da Informação - EAD', 'POS-GTI-EAD'),
(29, 'Pós-Graduação em Pedagogia Empresarial - EAD', 'POS-PEM-EAD'),
(30, 'Graduação em Administração - EAD', 'ADM-EAD'),
(31, 'Graduação em Ciências Contábeis - EAD', 'CCO-EAD'),
(32, 'Graduação em Pedagogia - EAD', 'PED-EAD'),
(33, 'Graduação em Serviço Social - EAD', 'SER-EAD'),
(34, 'Graduação em Análise de Sistemas - EAD', 'ANS-EAD'),
(35, 'Graduação em Logística - EAD', 'LOG-EAD'),
(36, 'Graduação em Recursos Humanos - EAD', 'RH-EAD'),
(37, 'Graduação em Redes de Computadores - EAD', 'RDC-EAD'),
(38, 'Graduação em Engenharia de Produção - EAD', 'EPD-EAD'),
(39, 'Graduação Presencial em Administração - VR', 'VR-ADM-N'),
(40, 'Graduação Presencial em Arquitetura e Urbanismo - VR', 'VR-ARQ-N'),
(41, 'Graduação Presencial em Ciências Biológicas - VR', 'VR-CBI-N'),
(42, 'Graduação Presencial em Direito (Matutino) - VR', 'VR-DIR-M'),
(43, 'Graduação Presencial em Direito (Noturno) - VR', 'VR-DIR-N'),
(44, 'Graduação Presencial em Engenharia Civil - VR', 'VR-ECV-N'),
(45, 'Graduação Presencial em Engenharia de Produção- VR', 'VR-EPD-N'),
(46, 'Graduação Presencial em Engenharia Mecânica - VR', 'VR-EMC-N'),
(47, 'Graduação Presencial em Gestão de Recursos Humanos - VR', 'VR-GRH-N'),
(48, 'Graduação Presencial em História - VR', 'VR-HIS-N'),
(49, 'Graduação Presencial em Letras Português e Inglês - VR', 'VR-LET-N'),
(50, 'Graduação Presencial em Pedagogia - VR', 'VR-PED-N'),
(51, 'Graduação Presencial em Serviço Social - VR', 'VR-SER-N'),
(52, 'Graduação Presencial em Sistema de Informação - VR', 'VR-SIS-N'),
(53, 'Graduação Presencial em Administração - BP', 'BP-ADM-N'),
(54, 'Graduação Presencial em Biomedicina - BP', 'BP-BIO-N'),
(55, 'Graduação Presencial em Direito - BP', 'BP-DIR-N'),
(56, 'Graduação Presencial em Educação Física (Bacharelado) - BP', 'BP-EDU-B-N'),
(57, 'Graduação Presencial em Educação Física (Licenciatura) - BP', 'BP-EDU-L-N'),
(58, 'Graduação Presencial em Engenharia Civil - BP', 'BP-ECV-N'),
(59, 'Graduação Presencial em Engenharia Mecânica - BP', 'BP-EMC-N'),
(60, 'Graduação Presencial em Engenharia de Produção - BP', 'BP-EPD-N'),
(61, 'Graduação Presencial em Nutrição - BP', 'BP-NUT-N'),
(62, 'Graduação Presencial em Engenharia Mecânica - NI', 'NI-EMC-N'),
(63, 'Graduação Presencial em Engenharia Civil - NI', 'NI-ECV-N'),
(64, 'Graduação Presencial em Engenharia de Produção', 'NI-EPD-N');

-- --------------------------------------------------------

--
-- Estrutura da tabela `enrolments`
--

CREATE TABLE `enrolments` (
  `id` int(11) NOT NULL,
  `shortnamecourse` varchar(255) DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `shortnamerole` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT 'student',
  `matriz` int(11) NOT NULL,
  `timecreated` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `error` tinyint(1) NOT NULL,
  `timecreated` bigint(20) NOT NULL,
  `more` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `shortnamecourse` varchar(45) DEFAULT NULL,
  `periodo` int(2) DEFAULT NULL,
  `matriz` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `matrizes`
--

CREATE TABLE `matrizes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `curso` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `modalidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modalidades_de_cursos`
--

CREATE TABLE `modalidades_de_cursos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `modalidades_de_cursos`
--

INSERT INTO `modalidades_de_cursos` (`id`, `nome`) VALUES
(1, 'EAD'),
(2, 'PRESENCIAL');

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `matriz` int(11) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `shortnamecourse` varchar(255) NOT NULL,
  `periodo` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shortname` (`shortname`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `enrolments`
--
ALTER TABLE `enrolments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matriz` (`matriz`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrizes`
--
ALTER TABLE `matrizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `curso` (`curso`),
  ADD KEY `modalidade` (`modalidade`);

--
-- Indexes for table `modalidades_de_cursos`
--
ALTER TABLE `modalidades_de_cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modulos_ibfk_1` (`matriz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `enrolments`
--
ALTER TABLE `enrolments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matrizes`
--
ALTER TABLE `matrizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `modalidades_de_cursos`
--
ALTER TABLE `modalidades_de_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `matrizes`
--
ALTER TABLE `matrizes`
  ADD CONSTRAINT `matrizes_ibfk_1` FOREIGN KEY (`curso`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `matrizes_ibfk_2` FOREIGN KEY (`modalidade`) REFERENCES `modalidades_de_cursos` (`id`);

--
-- Limitadores para a tabela `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`matriz`) REFERENCES `matrizes` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
