-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.196
-- Generation Time: 30-Ago-2024 às 09:15
-- Versão do servidor: 5.7.32-35-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdsis_eleicao`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `gestao` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `votante` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `unidade_votante` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `idcandidatos` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `senha` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `anoeleicao` varchar(4) COLLATE latin1_general_ci NOT NULL,
  `dthora_voto` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `ip_servidor` varchar(120) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `auditoria`
--

INSERT INTO `auditoria` (`id`, `gestao`, `votante`, `unidade_votante`, `idcandidatos`, `senha`, `anoeleicao`, `dthora_voto`, `ip_servidor`) VALUES
(43, '2020/2022', '31', 'JAC - JacarandÃ¡s / 029', 'a:4:{i:0;a:2:{s:5:\"cargo\";s:10:\"Tesoureiro\";s:11:\"idcandidato\";s:4:\"Nulo\";}i:1;a:2:{s:5:\"cargo\";s:8:\"SÃ­ndico\";s:11:\"idcandidato\";s:2:\"50\";}i:2;a:2:{s:5:\"cargo\";s:15:\"Conselho Fiscal\";s:11:\"idcandidato\";s:2:\"51\";}i:3;a:2:{s:5:\"cargo\";s:23:\"Conselho Administrativo\";s:11:\"idcandidato\";s:4:\"Nulo\";}}', 'vJiod33O8k', '2020', '14-01-2020 21:31:56', '179.214.10.223 / b3d60adf.virtua.com.br');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastropessoais`
--

CREATE TABLE `cadastropessoais` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `endereco` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `unidade` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `telefone_fixo` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `cpf` varchar(11) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `cadastropessoais`
--

INSERT INTO `cadastropessoais` (`id`, `nome`, `endereco`, `unidade`, `email`, `telefone_fixo`, `cpf`) VALUES
(31, 'Rogerio Wilson Lelis Caixeta', 'Rua 8 Chacara 297 casa 21', 'JAC - JacarandÃ¡s / 029', 'rogerio@1portodos.com.br', '61 995550726', '48832138115'),
(35, 'Claudio Tavares Carvalho', 'Gardenia 43', 'GA - GardÃªnia / 043', 'carvalho.ideal@gmail.com', '64 34530644', '33281726291'),
(40, 'Lorrane Nascimento', 'Rua do XarÃ©u qd 13 lt 36 casa 1 Jardim AtlÃ¢ntico  GoiÃ¢nia ', 'BO - Bougainville / 011', 'Lorrane.go@gmail.com', '6291608990', '80750818115');

-- --------------------------------------------------------

--
-- Estrutura da tabela `candidatos`
--

CREATE TABLE `candidatos` (
  `id` int(11) NOT NULL,
  `id_candidato` int(11) NOT NULL,
  `nome` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `cargo` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `gestao` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `foto` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `qtdevotos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `candidatos`
--

INSERT INTO `candidatos` (`id`, `id_candidato`, `nome`, `cargo`, `gestao`, `foto`, `qtdevotos`) VALUES
(50, 31, 'Rogerio Wilson Lelis Caixeta', 'SÃ­ndico', '2020/2022', '48832138115.jpg', 8),
(51, 35, 'Claudio Tavares Carvalho', 'Conselho Fiscal', '2020/2022', '', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `cargo` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `descricao` varchar(805) COLLATE latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `cargos`
--

INSERT INTO `cargos` (`id`, `cargo`, `descricao`, `seq`) VALUES
(6, 'SÃ­ndico', 'Administrar o CondomÃ­nio dentro das exigÃªncias legais', 1),
(7, 'Conselho Fiscal', 'Fiscalizar, examinar, assessorar atividades e emitir parecer', 3),
(8, 'Conselho Administrativo', 'Assessorar atividades de cada etapa, opinar e aprovar despesas', 4),
(10, 'Tesoureiro', 'Apoiar as finanÃ§as do condomÃ­nio', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `controleacesso`
--

CREATE TABLE `controleacesso` (
  `id` int(11) NOT NULL,
  `datalogin` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `horalogin` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `idusuario` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `nomelogin` varchar(80) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `controleacesso`
--

INSERT INTO `controleacesso` (`id`, `datalogin`, `horalogin`, `idusuario`, `nomelogin`) VALUES
(1, '25/01/2020', '19:22:27', '31', 'Rogerio Wilson Lelis Caixeta');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipodemanda`
--

CREATE TABLE `tipodemanda` (
  `id` int(11) NOT NULL,
  `tipo` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `dtassembleia` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `descricao` varchar(805) COLLATE latin1_general_ci NOT NULL,
  `dtiniciovigencia` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `dtfimvigencia` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `gestao` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `situacao` varchar(10) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `tipodemanda`
--

INSERT INTO `tipodemanda` (`id`, `tipo`, `dtassembleia`, `descricao`, `dtiniciovigencia`, `dtfimvigencia`, `gestao`, `situacao`) VALUES
(25, 'EleiÃ§Ãµes Condominiais', '14/03/2020', 'Teste village', '10/02/2020 17:26:00', '20/03/2020 17:30:00', '2020/2022', 'ativa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `senha` varchar(80) NOT NULL,
  `unidade` varchar(30) NOT NULL,
  `adimplente` varchar(3) NOT NULL,
  `adm` varchar(3) NOT NULL,
  `cpf` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `unidade`, `adimplente`, `adm`, `cpf`) VALUES
(36, 'rogerio@1portodos.com.br', 'caleul99', 'JAC - JacarandÃ¡s / 029', 'sim', 'sim', '48832138115'),
(39, 'carvalho.ideal@gmail.com', 'carvalho.ideal@gmail.com', 'GA - GardÃªnia / 043', 'sim', 'sim', '33281726291'),
(42, 'Lorrane.go@gmail.com', 'Lorrane.go@gmail.com', 'BO - Bougainville / 011', 'sim', 'sim', '80750818115');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cadastropessoais`
--
ALTER TABLE `cadastropessoais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidatos`
--
ALTER TABLE `candidatos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `controleacesso`
--
ALTER TABLE `controleacesso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipodemanda`
--
ALTER TABLE `tipodemanda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `cadastropessoais`
--
ALTER TABLE `cadastropessoais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `candidatos`
--
ALTER TABLE `candidatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `controleacesso`
--
ALTER TABLE `controleacesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tipodemanda`
--
ALTER TABLE `tipodemanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
