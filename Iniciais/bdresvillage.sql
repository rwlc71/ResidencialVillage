-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 179.188.16.207
-- Generation Time: 30-Ago-2024 às 09:18
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
-- Database: `bdresvillage`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ANIMAIS`
--

CREATE TABLE `ANIMAIS` (
  `COD_AN` int(11) NOT NULL,
  `NOME` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `ESPECIE` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `DATA_NASC` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `COD_PROP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro`
--

CREATE TABLE `cadastro` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `endereco` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `cidade` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `estado` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `cep` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `etapa_village` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `unidade_etapa_village` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `telefone` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `obsgeral` text COLLATE latin1_general_ci NOT NULL,
  `valor_contribuicao` decimal(9,2) NOT NULL,
  `tipo` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `noticia` varchar(3) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `cadastro`
--

INSERT INTO `cadastro` (`id`, `nome`, `endereco`, `cidade`, `estado`, `cep`, `etapa_village`, `unidade_etapa_village`, `email`, `telefone`, `obsgeral`, `valor_contribuicao`, `tipo`, `noticia`) VALUES
(61, 'Rogerio Wilson Lelis Caixeta', 'SHVP - Rua 8 chacara 207 casa 21 - Vicente Pires', 'Taguatinga', 'Distrito Federal (DF)', '72.006-870', 'JAC - JacarandÃ¡s', '29', 'rogerio@1portodos.com.br', '(61)99555-0726', 'Criador do site', 0.00, 'sim', 'sim'),
(63, 'JosÃ© Luiz de Souza Andrade', 'Rua do Ãlamo nÂ° 1180 Apt 204-E Cond.Tropical PrivÃª  Goiania 2', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74.663-040', 'OR - OrquÃ­deas', '75', 'lutavares2809@hotmail.com', '(62) 996169174', 'Esposa: Lourdes Tavares \r\n(62) 32056206 residencial\r\n(62)9.81189374 Tim// (62)9.99784025 Vivo\r\n(62)9.85516158 Oi.whats\r\n(64)9.92070314 Claro', 0.00, '', ''),
(65, 'aloisio ferreira dos santos ', 'go 213 km 2,15 cp 221  or-79', 'caldas novas', 'GoiÃ¡s (GO)', '75690000', 'OR - OrquÃ­deas', '79', 'aloisioferreiradossantos@gmail.com', '64-999828303', '', 0.00, '', ''),
(66, 'Claudio Tavares carvalho', 'Village thermas de caldas', 'Caldas novas', 'GoiÃ¡s (GO)', '75690000', 'GA - GardÃªnia', '43', 'carvalho.ideal@gmail.com', '61-99594600', 'Moro no prÃ³prio Village.', 0.00, '', ''),
(67, 'Auridea Castro Falcao Fernandes', 'Rua Major Victor,272 Loja 13 Galeria Mara Morena Centro', 'Caldas Novas ', 'GoiÃ¡s (GO)', '', 'PIT - Pitangueiras', 'O4', 'aurideafernandes@gmail.com', '64 98144-9031', '', 0.00, '', ''),
(68, 'Zilda Ana de Morais ', 'CondomÃƒÆ’Ã‚Â­nio Residencial Village Thermas de Caldas, OrquÃƒÆ’Ã‚Â­deas 99. Ro', 'Caldas Novas ', 'GoiÃ¡s (GO)', '75690000', 'OR - OrquÃ­deas', '99', 'Misszilda@gmail.com', '64 99334 0660', '', 100.00, '', ''),
(69, 'Gislene Moraes ', 'Rua S-05 esq.c/T64 n 581 apto. 702  EdifÃƒÆ’Ã‚Â­cio Ville de Montagne-Setor Bela', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74823-460 ', 'OR - OrquÃ­deas', '05 (cinco)', 'gislene2801@gmail.com ', '62 981170907 ', '', 0.00, '', ''),
(70, 'JOSÃƒÆ’Ã¢â‚¬Â° ALVES PACHECO NETO ', 'Rod. GO 213 Res. Village - Bairro Saint German ', 'Caldas Novas ', 'GoiÃ¡s (GO)', '75690000', 'GA - GardÃªnia', 'CASA 13', 'pachecao1950@hotmail.com Rod.', '64-9-81434141', '', 0.00, '', ''),
(71, 'JOSÃƒÆ’Ã¢â‚¬Â° ALVES PACHECO NETO ', 'Rod. GO 213 Res. Village - Bairro Saint German ', 'Caldas Novas ', 'GoiÃ¡s (GO)', '75690000', 'GA - GardÃªnia', 'Casa 18', 'pachecao1950@hotmail.com ', '64-9-81434141', '', 0.00, '', ''),
(72, 'Edesio Francisco de oliveira', 'Rua 14  n 20   vila isaura', 'Goiania', 'GoiÃ¡s (GO)', '74553210', 'PIT - Pitangueiras', '16', 'eduardojamiro@yahoo.com.br', '62981446448', '', 100.00, '', ''),
(73, 'Ricardo Alves Cardoso', 'Rua das cravinas Q.91 L.16 n.393', 'GoiÃƒÆ’Ã‚Â¢nia ', 'GoiÃ¡s (GO)', '74375290', 'BO - Bougainville', '47', 'r.a.cardoso@hotmail.com', '62-984183065', '', 100.00, '', ''),
(74, 'Keliane Santos Arantes', 'SQS 310 bloco H APT 605', 'BrasÃƒÆ’Ã‚Â­lia DF', 'Distrito Federal (DF)', '70363080', 'GA - GardÃªnia', '40', 'thedemedeiros@hotmail.com', '61 981093641', '', 0.00, '', ''),
(75, 'Tania Marcia Viegas Marques E ', 'Rod. Go 213 km 2.5', 'Caldas novas ', 'GoiÃ¡s (GO)', '75690000', 'BO - Bougainville', '1', 'hmarques2502@yahoo. com.br', '', '', 0.00, '', ''),
(76, 'JosÃƒÆ’Ã‚Â© Miguel Sanches Gravina', 'Go 213 cond. Village pitangueiras 58', 'Caldas novas', 'GoiÃ¡s (GO)', '75690000', 'PIT - Pitangueiras', '58', 'jgravina25@gmail.com', '62999329696', '', 100.00, '', ''),
(77, 'Vanilda Alvim Alcantara', 'R C137 c/ C145 Qd 318 Lt 01 sob1 Jd America', 'Goiania', 'GoiÃ¡s (GO)', '74275060', 'GA - GardÃªnia', '39', 'van101960@outlook.com.br', '62 98288-8686', 'A casa esta em nome do nosso filho, mas temos amplos poderes. ', 0.00, '', ''),
(78, 'Franciele Carvalho de Oliveira Roma', 'SQNW 109 Bloco D apartamento 502 EdifÃƒÆ’Ã‚Â­cio Sancy', 'BrasÃƒÆ’Ã‚Â­lia ', 'Distrito Federal (DF)', '70686420', 'BO - Bougainville', '76', 'francieleromadf@hotmail.com', '61 99281.5117', '', 0.00, '', ''),
(79, 'Clarice Alves Pereira', 'rua 20 nr. 974 - Ap.402 - Ed. Paraiso - Centro', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74020-170', 'OR - OrquÃ­deas', '40', 'claric@globo.com', '62981417885', '', 0.00, '', ''),
(80, 'Vera LÃƒÆ’Ã‚Âºcia Muniz Silva ', 'Rod. Go. 213 km 2.5', 'Caldas Novas', 'GoiÃ¡s (GO)', '75690000', 'GA - GardÃªnia', '35 e 50', 'vera muniz @gmail. com', '', '', 0.00, '', ''),
(81, 'PAULO SÃ‰RGIO DE MELLO VAZ', 'QNE 10 CASA 09', 'Brasilia- bairro Taguatinga', 'Distrito Federal (DF)', '72125100', 'AZ - AzalÃ©ia', '40', 'psmv.fla@gmail.com', '61 996477730', '', 0.00, '', ''),
(82, 'Jose AntÃƒÆ’Ã‚Â´nio  Fernandes de Oliveira ', 'Residencial  Village Thermas das Caldas', 'Caldas Novas', 'GoiÃ¡s (GO)', '75690000', 'AZ - AzalÃ©ia', '91', 'stjafo@gmail.com', '64992215100', '', 100.00, 'sim', ''),
(83, 'ELEANDRA CRISTINA DE OLIVEIRA CAMPOS', 'Rodovia GO-213 km 2,5 Etapa GardÃƒÆ’Ã‚Âªnia, Casa 45 Residencial Village Thermas', 'CALDAS NOVAS ', 'GoiÃ¡s (GO)', '75690-000', 'GA - GardÃªnia', '45', 'eleandracristina@yahoo.com.br', '64993112188', '', 100.00, '', ''),
(84, 'Cezar Esteves do Nascimento', 'Rua T-48 n. 395 Apto. 502 EdifÃƒÆ’Ã‚Â­cio Denver II - CEP 74210-190', 'Goiania', 'GoiÃ¡s (GO)', '', 'JAC - JacarandÃ¡s', '48', 'cezarestevesadv@gmail.com', '62/981597195 vi', 'Trabalho:\r\nEsteves Advogados S.S.\r\n(62) 3995.2212', 100.00, '', ''),
(85, 'NÃƒÆ’Ã‚Â¡dia Perez Canhamero', 'GO 213 CondomÃƒÆ’Ã‚Â­nio Residencial Theras Village', 'Caldas Novas', 'GoiÃ¡s (GO)', '75690000', 'BO - Bougainville', '22', 'nadiamaduleta@yahoo.com.br', '(64)992447878', '', 100.00, '', ''),
(86, 'SUELI PEREIRA DA PAULA', 'QSB 13 CASA 03', 'BRASÃLIA ', 'Distrito Federal (DF)', '72015630', 'GA - GardÃªnia', '75', 'spaula7@gmail.com', '6199311301', '', 0.00, '', ''),
(87, 'JosÃƒÆ’Ã‚Â© Maria bianchi', 'Quadra 203 lote 3 bloco A apto 1203 portal das Andorinhas ', 'ÃƒÆ’Ã‚Âguas Claras', 'Distrito Federal (DF)', '71939-360', 'AZ - AzalÃ©ia', '33', 'Fatimabianchi@gmail.com', '61 982605410', '', 0.00, '', ''),
(88, 'Zenilda Francisco dos Santos', 'Rua B6A  Lt. 15/18 Casa 09, Resid.PrivÃª das Laranjeiras - Bairro: Pq. das', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74855-110', 'GA - GardÃªnia', '23', 'zenilda@saneago.com.br', '', '', 0.00, '', ''),
(89, 'Edmilson Alves da Cunha', 'Quadra 06 CL 09 Apto 102', 'Sobradinho ', 'Distrito Federal (DF)', '', 'OR - OrquÃ­deas', '97', 'taniaferreira.bsb@gmail.com', '61 999640491', 'ProprietÃƒÆ’Ã‚Â¡rio ÃƒÆ’Ã‚Â© Sr Edmilson, mas quem faz os contatos via watasap e e-mail   ÃƒÆ’Ã‚Â© Tania Ferreira (esposa)             61 999796064', 100.00, '', ''),
(90, 'Carmen Lucia dos Santos Gomes', 'Rua K n. 112 apto 606', 'Goiania', 'GoiÃ¡s (GO)', '74120_040', 'PIT - Pitangueiras', '51', 'gomes.carmen@hotmail.com', '62993078603', '', 0.00, '', ''),
(91, 'Maria Cleusa Teixeira Rezende', 'SHVP Rua 3 ChÃƒÆ’Ã‚Â¡cara 90 Lote 7', 'Vicente Pires', 'Distrito Federal (DF)', '72005805', 'OR - OrquÃ­deas', '21', 'mctrezende@gmail.com', '61999973340', '', 0.00, '', ''),
(92, 'Walquiria Peixoto Alves', 'Rua 15 de Novembro Qd 32 A Lt 13 n. 365 St. Centro Oeste ', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74550-280', 'OR - OrquÃ­deas', '8', 'alveswp@hotmail.com', '62 9 9979-6268', '', 0.00, '', ''),
(93, 'Maria do RosÃƒÆ’Ã‚Â¡rio ConstÃƒÆ’Ã‚Â¢ncio Silva', 'QE 24 conjunto E casa 23', 'brasilia', 'Distrito Federal (DF)', '71060050', 'PIT - Pitangueiras', '24', 'allinecramos@hotmail.com', '6135689494', '', 100.00, '', ''),
(94, 'Nilo dos Santos Martins', 'Rua 26 norte Lote 01 Apto 602 Ed. Saphira', 'ÃƒÆ’Ã‚Âguas Claras ', 'Distrito Federal (DF)', '71917360', 'OR - OrquÃ­deas', '27', 'Nilodaglobo@globo.com', '61 991225322 ', '61 35521031 \r\n61 995578020\r\nHelena de Castro Martins (esposa) ', 0.00, '', ''),
(95, 'Ana Carolina de Almeida Mandrami ', 'Rua Antonio Marciano de Avila 1560', 'UberlÃƒÆ’Ã‚Â¢ndia', 'Minas Gerais (MG)', '38408244', 'AZ - AzalÃ©ia', '34', 'acmandrami@gmail.com', '(64) 98457-0551', 'Eduardo Batista Pacheco e Ana Carolina De Almeida Mandrami ( proprietÃƒÆ’Ã‚Â¡rios ) \r\nSra Marina e Sr Jair ( inquilinos )', 0.00, '', ''),
(96, 'ANTONIO PEREIRA NETO', 'RUA PRIMAVERA QD-26 LT-03 - CONJUNTO RESIDENCIAL PALMARES', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74.775-026', 'OR - OrquÃ­deas', '7', 'toninhop@terra.com.br', '62-9-9975-2588', 'Toninho.', 0.00, '', ''),
(97, 'JoÃƒÆ’Ã‚Â£o Batista Pereira de Souza', 'quadra 06 conj. k Casa 02 setor sul', 'gama df', 'Distrito Federal (DF)', '72415311', 'BO - Bougainville', '88', 'nubia3010@yahoo.om.br', '061 999840211', '', 0.00, '', ''),
(98, 'Neuza vieira da costa', 'Rua MaceiÃƒÆ’Ã‚Â³,  qd 134, lt.1/7 Park Privilege apto 2801, torre Javari Pq. Am', 'GoiÃƒÆ’Ã‚Â¢nia ', 'GoiÃ¡s (GO)', '', 'AZ - AzalÃ©ia', '61', 'neuzavc@hotmail.com', '62 39542940 ', '', 0.00, '', ''),
(99, 'Luz Marina Alves Borges', 'QSA 14 CASA, 25', 'Taguatinga', 'Distrito Federal (DF)', '72.015-140', 'PIT - Pitangueiras', '1', 'lmaborges@yahoo.com.br', '061 984875996', '', 100.00, '', ''),
(100, 'JosuÃƒÆ’Ã‚Â© Carlos dos santos', 'Avenida sucuri QD 139, LT 49', 'GoiÃƒÆ’Ã‚Â¢nia', 'GoiÃ¡s (GO)', '', 'AZ - AzalÃ©ia', '85', 'jsantoscta@yahoo.com.br', '62 981568776', '', 100.00, '', ''),
(101, 'Marcilio', 'Residencial Village Thermas de Caldas - Etapa AzalÃƒÆ’Ã‚Â©ia, casa 42 ', 'Caldas Novas', 'GoiÃ¡s (GO)', '75.690-000', 'AZ - AzalÃ©ia', '42', 'Mgomes2@brturbo.com.br', '062 984041151', 'Meu endereÃƒÆ’Ã‚Â§o em GoiÃƒÆ’Ã‚Â¢nia: rua T-29 n.290 res plaza lourenzzo apto 1701 setor bueno GoiÃƒÆ’Ã‚Â¢nia-GoiÃƒÆ’Ã‚Â¡s\r\nCEP: 74.210-050', 0.00, '', ''),
(102, 'Katia Mara Sales Moreira', '', 'Rua professora gelmires reis qd02 lt19', 'GoiÃ¡s (GO)', '74593670', 'AZ - AzalÃ©ia', '29', 'Ksalesmoreira@hotmail.com', '62984780326', '', 0.00, '', ''),
(103, 'Hebe Cunha Neves de Oliveira e Paula Cristina Cunha de Oliveira ', 'Rod. GO 213, Residencial Village, Etapa GardÃªnia ,  acesso G 5, casa 48', 'Caldas Novas ', 'GoiÃ¡s (GO)', '75690-000', 'GA - GardÃªnia', '48', 'pckhanna@hotmail.com ', '064 99277- 4522', 'Paula, cel: 061 98448-3315.\r\nAmbas somos donas da casa.', 0.00, '', ''),
(104, 'ARNALDO NADIM MIZIARA ', 'QUADRA QSC 4, CASA 18', 'BRASILIA', 'Distrito Federal (DF)', '72016040', 'PIT - Pitangueiras', '78', 'arnaldomiziara@gmail.com', '(61) 3351-1706', '', 0.00, '', ''),
(105, 'NIVIO VALTER DIAS', 'Rua Jacirendi 91, 121 B', 'sao paulo', 'SÃƒÆ’Ã‚Â£o Paulo (SP)', '3080000', 'PIT - Pitangueiras', '19', 'nivio52@gmail.com', '11984479486', '', 0.00, '', ''),
(106, 'JosÃƒÆ’Ã‚Â© Miguel Sanches Gravina', 'Go 213 cond. Village pitangueiras 58', 'Caldas novas', 'GoiÃ¡s (GO)', '', 'PIT - Pitangueiras', '58', 'jgravina25@gmail.com', '62999329696', '', 0.00, '', ''),
(107, 'Augusto Pereira da Silva ', 'QNL 17 Conjunto ÃƒÆ’Ã‚Â Casa 18', 'BrasÃƒÆ’Ã‚Â­lia ', 'Distrito Federal (DF)', '72151-701', 'PIT - Pitangueiras', '90', 'ana.poeck@gmail.com ', '(61) 98424-7955', '', 0.00, '', ''),
(108, 'Lu Vieira', 'Av.miguel JoÃƒÆ’Ã‚Â£o ', 'AnÃƒÆ’Ã‚Â¡polis', 'GoiÃ¡s (GO)', '', 'AZ - AzalÃ©ia', '83', 'lucilenelvieira@hotmail.com', '62991313159', '', 0.00, '', ''),
(109, 'JoÃ£o CesÃ¡rio de Araujo', 'Rua BabaÃ§u 5 Ed Premiere Apto 201 Ãguas  Claras ', 'BrasÃ­lia', 'Distrito Federal (DF)', '71 928000', 'OR - OrquÃ­deas', '59', 'cgma@iegui.com.br', '61 3562 7450', '', 0.00, '', ''),
(110, 'Walquiria Peixoto Alves', 'Rua 15 de novembro Qd. 32A Lt.13 N.365 Setor Centro Oeste', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74550280', 'OR - OrquÃ­deas', '8', 'alveswp@hotmail.com', '62999796268', '', 0.00, '', ''),
(111, 'Maria  Goreth Rezende Ribeiro ', 'QNF 20 CASA 19', 'Taguatinga BrasÃƒÆ’Ã‚Â­lia ', 'Distrito Federal (DF)', '72125700', 'BO - Bougainville', '25', 'Goreth.rezende@gmail.com. ', '/61 984887785', '', 0.00, '', ''),
(112, 'SÃƒÆ’Ã‚Â­lvia Beatriz', 'Rod.GO 213 km 2.5 Res. Village T. Caldas Etapa  Gardenia casa 13  Bairro Saint G', 'Caldas Novas', 'GoiÃ¡s (GO)', '75690000', 'GA - GardÃªnia', '13', 'silviabcvp@gmail.com', '62 999297064', '', 0.00, '', ''),
(113, '', '', '', '', '', '', '', 'abmilhomem@gmail.com', '', '', 0.00, '', ''),
(114, '', '', '', '', '', '', '', 'acarloslneves@gmail.com', '', '', 0.00, '', ''),
(115, '', '', '', '', '', '', '', 'acblago@gmail.com', '', '', 0.00, '', ''),
(116, '', '', '', '', '', '', '', 'adaoazevedo@globo.com', '', '', 0.00, '', ''),
(117, '', '', '', '', '', '', '', 'adhara@adharaluz.com.br', '', '', 0.00, '', ''),
(118, '', '', '', '', '', '', '', 'adriana.hospitalar@hotmail.com', '', '', 0.00, '', ''),
(119, '', '', '', '', '', '', '', 'adriano.meneses@hotmail.com.br', '', '', 0.00, '', ''),
(120, '', '', '', '', '', '', '', 'adrianotyrka@gmail.com', '', '', 0.00, '', ''),
(121, '', '', '', '', '', '', '', 'agildoapeixoto@yahoo.com.br', '', '', 0.00, '', ''),
(122, '', '', '', '', '', '', '', 'agnaldo.bezerra@conab.gov.br', '', '', 0.00, '', ''),
(123, '', '', '', '', '', '', '', 'agsilva3@gmail.com', '', '', 0.00, '', ''),
(124, '', '', '', '', '', '', '', 'airamcb2014@gmail.com', '', '', 0.00, '', ''),
(125, '', '', '', '', '', '', '', 'alaylins@terra.com.br', '', '', 0.00, '', ''),
(126, '', '', '', '', '', '', '', 'aline-g@yahoo.com.br', '', '', 0.00, '', ''),
(127, '', '', '', '', '', '', '', 'aloisio-aguiar@uol.com.br', '', '', 0.00, '', ''),
(128, '', '', '', '', '', '', '', 'amandaabatistadeoliveira@gmail.com', '', '', 0.00, '', ''),
(129, '', '', '', '', '', '', '', 'ana.alcantara@marisa.com.br', '', '', 0.00, '', ''),
(130, '', '', '', '', '', '', '', 'aninhatchipsh@hotmail.com', '', '', 0.00, '', ''),
(131, '', '', '', '', '', '', '', 'aparecidoseguros@gmail.com', '', '', 0.00, '', ''),
(132, '', '', '', '', '', '', '', 'apcap.df@gmail.com', '', '', 0.00, '', ''),
(133, '', '', '', '', '', '', '', 'AQUARIUS@aquariusdespachante.com.br', '', '', 0.00, '', ''),
(134, '', '', '', '', '', '', '', 'ar.donatoni@gmail.com', '', '', 0.00, '', ''),
(135, '', '', '', '', '', '', '', 'arantestatiana@yahoo.com.br', '', '', 0.00, '', ''),
(136, '', '', '', '', '', '', '', 'arianne@iegui.com.br', '', '', 0.00, '', ''),
(137, '', '', '', '', '', '', '', 'arionemarques@hotmail.com', '', '', 0.00, '', ''),
(138, '', '', '', '', '', '', '', 'arlindo.chaves@terra.com.br', '', '', 0.00, '', ''),
(139, '', '', '', '', '', '', '', 'axos@uol.com.br', '', '', 0.00, '', ''),
(140, '', '', '', '', '', '', '', 'b.ilhadosaber@gmail.com', '', '', 0.00, '', ''),
(141, '', '', '', '', '', '', '', 'barbozarep@bol.com.br', '', '', 0.00, '', ''),
(142, '', '', '', '', '', '', '', 'barros@quimifol.com.br', '', '', 0.00, '', ''),
(143, '', '', '', '', '', '', '', 'belchiorc@gmail.com', '', '', 0.00, '', ''),
(144, '', '', '', '', '', '', '', 'bernardogalli@hotmail.com', '', '', 0.00, '', ''),
(145, '', '', '', '', '', '', '', 'bernardomonticelligm@outlook.com', '', '', 0.00, '', ''),
(146, '', '', '', '', '', '', '', 'betimcs@bol.com.br', '', '', 0.00, '', ''),
(147, '', '', '', '', '', '', '', 'bizzottoangela@yahoo.com.br', '', '', 0.00, '', ''),
(148, '', '', '', '', '', '', '', 'boletoacontece@gmail.com', '', '', 0.00, '', ''),
(149, '', '', '', '', '', '', '', 'bradisba@yahoo.com.br', '', '', 0.00, '', ''),
(150, '', '', '', '', '', '', '', 'brenno.machado@gmail.com', '', '', 0.00, '', ''),
(151, '', '', '', '', '', '', '', 'caldasnovas@yogoothies.com.br', '', '', 0.00, '', ''),
(152, '', '', '', '', '', '', '', 'camilaarouca@hotmail.com', '', '', 0.00, '', ''),
(153, '', '', '', '', '', '', '', 'camillavieira81@gmail.com', '', '', 0.00, '', ''),
(154, '', '', '', '', '', '', '', 'capmartinsgontijo@gmail.com', '', '', 0.00, '', ''),
(155, '', '', '', '', '', '', '', 'carita.marques@hotmail.com', '', '', 0.00, '', ''),
(156, '', '', '', '', '', '', '', 'carlosapmorgado@hotmail.com', '', '', 0.00, '', ''),
(157, '', '', '', '', '', '', '', 'carlosdaciano@hotmail.com', '', '', 0.00, '', ''),
(158, '', '', '', '', '', '', '', 'carmelita2105@gmail.com', '', '', 0.00, '', ''),
(159, '', '', '', '', '', '', '', 'carolinatroyan@gmail.com', '', '', 0.00, '', ''),
(160, '', '', '', '', '', '', '', 'celio@viacontabil.net.br', '', '', 0.00, '', ''),
(161, '', '', '', '', '', '', '', 'celio-bezerra@hotmail.com', '', '', 0.00, '', ''),
(162, '', '', '', '', '', '', '', 'cerejafaria@gmail.com', '', '', 0.00, '', ''),
(163, '', '', '', '', '', '', '', 'christianmarra2005@yahoo.com.br', '', '', 0.00, '', ''),
(164, '', '', '', '', '', '', '', 'claudiagnakato@hotmail.com', '', '', 0.00, '', ''),
(165, '', '', '', '', '', '', '', 'claudiamarizegsilva@gmail.com', '', '', 0.00, '', ''),
(166, '', '', '', '', '', '', '', 'claudinhalins0307@hotmail.com', '', '', 0.00, '', ''),
(167, '', '', '', '', '', '', '', 'claudinhu_uu@hotmail.com', '', '', 0.00, '', ''),
(168, '', '', '', '', '', '', '', 'claudio.mendesaidar@gmail.com', '', '', 0.00, '', ''),
(169, '', '', '', '', '', '', '', 'cleberj@libertyseguros.com.br', '', '', 0.00, '', ''),
(170, '', '', '', '', '', '', '', 'cledson.marques@hotmail.com', '', '', 0.00, '', ''),
(171, '', '', '', '', '', '', '', 'cleoms@hotmail.com', '', '', 0.00, '', ''),
(172, '', '', '', '', '', '', '', 'clodoaldo_silva@uol.com.br', '', '', 0.00, '', ''),
(173, '', '', '', '', '', '', '', 'clorisilvaadv@gmail.com', '', '', 0.00, '', ''),
(174, '', '', '', '', '', '', '', 'consultedf@yahoo.com.br', '', '', 0.00, '', ''),
(175, '', '', '', '', '', '', '', 'contabil.assistente3@cifarma.com.br', '', '', 0.00, '', ''),
(176, '', '', '', '', '', '', '', 'contador.valdelino@gmail.com', '', '', 0.00, '', ''),
(177, '', '', '', '', '', '', '', 'costar849@gmail.com', '', '', 0.00, '', ''),
(178, '', '', '', '', '', '', '', 'crakdebola@yahoo.com.br', '', '', 0.00, '', ''),
(179, '', '', '', '', '', '', '', 'cristianop@ibest.com.br', '', '', 0.00, '', ''),
(180, '', '', '', '', '', '', '', 'cristina.csggn@ig.com.br', '', '', 0.00, '', ''),
(181, '', '', '', '', '', '', '', 'cs_teixeira@yahoo.com.br', '', '', 0.00, '', ''),
(182, '', '', '', '', '', '', '', 'cyannbrindes@gmail.com', '', '', 0.00, '', ''),
(183, '', '', '', '', '', '', '', 'danielrotordiesel@outlook.com', '', '', 0.00, '', ''),
(184, '', '', '', '', '', '', '', 'daodamota@hotmail.com', '', '', 0.00, '', ''),
(185, '', '', '', '', '', '', '', 'dayseetacinha@hotmail.com', '', '', 0.00, '', ''),
(186, '', '', '', '', '', '', '', 'ddrika0809@hotmail.com', '', '', 0.00, '', ''),
(187, '', '', '', '', '', '', '', 'deaimoveiscaldasnovas@gmail.com', '', '', 0.00, '', ''),
(188, '', '', '', '', '', '', '', 'debora.gate2009@gmail.com', '', '', 0.00, '', ''),
(189, '', '', '', '', '', '', '', 'deiziella@hotmail.com', '', '', 0.00, '', ''),
(190, '', '', '', '', '', '', '', 'deus21@ig.com.br', '', '', 0.00, '', ''),
(191, '', '', '', '', '', '', '', 'diagnistica.go@hotmail.com', '', '', 0.00, '', ''),
(192, '', '', '', '', '', '', '', 'divanypires@gmail.com', '', '', 0.00, '', ''),
(193, '', '', '', '', '', '', '', 'divina-claudia@hotmail.com', '', '', 0.00, '', ''),
(194, '', '', '', '', '', '', '', 'doraadv@terra.com.br', '', '', 0.00, '', ''),
(195, '', '', '', '', '', '', '', 'dp@grupop7.com.br', '', '', 0.00, '', ''),
(196, '', '', '', '', '', '', '', 'dulcineiasouza@hotmail.com', '', '', 0.00, '', ''),
(197, '', '', '', '', '', '', '', 'dutraortodontia@gmail.com', '', '', 0.00, '', ''),
(198, '', '', '', '', '', '', '', 'dwildson@gmail.com', '', '', 0.00, '', ''),
(199, '', '', '', '', '', '', '', 'edmaria1@hotmail.com', '', '', 0.00, '', ''),
(200, '', '', '', '', '', '', '', 'ednatania@yahoo.com.br', '', '', 0.00, '', ''),
(201, '', '', '', '', '', '', '', 'edsonpalhao5@gmail.com', '', '', 0.00, '', ''),
(202, '', '', '', '', '', '', '', 'eduardomagalhaes1209@hotmail.com', '', '', 0.00, '', ''),
(203, '', '', '', '', '', '', '', 'edudagama@gmail.com', '', '', 0.00, '', ''),
(204, '', '', '', '', '', '', '', 'elenicebeteti@ig.com.br', '', '', 0.00, '', ''),
(205, '', '', '', '', '', '', '', 'eliel.adet@gmail.com', '', '', 0.00, '', ''),
(206, '', '', '', '', '', '', '', 'elton-lan@hotmail.com', '', '', 0.00, '', ''),
(207, '', '', '', '', '', '', '', 'emersoncoordenador@bol.com.br', '', '', 0.00, '', ''),
(208, '', '', '', '', '', '', '', 'emijecsan@hotmail.com', '', '', 0.00, '', ''),
(209, '', '', '', '', '', '', '', 'emilianagomes@terra.com.br', '', '', 0.00, '', ''),
(210, '', '', '', '', '', '', '', 'engenharia2@familiapaulista.com.br', '', '', 0.00, '', ''),
(211, '', '', '', '', '', '', '', 'engenhariajc10@gmail.com', '', '', 0.00, '', ''),
(212, '', '', '', '', '', '', '', 'epeduff@yahoo.com.br', '', '', 0.00, '', ''),
(213, '', '', '', '', '', '', '', 'eraldoab@gmail.com', '', '', 0.00, '', ''),
(214, '', '', '', '', '', '', '', 'erlaneasiqueira@hotmail.com', '', '', 0.00, '', ''),
(215, '', '', '', '', '', '', '', 'estrelasul2010@hotmail.com', '', '', 0.00, '', ''),
(216, '', '', '', '', '', '', '', 'euliviamuro@gmail.com', '', '', 0.00, '', ''),
(217, '', '', '', '', '', '', '', 'fatimagonzaga08@gmail.com', '', '', 0.00, '', ''),
(218, '', '', '', '', '', '', '', 'fcatorquato@hotmail.com', '', '', 0.00, '', ''),
(219, '', '', '', '', '', '', '', 'felipegaldino1703@gmail.com', '', '', 0.00, '', ''),
(220, '', '', '', '', '', '', '', 'felix_farma@hotmail.com', '', '', 0.00, '', ''),
(221, '', '', '', '', '', '', '', 'ferreira_s8@hotmail.com', '', '', 0.00, '', ''),
(222, '', '', '', '', '', '', '', 'financeiro@camagri.com.br', '', '', 0.00, '', ''),
(223, '', '', '', '', '', '', '', 'financeiro02@centrocarautoservice.com.br', '', '', 0.00, '', ''),
(224, '', '', '', '', '', '', '', 'flacarlito@gmail.com', '', '', 0.00, '', ''),
(225, '', '', '', '', '', '', '', 'flaviarm59@gmail.com', '', '', 0.00, '', ''),
(226, '', '', '', '', '', '', '', 'flaviojds089@gmail.com', '', '', 0.00, '', ''),
(227, '', '', '', '', '', '', '', 'floracysilva@hotmail.com', '', '', 0.00, '', ''),
(228, '', '', '', '', '', '', '', 'franchi.regina@ig.com.br', '', '', 0.00, '', ''),
(229, '', '', '', '', '', '', '', 'francisco.cunha@trf1.jus.br', '', '', 0.00, '', ''),
(230, '', '', '', '', '', '', '', 'francisco.x.lima@funasa.gov.br', '', '', 0.00, '', ''),
(231, '', '', '', '', '', '', '', 'frederico.frp@gmail.com', '', '', 0.00, '', ''),
(232, '', '', '', '', '', '', '', 'ger_salves@yahoo.com.br', '', '', 0.00, '', ''),
(233, '', '', '', '', '', '', '', 'GERALDO.MAJELLA@hotmail.com', '', '', 0.00, '', ''),
(234, '', '', '', '', '', '', '', 'geraldoney@terra.com.br', '', '', 0.00, '', ''),
(235, '', '', '', '', '', '', '', 'germannameloo@gmail.com', '', '', 0.00, '', ''),
(236, '', '', '', '', '', '', '', 'gileneteles@gmail.com', '', '', 0.00, '', ''),
(237, '', '', '', '', '', '', '', 'giokono@gmail.com', '', '', 0.00, '', ''),
(238, '', '', '', '', '', '', '', 'gislenemoraes28@gmail.com', '', '', 0.00, '', ''),
(239, '', '', '', '', '', '', '', 'glenister5@hotmail.com', '', '', 0.00, '', ''),
(240, '', '', '', '', '', '', '', 'gondim.edson@gmail.com', '', '', 0.00, '', ''),
(241, '', '', '', '', '', '', '', 'gorettcouto@gmail.com', '', '', 0.00, '', ''),
(242, '', '', '', '', '', '', '', 'gorettcouto@hotmail.com', '', '', 0.00, '', ''),
(243, '', '', '', '', '', '', '', 'hbagyn@gmail.com', '', '', 0.00, '', ''),
(244, '', '', '', '', '', '', '', 'helenabsbmartins@hotmail.com', '', '', 0.00, '', ''),
(246, '', '', '', '', '', '', '', 'hilarinoferreira@gmail.com', '', '', 0.00, '', ''),
(247, '', '', '', '', '', '', '', 'hilcey16@hotmail.com', '', '', 0.00, '', ''),
(248, '', '', '', '', '', '', '', 'hipertintas@bol.com.br', '', '', 0.00, '', ''),
(249, '', '', '', '', '', '', '', 'hugomendespc@hotmail.com', '', '', 0.00, '', ''),
(250, '', '', '', '', '', '', '', 'i.gmodesto@ig.com.br', '', '', 0.00, '', ''),
(251, '', '', '', '', '', '', '', 'idealimoveiscn@hotmail.com', '', '', 0.00, '', ''),
(252, '', '', '', '', '', '', '', 'ionimoveis@globo.com', '', '', 0.00, '', ''),
(253, '', '', '', '', '', '', '', 'ira.imc@gmail.com', '', '', 0.00, '', ''),
(254, '', '', '', '', '', '', '', 'irisdamata@hotmail.com', '', '', 0.00, '', ''),
(255, '', '', '', '', '', '', '', 'isabelamunizfeitosa@gmail.com', '', '', 0.00, '', ''),
(256, '', '', '', '', '', '', '', 'j_mauro_ferreira@hotmail.com', '', '', 0.00, '', ''),
(257, '', '', '', '', '', '', '', 'j1601@hotmail.com', '', '', 0.00, '', ''),
(258, '', '', '', '', '', '', '', 'jaircampospai@gmail.com', '', '', 0.00, '', ''),
(259, '', '', '', '', '', '', '', 'janete_educ@hotmail.com', '', '', 0.00, '', ''),
(260, '', '', '', '', '', '', '', 'janyjaline@yahoo.com.br', '', '', 0.00, '', ''),
(261, '', '', '', '', '', '', '', 'jcsegurado@yahoo.com.br', '', '', 0.00, '', ''),
(262, '', '', '', '', '', '', '', 'jdinizgodoy@gmail.com', '', '', 0.00, '', ''),
(263, '', '', '', '', '', '', '', 'jeovap@gmail.com', '', '', 0.00, '', ''),
(264, '', '', '', '', '', '', '', 'jeronimofranciscob@yahoo.com.br', '', '', 0.00, '', ''),
(265, '', '', '', '', '', '', '', 'jesomarciom@gmail.com', '', '', 0.00, '', ''),
(266, '', '', '', '', '', '', '', 'jjjonasmincri@gmail.com', '', '', 0.00, '', ''),
(267, '', '', '', '', '', '', '', 'joaoreboucas56@hotmail.com', '', '', 0.00, '', ''),
(268, '', '', '', '', '', '', '', 'joaquimaguabranca@gmail.com', '', '', 0.00, '', ''),
(269, '', '', '', '', '', '', '', 'joaquimorivaldo2010@hotmail.com', '', '', 0.00, '', ''),
(270, '', '', '', '', '', '', '', 'jorgelino114@gmail.com', '', '', 0.00, '', ''),
(271, '', '', '', '', '', '', '', 'jorgelu@embratel.com.br', '', '', 0.00, '', ''),
(272, '', '', '', '', '', '', '', 'josecaetano1963@gmail.com', '', '', 0.00, '', ''),
(273, '', '', '', '', '', '', '', 'josemgc1@gmail.com', '', '', 0.00, '', ''),
(274, '', '', '', '', '', '', '', 'joserenatoresende@gmail.com', '', '', 0.00, '', ''),
(275, '', '', '', '', '', '', '', 'josyandmary@hotmail.com', '', '', 0.00, '', ''),
(276, '', '', '', '', '', '', '', 'jpereira@sefaz.am.gov.br', '', '', 0.00, '', ''),
(277, '', '', '', '', '', '', '', 'jsilvaresende@yahoo.com.br', '', '', 0.00, '', ''),
(278, '', '', '', '', '', '', '', 'juanita.costa@gmail.com', '', '', 0.00, '', ''),
(279, '', '', '', '', '', '', '', 'juliomaria@juliomaria.com.br', '', '', 0.00, '', ''),
(280, '', '', '', '', '', '', '', 'juniorborys@gmail.com', '', '', 0.00, '', ''),
(281, '', '', '', '', '', '', '', 'juniorcoelho2@hotmail.com', '', '', 0.00, '', ''),
(282, '', '', '', '', '', '', '', 'JUNIORDU@uol.com.br', '', '', 0.00, '', ''),
(283, '', '', '', '', '', '', '', 'jvn47@hotmail.com', '', '', 0.00, '', ''),
(284, '', '', '', '', '', '', '', 'karinerodovalho@hotmail.com', '', '', 0.00, '', ''),
(285, '', '', '', '', '', '', '', 'katita18@hotmail.com', '', '', 0.00, '', ''),
(286, '', '', '', '', '', '', '', 'keylarh@hotmail.com', '', '', 0.00, '', ''),
(287, '', '', '', '', '', '', '', 'lan.cell.sp@gmail.com', '', '', 0.00, '', ''),
(288, '', '', '', '', '', '', '', 'larissa@masterconsultoria.srv.br', '', '', 0.00, '', ''),
(289, '', '', '', '', '', '', '', 'lcpdprotese@hotmail.com', '', '', 0.00, '', ''),
(290, '', '', '', '', '', '', '', 'lfbnobre@hotmail.com', '', '', 0.00, '', ''),
(291, '', '', '', '', '', '', '', 'lilacandido@hotmail.com', '', '', 0.00, '', ''),
(292, '', '', '', '', '', '', '', 'lizsiq4@hotmail.com', '', '', 0.00, '', ''),
(293, '', '', '', '', '', '', '', 'lucelio.meireles@gmail.com', '', '', 0.00, '', ''),
(294, '', '', '', '', '', '', '', 'lucia1portocastro@hotmail.com', '', '', 0.00, '', ''),
(295, '', '', '', '', '', '', '', 'luciano.rotordiesel@hotmail.com', '', '', 0.00, '', ''),
(296, '', '', '', '', '', '', '', 'lucyguedes2001@hotmail.com', '', '', 0.00, '', ''),
(297, '', '', '', '', '', '', '', 'ludmaria@hotmail.com', '', '', 0.00, '', ''),
(298, '', '', '', '', '', '', '', 'luisedubf@yahoo.com.br', '', '', 0.00, '', ''),
(299, '', '', '', '', '', '', '', 'luizedu_abreu@hotmail.com', '', '', 0.00, '', ''),
(300, '', '', '', '', '', '', '', 'malvessatas@yahoo.com.br', '', '', 0.00, '', ''),
(301, '', '', '', '', '', '', '', 'manoelens@hotmail.com', '', '', 0.00, '', ''),
(302, '', '', '', '', '', '', '', 'MARCELO.BMELO@hotmail.com', '', '', 0.00, '', ''),
(303, '', '', '', '', '', '', '', 'marcia1997@uol.com.br', '', '', 0.00, '', ''),
(304, '', '', '', '', '', '', '', 'margarethbailoni@gmail.com', '', '', 0.00, '', ''),
(305, '', '', '', '', '', '', '', 'marheco@bol.com.br', '', '', 0.00, '', ''),
(306, '', '', '', '', '', '', '', 'maria.rodrigues@saude.gov.br', '', '', 0.00, '', ''),
(307, '', '', '', '', '', '', '', 'mariabarbara.p@hotmail.com', '', '', 0.00, '', ''),
(308, '', '', '', '', '', '', '', 'mariaelaineborges@hotmail.com', '', '', 0.00, '', ''),
(309, '', '', '', '', '', '', '', 'marisabruder@msn.com', '', '', 0.00, '', ''),
(310, '', '', '', '', '', '', '', 'marisol@saneago.com.br', '', '', 0.00, '', ''),
(311, '', '', '', '', '', '', '', 'marlene@carvalhomc.com.br', '', '', 0.00, '', ''),
(312, '', '', '', '', '', '', '', 'martins.d.alva@hotmail.com', '', '', 0.00, '', ''),
(313, '', '', '', '', '', '', '', 'marysmachados@hotmail.com', '', '', 0.00, '', ''),
(314, '', '', '', '', '', '', '', 'maura.tania@hotmail.com', '', '', 0.00, '', ''),
(315, '', '', '', '', '', '', '', 'mauricioriberplas@hotmail.com', '', '', 0.00, '', ''),
(316, '', '', '', '', '', '', '', 'mauro@sosoja.com.br', '', '', 0.00, '', ''),
(317, '', '', '', '', '', '', '', 'max.okamoto@gmail.com', '', '', 0.00, '', ''),
(318, '', '', '', '', '', '', '', 'maxmazareno@hotmail.com', '', '', 0.00, '', ''),
(319, '', '', '', '', '', '', '', 'mchozen@gmail.com', '', '', 0.00, '', ''),
(320, '', '', '', '', '', '', '', 'mcristina.pinheiro14@gmail.com', '', '', 0.00, '', ''),
(321, '', '', '', '', '', '', '', 'mendes-climaco@hotmail.com', '', '', 0.00, '', ''),
(322, '', '', '', '', '', '', '', 'mendesemerson@hotmail.com', '', '', 0.00, '', ''),
(323, '', '', '', '', '', '', '', 'mgamart@gmail.com', '', '', 0.00, '', ''),
(324, '', '', '', '', '', '', '', 'mharliagr@gmail.com', '', '', 0.00, '', ''),
(325, '', '', '', '', '', '', '', 'mlibanio1@gmail.com', '', '', 0.00, '', ''),
(326, '', '', '', '', '', '', '', 'mmarquesn@gmail.com', '', '', 0.00, '', ''),
(327, '', '', '', '', '', '', '', 'moiseslimafilho@gmail.com', '', '', 0.00, '', ''),
(328, '', '', '', '', '', '', '', 'mouravivi2013@hotmail.com', '', '', 0.00, '', ''),
(329, '', '', '', '', '', '', '', 'msfodonto@hotmail.com', '', '', 0.00, '', ''),
(330, '', '', '', '', '', '', '', 'muciogomes@hotmail.com', '', '', 0.00, '', ''),
(331, '', '', '', '', '', '', '', 'mudjr@hotmail.com', '', '', 0.00, '', ''),
(332, '', '', '', '', '', '', '', 'mzpzelia@gmail.com', '', '', 0.00, '', ''),
(333, '', '', '', '', '', '', '', 'nairfugimoto@gmail.com', '', '', 0.00, '', ''),
(334, '', '', '', '', '', '', '', 'neide_linda@hotmail.com', '', '', 0.00, '', ''),
(335, '', '', '', '', '', '', '', 'nelzylouza@gmail.com', '', '', 0.00, '', ''),
(336, '', '', '', '', '', '', '', 'nilzo.alves@hotmail.com', '', '', 0.00, '', ''),
(337, '', '', '', '', '', '', '', 'nirooliveira54@gmail.com', '', '', 0.00, '', ''),
(338, '', '', '', '', '', '', '', 'noemigrodrigues@hotmail.com', '', '', 0.00, '', ''),
(339, '', '', '', '', '', '', '', 'norteminas@hotmail.com.br', '', '', 0.00, '', ''),
(340, '', '', '', '', '', '', '', 'norval10@hotmail.com', '', '', 0.00, '', ''),
(341, '', '', '', '', '', '', '', 'nubia3010@yahoo.com.br', '', '', 0.00, '', ''),
(342, '', '', '', '', '', '', '', 'olavina@assuncao.net', '', '', 0.00, '', ''),
(343, '', '', '', '', '', '', '', 'orlenepsilva@gmail.com', '', '', 0.00, '', ''),
(344, '', '', '', '', '', '', '', 'osmarrcampos@yahoo.com.br', '', '', 0.00, '', ''),
(345, '', '', '', '', '', '', '', 'pachecao1950@hotmail.com', '', '', 0.00, '', ''),
(346, '', '', '', '', '', '', '', 'PAMPLONALUCIANO33@gmail.com', '', '', 0.00, '', ''),
(347, '', '', '', '', '', '', '', 'pastora_ana_min_@hotmail.com', '', '', 0.00, '', ''),
(348, '', '', '', '', '', '', '', 'pcsanches_1@yahoo.com.br', '', '', 0.00, '', ''),
(349, '', '', '', '', '', '', '', 'pgcurisco@gmail.com', '', '', 0.00, '', ''),
(350, '', '', '', '', '', '', '', 'pinheirojv27@gmail.com', '', '', 0.00, '', ''),
(351, '', '', '', '', '', '', '', 'pollianabahmad@gmail.com', '', '', 0.00, '', ''),
(352, '', '', '', '', '', '', '', 'preludiomusica@gmail.com', '', '', 0.00, '', ''),
(353, '', '', '', '', '', '', '', 'priveplan@terra.com.br', '', '', 0.00, '', ''),
(354, '', '', '', '', '', '', '', 'PROFESSORAHOLANDACANTRIZ@gmail.com', '', '', 0.00, '', ''),
(355, '', '', '', '', '', '', '', 'pt2lb@uol.com.br', '', '', 0.00, '', ''),
(356, '', '', '', '', '', '', '', 'r246rodrigues@hotmail.com', '', '', 0.00, '', ''),
(357, '', '', '', '', '', '', '', 'rafael_amarante@uol.com.br', '', '', 0.00, '', ''),
(358, '', '', '', '', '', '', '', 'rafaelrodrigues82@hotmail.com', '', '', 0.00, '', ''),
(359, '', '', '', '', '', '', '', 'rainer12alencar@hotmail.com', '', '', 0.00, '', ''),
(360, '', '', '', '', '', '', '', 'ramonrezende@hotmail.com', '', '', 0.00, '', ''),
(361, '', '', '', '', '', '', '', 'renata_coqueiros@hotmail.com', '', '', 0.00, '', ''),
(362, '', '', '', '', '', '', '', 'resendegc@hotmail.com', '', '', 0.00, '', ''),
(363, '', '', '', '', '', '', '', 'rfbatitude@hotmail.com', '', '', 0.00, '', ''),
(364, '', '', '', '', '', '', '', 'ribeiro.auri@yahoo.com.br', '', '', 0.00, '', ''),
(365, '', '', '', '', '', '', '', 'ricardo.marrocos@funasa.gov.br', '', '', 0.00, '', ''),
(366, '', '', '', '', '', '', '', 'ricardo_oliveiracesar@hotmail.com', '', '', 0.00, '', ''),
(367, '', '', '', '', '', '', '', 'ricardollobet@hotmail.com', '', '', 0.00, '', ''),
(368, '', '', '', '', '', '', '', 'rivaldoaassis@hotmail.com', '', '', 0.00, '', ''),
(369, '', '', '', '', '', '', '', 'robcampos2010@hotmail.com', '', '', 0.00, '', ''),
(370, '', '', '', '', '', '', '', 'robjosy@terra.com.br', '', '', 0.00, '', ''),
(371, '', '', '', '', '', '', '', 'rodosbrasil@oi.com.br', '', '', 0.00, '', ''),
(372, '', '', '', '', '', '', '', 'rogerio.marinho@xerox.com', '', '', 0.00, '', ''),
(373, '', '', '', '', '', '', '', 'rogerio_o.araujo@hotmail.com', '', '', 0.00, '', ''),
(374, '', '', '', '', '', '', '', 'rooseveltdiniz@hotmail.com', '', '', 0.00, '', ''),
(375, '', '', '', '', '', '', '', 'rosane.miotto@terra.com.br', '', '', 0.00, '', ''),
(376, '', '', '', '', '', '', '', 'rosangelampcsantos@gmail.com', '', '', 0.00, '', ''),
(377, '', '', '', '', '', '', '', 'rosemcs912@hotmail.com', '', '', 0.00, '', ''),
(378, '', '', '', '', '', '', '', 'rubensdivinodasilva@icloud.com', '', '', 0.00, '', ''),
(379, '', '', '', '', '', '', '', 'sabc1979@hotmail.com', '', '', 0.00, '', ''),
(380, '', '', '', '', '', '', '', 'sca53@hotmail.com', '', '', 0.00, '', ''),
(381, '', '', '', '', '', '', '', 'sec.diretoria@homehospital.com.br', '', '', 0.00, '', ''),
(382, '', '', '', '', '', '', '', 'serginhomed@hotmail.com', '', '', 0.00, '', ''),
(383, '', '', '', '', '', '', '', 'sergiosilva.caldas@gmail.com', '', '', 0.00, '', ''),
(384, '', '', '', '', '', '', '', 'shirleifariacunha@gmail.com', '', '', 0.00, '', ''),
(385, '', '', '', '', '', '', '', 'siledaalmeidaster@gmail.com', '', '', 0.00, '', ''),
(386, '', '', '', '', '', '', '', 'silvestre@stm.gov.br', '', '', 0.00, '', ''),
(387, '', '', '', '', '', '', '', 'silvia@liberdade-contabil.com.br', '', '', 0.00, '', ''),
(388, '', '', '', '', '', '', '', 'si-monevida@hotmail.com', '', '', 0.00, '', ''),
(389, '', '', '', '', '', '', '', 'siqueirakaroline@outlook.com', '', '', 0.00, '', ''),
(390, '', '', '', '', '', '', '', 'smtere@msn.com', '', '', 0.00, '', ''),
(391, '', '', '', '', '', '', '', 'soares.inez@bol.com.br', '', '', 0.00, '', ''),
(392, '', '', '', '', '', '', '', 'soccorro_nary@hotmail.com', '', '', 0.00, '', ''),
(393, '', '', '', '', '', '', '', 'solange.queiroz@superig.com.br', '', '', 0.00, '', ''),
(394, '', '', '', '', '', '', '', 'solarrudama@hotmail.com', '', '', 0.00, '', ''),
(395, '', '', '', '', '', '', '', 'sotemari4@gmail.com', '', '', 0.00, '', ''),
(396, '', '', '', '', '', '', '', 'stampgrafica@hotmail.com', '', '', 0.00, '', ''),
(397, '', '', '', '', '', '', '', 'sucesso1contabilidade@gmail.com', '', '', 0.00, '', ''),
(398, '', '', '', '', '', '', '', 'sulamita_cunhabarros@yahoo.com.br', '', '', 0.00, '', ''),
(399, '', '', '', '', '', '', '', 'tafs_dc@yahoo.com.br', '', '', 0.00, '', ''),
(400, '', '', '', '', '', '', '', 'terplan@gmail.com', '', '', 0.00, '', ''),
(401, '', '', '', '', '', '', '', 'TERRA_PLAN44@hotmail.com', '', '', 0.00, '', ''),
(402, '', '', '', '', '', '', '', 'thiagopbarcelos@gmail.com', '', '', 0.00, '', ''),
(403, '', '', '', '', '', '', '', 'toledoamarok@yahoo.com.br', '', '', 0.00, '', ''),
(404, '', '', '', '', '', '', '', 'tolentinot@ig.com.br', '', '', 0.00, '', ''),
(405, '', '', '', '', '', '', '', 'trsmartinez49@gmail.com', '', '', 0.00, '', ''),
(406, '', '', '', '', '', '', '', 'tsct.df@gmail.com', '', '', 0.00, '', ''),
(407, '', '', '', '', '', '', '', 'valdenorqj@gmail.com', '', '', 0.00, '', ''),
(408, '', '', '', '', '', '', '', 'VALERIA.M.FERREIRA@hotmail.com', '', '', 0.00, '', ''),
(409, '', '', '', '', '', '', '', 'valeria.neiva@embrapa.br', '', '', 0.00, '', ''),
(410, '', '', '', '', '', '', '', 'valfredo.valle@hotmail.com', '', '', 0.00, '', ''),
(411, '', '', '', '', '', '', '', 'valtecimachado@gmail.com', '', '', 0.00, '', ''),
(412, '', '', '', '', '', '', '', 'vanessasempresa@gmail.com', '', '', 0.00, '', ''),
(413, '', '', '', '', '', '', '', 'vanessatsx@hotmail.com', '', '', 0.00, '', ''),
(414, '', '', '', '', '', '', '', 'vanilda.alcantara@caixa.gov.br', '', '', 0.00, '', ''),
(415, '', '', '', '', '', '', '', 'vendas@papellink.com.br', '', '', 0.00, '', ''),
(416, '', '', '', '', '', '', '', 'virtualbel@gmail.com', '', '', 0.00, '', ''),
(417, '', '', '', '', '', '', '', 'virtualjogos@hotmail.com', '', '', 0.00, '', ''),
(418, 'Viviane Lorenco', '', '', '', '', 'AZ - AzalÃ©ia', '87', 'vivianelorenco@hotmail.com', '', '', 0.00, '', ''),
(419, '', '', '', '', '', '', '', 'waniafbertanha@gmail.com', '', '', 0.00, '', ''),
(420, '', '', '', '', '', '', '', 'werleypereira@ig.com.br', '', '', 0.00, '', ''),
(421, '', '', '', '', '', '', '', 'weuderrr@gmail.com', '', '', 0.00, '', ''),
(422, '', '', '', '', '', '', '', 'wic.felix@yahoo.com.br', '', '', 0.00, '', ''),
(423, '', '', '', '', '', '', '', 'will_rodrigue@hotmail.com', '', '', 0.00, '', ''),
(424, '', '', '', '', '', '', '', 'willemmadison@globo.com', '', '', 0.00, '', ''),
(425, '', '', '', '', '', '', '', 'williana.bezerra@gmail.com', '', '', 0.00, '', ''),
(426, '', '', '', '', '', '', '', 'wjcal22@hotmail.com', '', '', 0.00, '', ''),
(427, '', '', '', '', '', '', '', 'wjoselyra@hotmail.com', '', '', 0.00, '', ''),
(428, '', '', '', '', '', '', '', 'xandisato@gmail.com', '', '', 0.00, '', ''),
(429, '', '', '', '', '', '', '', 'YURIAB11@hotmail.com', '', '', 0.00, '', ''),
(430, 'JosÃ© Alves Pacheco Neto ', 'Rod. Go 213 km 2.5 Res. Village T Caldas - etapa Gardenia casa 13', 'Caldas Novas', 'GoiÃ¡s (GO)', '', 'GA - GardÃªnia', '13 e 18', 'pachecao1950@hotmail.com ', '75690000', '', 0.00, '', ''),
(431, 'Caleul Raposo Raposo', 'Rua 8 ChÃ¡cara 207 casa 21', 'BrasÃ­lia', 'Distrito Federal (DF)', '72006870', 'JAC - JacarandÃ¡s', '29', 'caleul@1portodos.com.br', '(61) 995550726', 'Filho do RogÃ©rio', 0.00, '', ''),
(432, 'EDNA SOARES DA SILVA - CLEDSON MARQUES SOARES DA SILVA', 'QE 28 CONJUNTO L CASA 08', 'BRASÃLIA', 'Distrito Federal (DF)', '71060-122', 'PIT - Pitangueiras', '42', 'cledson.marques@hotmail.com', '(61) 98401-2016', 'Outro telefone: (61) 3383-4295', 0.00, '', ''),
(433, 'Ana Maria Carvalho Soares', 'conj. Res. Benjamin SodrÃ©  Rua CanindÃ©  Qd 12 CS 07', 'BelÃ©m ', 'ParÃ¡ (PA)', '66635140', 'BO - Bougainville', '99', 'annacarvalho52@hotmail.com', '91983441170', '', 0.00, '', ''),
(434, 'Rosane Miotto de Oliveira', 'Rua Temininos, 485  Jardim Leblon', 'Campo Grande ', 'Mato Grosso do Sul (MS)', '79.092-011', 'PIT - Pitangueiras', '50', 'rosane.miotto@terra.com.br', '67 981081937', '', 0.00, '', ''),
(435, 'Maria Cristina Pinheiro Costa', 'QSE 04 CASA 15', 'Taguatinga', 'Distrito Federal (DF)', '72025040', 'JAC - JacarandÃ¡s', '49', 'mcristina.pinheiro14@gmail.com', '6130385353', 'A casa consta em dois nomes Daniel Costa Pinheiro e Maria Crisitna Pinheiro Costa', 0.00, '', ''),
(436, 'AluÃ­zio Pereira', 'Rua Silvio Daige n245.', 'GuarujÃ¡', 'SÃ£o Paulo (SP)', '11440550', 'AZ - AzalÃ©ia', '17', 'Ladobbb@hotmail.com', '13 33841830', 'Contato do meu filho ClÃ¡udio (13)991361490/(62)981450554.', 0.00, '', ''),
(437, 'Marcelo Henrique Costa', 'Rua 115-G, 27, St. Sul', 'GoiÃ¢nia', 'GoiÃ¡s (GO)', '74085310', 'BO - Bougainville', '32/27', 'marheco@bol.com.br', '6232151239', '', 0.00, '', ''),
(438, 'EMILIO BARBOSA RODRIGUES', 'Residencial Asa Branca - br 060 km 15', 'Recanto das Emas', 'Distrito Federal (DF)', '72668-100', 'JAC - JacarandÃ¡s', '008', 'emijecsan@hotmail.com', '53 981170926', '', 0.00, '', ''),
(439, 'EMILIO BARBOSA RODRIGUES', '', '', '', '', 'JAC - JacarandÃ¡s', '08', 'emijecsan@hotmail.com', '', '', 0.00, '', ''),
(440, 'Maria  Auxiliadora da ConceiÃ§Ã£o Lopes ', 'Rua SÃ£o Benedito, 1909', 'SÃ£o Paulo', 'SÃ£o Paulo (SP)', '04735-004', 'JAC - JacarandÃ¡s', '30', 'doraadv@terra.com.br', '(11) 5522 3666', 'Meu irmÃ£o o Paulo Antonio Dias Lopes, serÃ¡ sempre o meu procurador. ', 0.00, '', ''),
(441, 'Emilio Barbosa Rodrigues', 'C.R ASA BRANCA - BR 060 KM 15 - CASA MF 24', 'RECANTO DAS EMAS', 'Distrito Federal (DF)', '72668-100', 'JAC - JacarandÃ¡s', 'JA 008', 'emijecsan@hotmail.com', '61-992785900', 'AGRADECERIA ENVIAR-ME O BOLETO DE VENCIMENTO 20 DE DEZEMBRO DE 2017. OU INDICAR-ME QUEM ESTÃ AGORA ADMINISTRANDO AS TAXAS DE CONDOMINIO. OBRIGADO', 0.00, '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro_adm`
--

CREATE TABLE `cadastro_adm` (
  `id` int(11) NOT NULL,
  `proprietario` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `cpf` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `endereco` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `bairro` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `cidade` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `estado` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `cep` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `etapa_village` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `unidade_etapa_village` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `telefone` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `tel_celular` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `obsgeral` text COLLATE latin1_general_ci NOT NULL,
  `tipo` varchar(3) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `cadastro_adm`
--

INSERT INTO `cadastro_adm` (`id`, `proprietario`, `cpf`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `etapa_village`, `unidade_etapa_village`, `email`, `telefone`, `tel_celular`, `obsgeral`, `tipo`) VALUES
(2, 'RogÃ©rio Wilson LÃ©lis Caixeta', '488.321.381-15', 'Rua 8 ChÃ¡cara 207 casa 21', 'Vicente Pires', 'BrasÃ­lia', 'Distrito Federal (DF)', '72.006-870', 'JacarandÃ¡s', '029', 'rogerio@1portodos.com.br', '61 3397-5107', '61 99555-0726', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `convencao`
--

CREATE TABLE `convencao` (
  `id` int(11) NOT NULL,
  `clausula` varchar(20) NOT NULL,
  `paragrafo` varchar(20) NOT NULL,
  `item` varchar(20) NOT NULL,
  `conteudo` text NOT NULL,
  `conteudo_proposto` text NOT NULL,
  `dt_criacao` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `convencao`
--

INSERT INTO `convencao` (`id`, `clausula`, `paragrafo`, `item`, `conteudo`, `conteudo_proposto`, `dt_criacao`) VALUES
(1, 'ClÃ¡usula 1Âª', '', '', 'O CondomÃ­nio Residencial Village Thermas das Caldas Ã© constituÃ­do por sete\r\netapas, sendo 06(seis) etapas residenciais e uma comercial, totalizando o empreendimento\r\na Ã¡rea de 290.400.00 m2 de Ã¡rea privativa e 66.143.509 + 3.901.208 m2 de doaÃ§Ã£o feita\r\npela Construtora Serra de Caldas como Ã¡rea comum. O CondomÃ­nio reger-se sob a\r\ndenominaÃ§Ã£o de \"RESIDENCIAL VILLAGE THERMAS DAS CALDAS\", serÃ¡ constituÃ­do\r\nde 549 (quinhentos e quarenta e nove) casas de campo, uma Ã¡rea destinada Ã¡ comÃ©rcio\r\nduas Ã¡reas destinadas Ã  perfuraÃ§Ã£o de poÃ§os artesianos, uma Ã¡rea destinada Ã  construÃ§Ã£o de\r\numa Ã¡rea de lazer, Ã¡rea verde, vias acesso e alamedas, sendo que as Ã¡reas destinadas ao\r\ncomÃ©rcio e Ã  perfuraÃ§Ã£o de poÃ§os artesianos sÃ£o de propriedade da empresa incorporadora. As\r\netapas residenciais serÃ£o assim constituÃ­das:\r\n<p>\r\n1Âª. Etapa: Residencial Pitangueiras - constituÃ­do pelas fraÃ§Ãµes idÃ©ias de nÂ°l Ã  94\r\ntotalizando a Ã¡rea de 51.468.960 m2, sendo 39.746.034 m2 de Ã¡rea privativa e\r\n11.722.926 m2 de Ã¡rea comum, o que representa uma fraÃ§Ã£o ideal de 17,72% do\r\nempreendimento.\r\n<p>\r\n2Âª. Etapa: Residencial OrquÃ­deas â€” constituÃ­da pelas fraÃ§Ãµes ideais de nÂ°. l Ã  99,\r\ntotalizando a Ã¡rea de 49.605,294 m2, sendo 38.306,850 m2 de Ã¡rea privativa e 11.298,444\r\nm2 de Ã¡rea comum, o que representa uma fraÃ§Ã£o ideal de 17,08% do empreendimento.\r\n<p>\r\n3Âª. Etapa: Residencial Bougainvilles - constituÃ­do pelas fraÃ§Ãµes idÃ©ias de nÂ°. l Ã  106,\r\ntotalizando a Ã¡rea de 57.769,410 m2, sendo 42.811,45 m2 de Ã¡rea privativa e 17.059,168\r\nm2 de Ã¡rea comum, o que representa uma fraÃ§Ã£o ideal de 19,89% do empreendimento. Foram\r\nsuprimidas as unidades 5,6,17,18,29,30,41 e 42, transformadas em passagem.\r\n<p>\r\n4Âª. Etapa: Residencial AzalÃ©ias - constituÃ­da pelas fraÃ§Ãµes ideais de nÂ°. l Ã  114, totalizando\r\na Ã¡rea de 56.031,010 m2, sendo 43.269,000 m2 de Ã¡rea privativa e 12.762,010 m2 de Ã¡rea\r\ncomum, o que representa uma fraÃ§Ã£o ideal de 19,30% do empreendimento.\r\n<p>\r\n5Âª. Etapa: Residencial GardÃªnias - constituÃ­da pelas fraÃ§Ãµes ideais de nÂ°.l Ã  78,\r\ntotalizando a Ã¡rea de 42.344,727 m2, sendo 32.700,000 m2 de Ã¡rea privativa e 9.644,727\r\nm2 de Ã¡rea comum, o que representa uma fraÃ§Ã£o ideal de 14,58% do empreendimento.\r\n<p>\r\n6Âª: Residencial JacarandÃ¡s - constituÃ­do pelas fraÃ§Ãµes ideais de nÂ°. l Ã¡ 58,\r\ntotalizando a Ã¡rea de 29.069,811 m2, sendo 22.448,670 m2 de Ã¡rea privativa e 6.621,141\r\nm2 de Ã¡rea comum, o que representa uma fraÃ§Ã£o ideal de 10,01% do empreendimento.\r\n<p>\r\n7Âª: Ãrea Comercial - totalizando a Ã¡rea de 4.110,788 m2, sendo 3.174,487 m2 de\r\nÃ¡rea privativa e 936,301\r\n<p>', '', '09/11/2017'),
(2, 'ClÃ¡usula 2Âª', '', '', 'Todas as casas de campo serÃ£o padronizadas, constituÃ­das de varanda, sala, dois ou trÃªs quartos, banheiro e cozinha, de acordo com os projetos jÃ¡ aprovados e executados em terrenos de fraÃ§Ãµes ideais de iguais dimensÃµes, exceto as localidades nas esquinas que serÃ£o menores em virtude dos chanfrados.', '', '09/11/2017'),
(4, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 1Âº', '', 'As unidades autÃ´nomas, a critÃ©rio da Empresa Incorporadora, e dos promitentes compradores das fraÃ§Ãµes idÃ©ias, poderÃ£o ser construÃ­das sob regime de incorporaÃ§Ã£o, administraÃ§Ã£o, empreitada ou qualquer outra modalidade nÃ£o defesa Lei.', '', '09/11/2017'),
(5, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 2Âº', '', 'A comercializaÃ§Ã£o do empreendimento se darÃ¡ por etapa, sendo que, apÃ³s a comercializaÃ§Ã£o das fraÃ§Ãµes idÃ©ias do terreno, a construÃ§Ã£o de cada unidade, autÃ´noma (casa de campo), serÃ¡ de responsabilidade do condÃ´mino proprietÃ¡rio que arcarÃ¡ com todas as despesas e responsabilidade da referia obra e, no caso optar por empresa que nÃ£o seja a incorporadora, arcarÃ¡ com as despesas de obtenÃ§Ã£o do habite-se e INSS.', '', '09/11/2017'),
(6, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 3Âº', '', 'Para a execuÃ§Ã£o de obras fica o interessado obrigado a notificar o CondomÃ­nio sobre o inicio das mesmas, indicando a administraÃ§Ã£o o nome do funcionÃ¡rio contratado para tal, os quais terÃ£o acesso ao local da construÃ§Ã£o, mediante crachÃ¡ fornecido para sua identificaÃ§Ã£o.', '', '09/11/2017'),
(7, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 4Âº', '', 'A empresa Incorporadora edificarÃ¡ na Ã¡rea destinada Ã  construÃ§Ã£o da Ãrea de lazer somente um parque de Ã¡guas frias, contendo uma piscina de adulto, uma piscina para uso infantil, uma quadra de esportes, uma playground, um prÃ©dio destinado Ã  lanchonete-e uma pista destinada a pratica de \"Cooper\".', '', '09/11/2017'),
(8, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 5Âº', '', 'Os condÃ´minos arcarÃ£o com a expansÃ£o e a instalaÃ§Ã£o dos equipamentos da Ã¡rea de lazer, cabendo-lhe ainda a administraÃ§Ã£o da mesma, esta de uso exclusivo dos condÃ´minos e seus familiares.', '', '09/11/2017'),
(9, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 6Âº', '', 'Ã‰ facultado ao condÃ´mino fazer acrÃ©scimos horizontais de acordo com o regimento interno e deliberaÃ§Ã£o de AssemblÃ©ia na casa de campo em continuaÃ§Ã£o Ã  parte jÃ¡ construÃ­da, obedecendo ao projeto e a padronizaÃ§Ã£o do empreendimento, bem como com a observÃ¢ncia das normas tÃ©cnicas relativas Ã  obra, nÃ£o sendo permitido a construÃ§Ã£o de muro de alvenarias, grades, etc., a qualquer pretexto.', '', '09/11/2017'),
(10, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 7Âº', '', 'O CondÃ´mino proprietÃ¡rio de duas ou mais fraÃ§Ãµes ideais de terreno contÃ­guas, deverÃ¡ obrigatoriamente, edificar a casa de campo obedecendo ao projeto padrÃ£o, sendo-lhe facultado, porÃ©m, destinar as Ã¡reas remanescentes, exceto as Ã¡reas verdes comuns, para a construÃ§Ã£o de Ã¡reas de lazer, de acordo com o regimento interno e deliberaÃ§Ã£o de assemblÃ©ia geral, sendo que o projeto, bem como a aprovaÃ§Ã£o destas edificaÃ§Ãµes pelos Ã³rgÃ£os competentes ficarÃ¡ a cargo exclusivo do condÃ´mino.', '', '09/11/2017'),
(11, 'ClÃ¡usula 2Âº', 'ParÃ¡grafo 8Âº', '', 'Qualquer alteraÃ§Ã£o das unidades privativas em desacordo com os projetos jÃ¡ aprovados serÃ¡ obrigatoriamente submetida a aprovaÃ§Ã£o do sÃ­ndico, que farÃ¡ a sua apreciaÃ§Ã£o em conjunto com o conselho fiscal e o conselheiro da etapa respectiva.', '', '09/11/2017'),
(12, 'ClÃ¡usula 3Âº', '', '', 'SÃ£o partes de uso e propriedade exclusiva de cada condÃ´mino as respectivas unidades autÃ´nomas com todos seus espaÃ§os, instalaÃ§Ãµes internas atÃ© a interseÃ§Ã£o com as linhas-troncas (marcos).', '', '09/11/2017'),
(13, 'ClÃ¡usula 4Âº', '', '', 'As Ã¡reas e coisas de uso comum do CondomÃ­nio sÃ£o inalienÃ¡veis, indivisÃ­veis e insusceptÃ­veis de utilizaÃ§Ã£o exclusiva por qualquer condÃ´mino, indissoluvelmente ligada Ã¡s partes autÃ´nomas como acessÃ³rio, sendo elas: o terreno, as cercas vivas, a portaria, o sistema de aduÃ§Ã£o e distribuiÃ§Ã£o de Ã¡gua, luz, esgoto, as vias de acesso, a alameda, o sistema viÃ¡rio, os jardins, as Ã¡reas de circulaÃ§Ã£o, Ã¡rea de lazer e demais Ã¡reas com a mesma natureza e destinaÃ§Ã£o. Conforme Art. 1331 do CC - disposiÃ§Ãµes gerais.', '', '09/11/2017'),
(14, 'ClÃ¡usula 5Âº', '', '', 'O local destinado Ã  Ã¡reas de lazer constitui Ã¡rea comum de todos, sendo que, o seu acesso serÃ¡ exclusivo aos condÃ´minos e seus familiares, nÃ£o sendo permitida a comercializaÃ§Ã£o de tÃ­tulos, aÃ§Ãµes, ou qualquer espÃ©cie de alienaÃ§Ã£o no todo ou em parte.', '', '09/11/2017'),
(15, 'ClÃ¡usula 6Âº', '', '', 'As unidades privativas, assim compreendidas das casas de campo e a respectiva fraÃ§Ã£o ideal, tÃªm finalidade exclusivamente residencial ou residencial temporÃ¡ria, nos casos de ocupaÃ§Ã£o por perÃ­odo determinado, devendo o condÃ´mino, usufruÃ­-la no exercÃ­cio de suas prerrogativas e direitos, devendo ainda observar, alÃ©m das condiÃ§Ãµes do tÃ­tulo aquisitivo, as normas da presente ConvenÃ§Ã£o, do Regimento Interno e demais disposiÃ§Ãµes sobre a matÃ©ria.', '', '09/11/2017'),
(16, 'ClÃ¡usula 6Âº', 'ParÃ¡grafo Ãšnico', '', 'Ã‰ vedada a alteraÃ§Ã£o da utilizaÃ§Ã£o ou quaisquer modificaÃ§Ãµes da edificaÃ§Ã£o que lhe desvirtue o uso ou altere as condiÃ§Ãµes que sÃ£o prÃ³prias Ã  sua destinaÃ§Ã£o.', '', '09/11/2017'),
(17, 'ClÃ¡usula 7Âº', '', '', 'O ExercÃ­cio financeiro serÃ¡ de 12 meses, de incumbido ao SÃ­ndico, em conjunto com os Conselhos Fiscal e Administrativo, preparar o orÃ§amento para custeio das despesas do exercÃ­cio, estimando as despesas e receitas do condomÃ­nio, a fim de serem\r\nobjetos de deliberaÃ§Ã£o da AssemblÃ©ia Gerais OrdinÃ¡rias que ocorrerÃ¡ na primeira quinzena de marÃ§o.', '', '09/11/2017'),
(18, 'ClÃ¡usula 7Âº', 'ParÃ¡grafo 1Âº', '', 'As previsÃµes mensais sÃ³ poderÃ£o ser reajustadas pelo SÃ­ndico, mediante deliberaÃ§Ã£o da AssemblÃ©ia ExtraordinÃ¡ria, para proceder aos ajustes que se fizerem necessÃ¡rios.', '', '09/11/2017'),
(19, 'ClÃ¡usula 7Âº', 'ParÃ¡grafo 2Âº', '', 'A receita do CondomÃ­nio serÃ¡ constituÃ­da pelas contribuiÃ§Ãµes dos condÃ´minos, aprovadas pelas AssemblÃ©ia Gerais OrdinÃ¡ria e ExtraordinÃ¡rias, e, arrecadada na forma prevista nesta ConvenÃ§Ã£o.', '', '09/11/2017'),
(20, 'ClÃ¡usula 7Âº', 'ParÃ¡grafo 3Âº', '', 'Os recebimentos de multa, juros moratÃ³rios, doaÃ§Ãµes, ou qualquer outro meio de entrada, integrarÃ£o a receita total do CondomÃ­nio.', '', '09/11/2017'),
(21, 'ClÃ¡usula 8Âº', '', '', 'Constituem Despesas Gerais Condominiais:\r\n<p>\r\na) Os prÃªmios dos seguros;\r\n<p>\r\nb) Os tributos incidentes sobre a as partes comuns do CondomÃ­nio;\r\n<p>\r\nc) As despesas derivadas do consumo de energia elÃ©trica, Ã¡gua e telefone das partes e coisas comuns do CondomÃ­nio;\r\n<p>\r\nd) A remuneraÃ§Ã£o do SÃ­ndico, gerente, honorÃ¡rios contÃ¡beis, salÃ¡rios e respectivos encargos sociais e trabalhistas dos empregados do CondomÃ­nio;\r\n<p>\r\ne) As despesas com o serviÃ§o de limpeza, conservaÃ§Ã£o, reparos e manutenÃ§Ã£o das instalaÃ§Ãµes em todas as Ã¡reas de uso com o CondomÃ­nio;\r\n<p>\r\nf) Outras despesas aprovadas pela AssemblÃ©ia Geral OrdinÃ¡ria ou ExtraordinÃ¡ria.\r\n<p>', '', '09/11/2017'),
(22, 'ClÃ¡usula 9Âº', '', '', 'SerÃ¡ apresentada, para apreciaÃ§Ã£o dos condÃ´minos na AssemblÃ©ia Geral OrdinÃ¡ria, a proposta de orÃ§amento de despesas para o exercÃ­cio subseqÃ¼ente, elaborada pelo SÃ­ndico, em conjunto com os Conselhos Administrativo e Fiscal.', '', '09/11/2017'),
(23, 'ClÃ¡usula 9Âº', 'ParÃ¡grafo Ãšnico', '', 'SerÃ¡ aprovado e fixado em AssemblÃ©ia Geral ExtraordinÃ¡ria, por 2/3 (dois terÃ§os) dos condÃ´minos presentes, o orÃ§amento das despesas extraordinÃ¡rias nÃ£o previstas no orÃ§amento anual, obrigando-se todos os condÃ´minos pelo pagamento das despesas que serÃ£o rateadas entre todas as unidades autÃ´nomas.', '', '09/11/2017'),
(24, 'ClÃ¡usula 10Âº', '', '', 'CorrerÃ¡ por inteira e exclusiva conta de cada condÃ´mino ou morador as despesas a que, por qualquer forma venham causar prejuÃ­zos Ã s partes comuns do CondomÃ­nio ou a vizinhos, por trabalhos, obras, ou reparaÃ§Ãµes realizadas em suas unidades autÃ´nomas.', '', '09/11/2017'),
(25, 'ClÃ¡usula 11Âº', '', '', 'As taxas de CondomÃ­nio deverÃ£o ser pagas nas datas estabelecidas em AssemblÃ©ia, obedecidas as normas de cobranÃ§a que para tanto forem adotadas pelo SÃ­ndico com base nesta ConvenÃ§Ã£o e no Regimento Interno.', '', '09/11/2017'),
(26, 'ClÃ¡usula 11Âº', 'ParÃ¡grafo 1Âº', '', 'O pagamento das taxas de CondomÃ­nio deverÃ¡ ser feito mediante quitaÃ§Ã£o de boleto bancÃ¡rio previamente emitido pelo CondomÃ­nio, com antecedÃªncia mÃ­nima de 10(dez) dias, nÃ£o sendo admitido, em hipÃ³tese alguma, depÃ³sito em conta corrente do CondomÃ­nio, permanecendo em situaÃ§Ã£o de inadimplÃªncia o condÃ´mino que assim proceder, atÃ© quitaÃ§Ã£o dos acessÃ³rios de suas taxas condominiais;', '', '09/11/2017'),
(27, 'ClÃ¡usula 11Âº', 'ParÃ¡grafo 2Âº', '', 'Toda a receita do CondomÃ­nio deverÃ¡ ser depositada em uma Ãºnica conta bancaria e os pagamentos efetuados mediante cheques nominais, assinados pelo SÃ­ndico a quem cabe movimentÃ¡-la em conjunto com um dos membros do Conselho Fiscal.', '', '09/11/2017'),
(28, 'ClÃ¡usula 11Âº', 'ParÃ¡grafo 3Âº', '', 'As taxas condominiais nÃ£o pagas no respectivo vencimentos serÃ£o acrescidas de:\r\n<p>\r\na) Juros de mora de 0,33% ao dia:\r\n<p>\r\nb) Multa de 2% sobre o valor do dÃ©bito;\r\n<p>\r\nc) HonorÃ¡rios AdvocatÃ­cios, se extrajudicial 10% e se judicial 20%;\r\n<p>\r\nd) Despesas e custas judiciais e demais cominaÃ§Ãµes legais.\r\n<p>', '', '09/11/2017'),
(29, 'ClÃ¡usula 12Âº', '', '', 'Fica instituÃ­da um Fundo de Reserva permanente, destinado ao atendimento exclusivo das despesas extraordinÃ¡rias para cuja constituiÃ§Ã£o obriga-se cada condÃ´mino ao pagamento mensal correspondente a (5% cinco por cento) do valor da taxa normal, incorporada ao valor do rateio mensal, deixando este de ser cobrado quando o saldo atingir o limite de 100 (cento) salÃ¡rios mÃ­nimos regionais.', '', '09/11/2017'),
(30, 'ClÃ¡usula 12Âº', 'ParÃ¡grafo 1Âº', '', 'Para instituÃ­do um Fundo de Reserva permanente, destinado ao atendimento exclusivo das despesas extraordinÃ¡rias para cuja constituiÃ§Ã£o obriga-se cada condÃ´mino ao pagamento mensal correspondente a (5% por cinco por cento) do valor da taxa normal, incorporada ao valor do rateio mensal, deixando este de ser cobrado quando o saldo atingir o limite de l00(cento) salÃ¡rios mÃ­nimos regionais.', '', '09/11/2017'),
(31, 'ClÃ¡usula 12Âº', 'ParÃ¡grafo 2Âº', '', 'Para efeito desta clÃ¡usula consideram-se despesas extraordinÃ¡rias aquelas relacionadas com acrÃ©scimos e melhorias das instalaÃ§Ãµes, obras voluptuÃ¡rias, reparos de grande monta, pinturas das edificaÃ§Ãµes das Ã¡reas comuns, reposiÃ§Ã£o de equipamentos e sistemas de seguranÃ§a que, por acarretarem benefÃ­cios e valorizaÃ§Ã£o no patrimÃ´nio individual, sÃ£o de total responsabilidade do condÃ´mino proprietÃ¡rio embora incluÃ­das nas  taxas mensais do condomÃ­nio.', '', '09/11/2017'),
(32, 'ClÃ¡usula 13Âº', '', '', 'A movimentaÃ§Ã£o do saldo da conta Fundo de Reserva sÃ³ poderÃ¡ ser realizada, quando necessÃ¡rio, com a autorizaÃ§Ã£o, do Conselho Fiscal.', '', '09/11/2017'),
(33, 'ClÃ¡usula 14Âº', '', '', 'As quotas arrecadadas, a qualquer titulo, para o Fundo de Reserva serÃ£o mantidas em conta bancÃ¡ria conjunta solidÃ¡ria prÃ³pria para esse fim, sÃ³ podendo ser movimentada com autorizaÃ§Ã£o do Conselho Fiscal, com a assinatura do SÃ­ndico e de um dos membros do Conselho Fiscal.', '', '09/11/2017'),
(34, 'ClÃ¡usula 15Âº', '', '', 'Em caso emergencial ou por falta de fundos de caixa, o Sindico poderÃ¡ utilizar recursos do Fundo de Reserva para pagamento das despesas ordinÃ¡rias, obrigando-se a fazer sua reposiÃ§Ã£o posterior com os recursos provenientes de recebimento de taxas condominiais em atraso.', '', '09/11/2017'),
(35, 'ClÃ¡usula 15Âº', 'ParÃ¡grafo 1Âº', '', 'Em casos de obras de execuÃ§Ã£o inadiÃ¡vel, com riscos de danos Ã  integridade dos condÃ´minos, a seguranÃ§a do condomÃ­nio e das partes comuns e para as quais nÃ£o haja verba prÃ³pria, o SÃ­ndico poderÃ¡ recorrer ao Fundo de Reserva, com previa autorizaÃ§Ã£o  do Conselho Fiscal, atÃ© o montante de 10(dez) salÃ¡rios mÃ­nimos, convocando, em seguida o Conselho Administrativo ou a AssemblÃ©ia Geral para ratificar o seu ato e determinar as providencias cabÃ­veis Ã¡ reposiÃ§Ã£o.', '', '09/11/2017'),
(36, 'ClÃ¡usula 15Âº', 'ParÃ¡grafo 2Âº', '', 'As despesas extraordinÃ¡rias do condomÃ­nio somente poderÃ£o utilizar se do fundo de reserva com a aprovaÃ§Ã£o do conselho consultivo atÃ© o montante de 40(quarenta) salÃ¡rios mÃ­nimos, sendo que os valores superiores a esse teto deverÃ£o ser levados em assemblÃ©ia para aprovaÃ§Ã£o dos condÃ´minos.', '', '09/11/2017'),
(37, 'ClÃ¡usula 16Âº', '', '', 'As resoluÃ§Ãµes dos condÃ´minos serÃ£o sempre tomadas em AssemblÃ©ias Gerais OrdinÃ¡rias e ExtraordinÃ¡rias.', '', '09/11/2017'),
(38, 'ClÃ¡usula 16Âº', 'ParÃ¡grafo 1Âº', '', 'As AssemblÃ©ias Gerais OrdinÃ¡rias serÃ£o realizadas anualmente, atÃ© a primeira quinzena de marÃ§o, ou quando as circunstÃ¢ncias exigirem, convocadas pelo SÃ­ndico e nelas discutidos e decididos os seguintes assuntos:\r\n<p>\r\na) ApreciaÃ§Ã£o e aprovaÃ§Ã£o das contas do SÃ­ndico relativas ao exercÃ­cio findo;\r\n<p>\r\nb) ApreciaÃ§Ã£o do orÃ§amento para o exercÃ­cio subseqÃ¼ente;\r\n<p>\r\nc) EleiÃ§Ã£o de Sindico\r\n<p>', '', '09/11/2017'),
(39, 'ClÃ¡usula 16Âº', 'ParÃ¡grafo 2Âº', '', 'SerÃ£o eleitos, na AssemblÃ©ia Geral OrdinÃ¡ria, a cada biÃªnio:\r\n<p>\r\na) O SÃ­ndico, sendo-lhe fixada a remuneraÃ§Ã£o, de um salÃ¡rio mÃ­nimo por etapa entregue e Â½ (meio) pela Ã¡rea de lazer, ou convencionado por AssemblÃ©ia.\r\n<p>\r\nb) Membros Titulares dos Conselhos Fiscal e Administrativo, com direito a isenÃ§Ã£o da taxa de condomÃ­nio.\r\n<p>', '', '09/11/2017'),
(40, 'ClÃ¡usula 16Âº', 'ParÃ¡grafo 3Âº', '', 'As AssemblÃ©ias Gerais ExtraordinÃ¡rias serÃ£o realizadas sempre que houver interesse ou as circunstÃ¢ncias o exigirem, convocadas pelo SÃ­ndico, pelo Conselho Fiscal ou por solicitaÃ§Ã£o de Â¼ (Um quarto) dos condÃ´minos, sendo obrigatÃ³rio constar do edital de convocaÃ§Ã£o as matÃ©rias a serem discutidas e votadas (Art. 1350,Â§lÂ°doC.C).', '', '09/11/2017'),
(41, 'ClÃ¡usula 16Âº', 'ParÃ¡grafo 4Âº', '', 'NÃ£o poderÃ¡, em hipÃ³tese alguma, serem submetidos Ã  votaÃ§Ã£o quaisquer assuntos que nÃ£o constem expressamente no edital de convocaÃ§Ã£o.', '', '09/11/2017'),
(42, 'ClÃ¡usula 17Âº', '', '', 'As resoluÃ§Ãµes ou decisÃµes tomadas em AssemblÃ©ias, observados os quoruns previstos em lei na presente ConvenÃ§Ã£o, obrigam a todos o seu cumprimento, inclusive os discordantes e aqueles que nÃ£o tenham participado da reuniÃ£o.', '', '09/11/2017'),
(43, 'ClÃ¡usula 18Âº', '', '', 'As convocaÃ§Ãµes das AssemblÃ©ias serÃ£o feitas atravÃ©s de edital, entregue aos condÃ´minos atravÃ©s de carga registrada ou protocolada, remetidas ao endereÃ§o indicado pelo condÃ´mino, com antecedÃªncia mÃ­nima de 15(quinze) dias.', '', '09/11/2017'),
(44, 'ClÃ¡usula 19Âº', '', '', 'As convocaÃ§Ãµes deverÃ£o conter a pauta a ser discutida, o local data e horÃ¡rio para primeira e segunda convocaÃ§Ã£o, com intervalo mÃ­nimo de 30(trinta) minutos entre uma e outra, e deverÃ£o ser assinadas pelo SÃ­ndico ou pelos Membros do Conselho Fiscal', '', '09/11/2017'),
(45, 'ClÃ¡usula 20Âº', '', '', 'As AssemblÃ©ias serÃ£o instaladas em primeira convocaÃ§Ã£o com a presenÃ§a de representantes de 2/3 (dois terÃ§os) do total das unidades do condomÃ­nio e, em segunda convocaÃ§Ã£o, trinta minutos apÃ³s, com a presenÃ§a de qualquer numero de participantes', '', '09/11/2017'),
(46, 'ClÃ¡usula 20Âº', 'ParÃ¡grafo Ãšnico', '', 'A comprovaÃ§Ã£o da presenÃ§a Ã s assemblÃ©ias se. farÃ¡ atravÃ©s das assinaturas apostas no Livro de PresenÃ§a que ficarÃ¡ Ã  disposiÃ§Ã£o dos participantes durante todo o tempo de realizaÃ§Ã£o das mesmas e atÃ© o seu encerramento.', '', '09/11/2017'),
(47, 'ClÃ¡usula 21Âº', '', '', 'As AssemblÃ©ias serÃ£o presididas por um dos condÃ´minos presente, escolhido por aclamaÃ§Ã£o, que solicitarÃ¡ o auxilio de outra pessoa para secretariar os trabalhos e lavrar a ata respectiva, sendo defeso ao SÃ­ndico presidi-las.', '', '09/11/2017'),
(48, 'ClÃ¡usula 22Âº', '', '', 'Cada CondÃ´mino terÃ¡ direito a tantos votos quantas unidades autÃ´nomas lhes, pertenÃ§am documentalmente, computando-se um voto por cada unidade, independentemente do seu tipo ou dimensÃ£o.', '', '09/11/2017'),
(49, 'ClÃ¡usula 22Âº', 'ParÃ¡grafo 1Âº', '', 'Os resultados das votaÃ§Ãµes serÃ£o computados e calculados sobre o nÃºmero de presentes registrado no livro de presenÃ§as por todos assinados, observados os quoruns estabelecidos.', '', '09/11/2017'),
(50, 'ClÃ¡usula 22Âº', 'ParÃ¡grafo 2Âº', '', 'NÃ£o poderÃ¡ participar votar ou ser votado o condÃ´mino ou morador inadimplente com as taxas condominiais e encargos, permitida a sua regularizaÃ§Ã£o atÃ© 24:00 horas antes do horÃ¡rio de inicio das AssemblÃ©ias, devendo comprovar esta regularizaÃ§Ã£o antes de apor sua assinatura no livro de presenÃ§as.', '', '09/11/2017'),
(51, 'ClÃ¡usula 22Âº', 'ParÃ¡grafo 3Âº', '', 'Somente mediante procuraÃ§Ã£o especifica do proprietÃ¡rio Ã© que o inquilino poderÃ¡ votar em assuntos que envolvam aprovaÃ§Ã£o de despesas\r\nextraordinÃ¡rias, todavia poderÃ¡ votar em qualquer outro assunto em pauta, inclusive eleiÃ§Ã£o do Sindico desde que o condÃ´mino nÃ£o esteja presente ou representado.', '', '09/11/2017'),
(52, 'ClÃ¡usula 23Âº', '', '', 'Na hipÃ³tese de uma unidade pertencer a mais de um condÃ´mino, os mesmo deverÃ£o eleger quem os representarÃ¡ nas assemblÃ©ias, credenciando-o por escritor falta da indicaÃ§Ã£o aqui prevista, um sÃ³ dos proprietÃ¡rios exercerÃ¡ o direito de voto observada a ordem de chegada Ã  ordem de chegada Ã  AssemblÃ©ia pelo registro no livro de presenÃ§as.', '', '09/11/2017'),
(53, 'ClÃ¡usula 23Âº', 'ParÃ¡grafo Ãšnico', '', 'Ã‰ facultado a qualquer condÃ´mino fazer-se representar por procurador formalmente constituÃ­do, condÃ´mino ou nÃ£o, com os poderes especÃ­ficos para a assemblÃ©ia.', '', '09/11/2017'),
(54, 'ClÃ¡usula 24Âº', '', '', '0 SÃ­ndico, na primeira correspondÃªncia a ser encaminhada apÃ³s a assemblÃ©ia, desde que nÃ£o ultrapasse 30(trinta) dias da realizaÃ§Ã£o da assemblÃ©ia, comunicarÃ¡ aos condÃ´minos o que tiver sido deliberado, inclusive no tocante Ã  previsÃ£o orÃ§amentÃ¡ria e rateio das despesas, promovendo a arrecadaÃ§Ã£o na forma que a assemblÃ©ia houver determinado.', '', '09/11/2017'),
(55, 'ClÃ¡usula 24Âº', 'ParÃ¡grafo 1Âº', '', 'Dos atos e decisÃµes do SÃ­ndico e do Conselho Consultivo ou Administrativo em prejuÃ­zo de um condÃ´mino, cabe recurso Ã  AssemblÃ©ia Geral solicitada pelo prejudicado e obrigatoriamente convocada pelo SÃ­ndico e/ou pelo Conselho Fiscal.', '', '09/11/2017'),
(56, 'ClÃ¡usula 24Âº', 'ParÃ¡grafo 2Âº', '', 'Quando o recurso Ã  assemblÃ©ia tiver como assunto a discussÃ£o de problema de questionamento de valores de multas e taxas condominiais nÃ£o terÃ¡ efeitos suspensivos, devendo o interessado efetuar necessariamente o pagamento do valor questionado para poder apresentar recurso.', '', '09/11/2017'),
(57, 'ClÃ¡usula 25Âº', '', '', 'SerÃ¡ exigida maioria qualificada nos seguintes casos:\r\n<p>\r\n1) Unanimidade dos proprietÃ¡rios (Lei 4.591/64)\r\n<p>\r\n<ul\r\n<li>1.1) Para aprovar modificaÃ§Ãµes na estrutura e na arquitetura das fachadas;</li>\r\n</ul>\r\n<p>\r\n2) 2/3 (Dois terÃ§os) de votos vÃ¡lidos:\r\n<p>\r\n<ul>\r\n<li>2.1) DestituiÃ§Ã£o do SÃ­ndico, sem motivo justificado;</li>\r\n</ul>\r\n<p>\r\n2.2) AlteraÃ§Ã£o da ConvenÃ§Ã£o de CondomÃ­nio e demais situaÃ§Ãµes previstas no Art.1.351 do CC;\r\n<p>\r\n3) Maioria simples dos presentes Ã s assemblÃ©ias:\r\n<p>\r\n<ul>\r\n<li>3.1) Nos demais casos. (Esta clÃ¡usula e seus itens ficam submetidos ao disposto no Art. 1354 do CC).</li>\r\n</ul>', '', '09/11/2017'),
(58, 'ClÃ¡usula 26Âº', '', '', 'O SÃ­ndico poderÃ¡ ser destituÃ­do pela assemblÃ©ia especialmente convocada, por maioria simples de votos, nos seguintes casos:\r\n<p>\r\na) Agir com negligencia, deixando de exercer quaisquer de suas atribuiÃ§Ãµes ou funÃ§Ãµes;\r\n<p>\r\nb) Causar danos ao CondomÃ­nio ou a qualquer dos condÃ´minos;\r\n<p>\r\nc) Portar-se de maneira inconveniente ou prejudicial ao CondomÃ­nio, perante terceiros;\r\n<p>\r\nd) Infringir ou contribuir para infraÃ§Ã£o de dispositivo da Lei, desta ConvenÃ§Ã£o ou do Regimento Interno.', '', '09/11/2017'),
(59, 'ClÃ¡usula 26Âº', 'ParÃ¡grafo Ãšnico', '', 'Mesmo sem qualquer justificativa o SÃ­ndico poderÃ¡ ser destituÃ­do em assemblÃ©ia especialmente convocada, pelos votos vÃ¡lidos de maioria absoluta do total de condÃ´minos, devendo os mesmos estar adimplentes com suas obrigaÃ§Ãµes (Art. 1354 do CC).', '', '09/11/2017'),
(60, 'ClÃ¡usula 27Âº', '', '', 'Das assemblÃ©ias gerais sÃ£o digitadas atas e coladas em livro prÃ³prio aberto, rubricado e encerrado pelo SÃ­ndico, assinadas pelo Presidente e o SecretÃ¡rio, sendo lÃ­cito aos participantes fazer constar as suas declaraÃ§Ãµes de votos, quando discordantes e antes do seu encerramento.', '', '09/11/2017'),
(61, 'ClÃ¡usula 27Âº', 'ParÃ¡grafo Ãšnico', '', 'O Presidente poderÃ¡ declarar a assemblÃ©ia em carÃ¡ter permanente caso existia matÃ©ria pendente de votaÃ§Ã£o e para a qual nÃ£o haja o quorum necessÃ¡rio Ã¡ sua aprovaÃ§Ã£o ou outras matÃ©rias que exijam motivos justificados, voltando a reabri-la no dia em que for determinado em alta quando, entÃ£o, a mesma serÃ¡ encerrada registrando-se o seu resultado.', '', '09/11/2017'),
(62, 'ClÃ¡usula 28Âº', '', '', 'A administraÃ§Ã£o do CondomÃ­nio serÃ¡ exercida por um SÃ­ndico (condÃ´mino proprietÃ¡rio adimplente) eleito em AssemblÃ©ia Geral OrdinÃ¡ria para uma gestÃ£o de 02(dois) anos, permitida a reeleiÃ§Ã£o (Art. 1347, do CC).', '', '09/11/2017'),
(63, 'ClÃ¡usula 28Âº', 'ParÃ¡grafo 1Âº', '', 'Quando da eleiÃ§Ã£o do SÃ­ndico, serÃ£o eleitos, os membros do Conselho Fiscal e os membros do Conselho de AdministraÃ§Ã£o, observado o mesmo prazo do mandato do SÃ­ndico, necessÃ¡rio, porÃ©m, que os conselheiros sejam condÃ´minos proprietÃ¡rios adimplentes', '', '09/11/2017'),
(64, 'ClÃ¡usula 28Âº', 'ParÃ¡grafo 2Âº', '', 'O SÃ­ndico eleito poderÃ¡ delegar as funÃ§Ãµes administrativas Ã  pessoa jurÃ­dica idÃ´nea sob sua inteira e exclusiva responsabilidade (Art. 1348 Â§ do CC).', '', '09/11/2017'),
(65, 'ClÃ¡usula 29Âº', '', '', 'Um dos Conselheiros integrantes do Conselho Fiscal assumirÃ¡ provisoriamente a administraÃ§Ã£o do CondomÃ­nio nos casos de afastamento temporÃ¡rio do SÃ­ndico; no caso de afastamento definitivo, serÃ¡ convocada uma AssemblÃ©ia Geral no prazo de 30(trinta) dias para referendar sua permanÃªncia no cargo  ou eleiÃ§Ã£o de novo SÃ­ndico para cumprir o restante do mandato.', '', '09/11/2017'),
(66, 'ClÃ¡usula 29Âº', 'ParÃ¡grafo 1Âº', '', 'O afastamento do SÃ­ndico, por vontade prÃ³pria e desde que nÃ£o esteja sendo questionada a sua gestÃ£o, serÃ¡ oficializada via comunicaÃ§Ã£o dirigida ao Conselho Fiscal, obrigando-se este Ã  prestaÃ§Ã£o de contas, encaminhando Ã  assemblÃ©ia para a devida aprovaÃ§Ã£o.', '', '09/11/2017'),
(67, 'ClÃ¡usula 29Âº', 'ParÃ¡grafo 2Âº', '', 'No caso de destituiÃ§Ã£o do SÃ­ndico ou da falta de prestaÃ§Ã£o de contas, ficarÃ¡ o mesmo impedido de exercer novamente o cargo ou participar da administraÃ§Ã£o do condomÃ­nio.', '', '09/11/2017'),
(68, 'ClÃ¡usula 30Âº', '', '', 'A remuneraÃ§Ã£o do sindico ou do Administrador serÃ¡ fixada na AssemblÃ©ia que o eleger, podendo esta ser fixada em moeda corrente.', '', '09/11/2017'),
(69, 'ClÃ¡usula 30Âº', 'ParÃ¡grafo Ãšnico', '', 'NÃ£o hÃ¡ qualquer vÃ­nculo empregatÃ­cio do CondomÃ­nio com SÃ­ndico, os membros do Conselho Fiscal e Administrativo.', '', '09/11/2017'),
(70, 'ClÃ¡usula 31Âº', '', '', 'O SÃ­ndico nÃ£o poderÃ¡ ser responsabilizado pessoalmente pelas obrigaÃ§Ãµes assumidas em nome do CondomÃ­nio, desde que tenha agido no exercÃ­cio regular de suas atribuiÃ§Ãµes.', '', '09/11/2017'),
(71, 'ClÃ¡usula 32Âº', '', '', 'Compete ao SÃ­ndico:\r\n<p>\r\n1) Administrar o CondomÃ­nio dentro das exigÃªncias legais;\r\n<p>\r\n2) Representar o CondomÃ­nio, ativa e passivamente, em juÃ­zo ou fora dele, cumprir e fazer cumprir a lei, a presente ConvenÃ§Ã£o, o Regimento Interno, bem como as deliberaÃ§Ãµes das assemblÃ©ias;\r\n<p>\r\n3) Admitir e dispensar funcionÃ¡rios e empregados;\r\n<p>\r\n4) Contratar com firmas especializadas os serviÃ§os de administraÃ§Ã£o terceirizada do CondomÃ­nio, desde que autorizado pela AssemblÃ©ia;\r\n<p>\r\n5) Prestar a qualquer condÃ´mino ou morador, a qualquer tempo e quando solicitado, mediante registro no livro de OcorrÃªncias, informaÃ§Ãµes sobre os atos da administraÃ§Ã£o e submeter ao Conselho Fiscal,  mensalmente, os balancetes respectivos para analise e aprovaÃ§Ã£o;\r\n<p>\r\n6) Prestar Ã  assemblÃ©ia anual contas de sua gestÃ£o com todos os balancetes mensais analisados e aprovados pelo Conselho Fiscal; \r\n<p>\r\n7) Cobrar em juÃ­zo, apÃ³s o vencimento da 3 (terceira) taxa em atraso, Ã¡s despesas do CondomÃ­nio nÃ£o pagas, bem como as multas e encargos por infraÃ§Ã£o de dispositivos legais ou previstos nesta ConvenÃ§Ã£o e no Regimento Interno; \r\n<p>\r\n8) Entregar ao seu sucessor todos os livros, documentos e pertences do CondomÃ­nio; \r\n<p>\r\n9) Manter atualizado o Livro de PatrimÃ´nio do CondomÃ­nio;\r\n<p>\r\n10) Comunicar aos Conselhos Fiscal e Administrativo as citaÃ§Ãµes judiciais ou administrativas que receber;\r\n<p>\r\n11) Convocar assemblÃ©ias gerais ordinÃ¡rias ou extraordinÃ¡rias, estas quando necessÃ¡rias, e nos casos de interposiÃ§Ã£o de recurso, conforme dispÃµe a presente ConvenÃ§Ã£o;\r\n<p>\r\n12) Elaborar o plano orÃ§amentÃ¡rio anual com todas as previsÃµes de investimentos, a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria \r\n<p>', '', '09/11/2017'),
(72, 'ClÃ¡usula 33Âº', '', '', 'Quando da eleiÃ§Ã£o do SÃ­ndico, serÃ£o eleitos na mesma assemblÃ©ia, 03 (trÃªs) suplementos do Conselho Fiscal que deverÃ£o ser, obrigatoriamente, condÃ´minos proprietÃ¡rios adimplentes, com mandato idÃªntico ao do SÃ­ndico.', '', '09/11/2017'),
(73, 'ClÃ¡usula 33Âº', 'ParÃ¡grafo Ãšnico', '', 'Os membros eleitos entre si indicarÃ£o o Presidente', '', '09/11/2017'),
(74, 'ClÃ¡usula 34Âº', '', '', 'Os membros do Conselho Fiscal, farÃ£o jus Ã  isenÃ§Ã£o da taxa de condomÃ­nio ordinÃ¡ria de uma unidade autÃ´noma', '', '09/11/2017'),
(75, 'ClÃ¡usula 34Âº', 'ParÃ¡grafo Ãšnico', '', 'Os membros do Conselho Fiscal que tiveram mais de 3(trÃªs) faltas nas reuniÃµes do mesmo, serÃ£o excluÃ­dos, assumindo em seu lugar o primeiro suplente eleito.', '', '09/11/2017'),
(76, 'ClÃ¡usula 35Âº', '', '', 'Compete ao Conselho Fiscal:\r\n<p>\r\n1) Apreciar o Plano orÃ§amentÃ¡rio anual, com todas as previsÃµes de investimentos; a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria, em conjunto com o sÃ­ndico e Conselho Administrativo;\r\n<p>\r\n2) Assessorar o SÃ­ndico na soluÃ§Ã£o dos problemas nÃ£o rotineiros e nos casos de urgÃªncia;\r\n<p>\r\n3) Opinar quanto aos assuntos pessoais que envolvam o relacionamento do SÃ­ndico e os condÃ´minos;\r\n<p>\r\n4) Dar parecer sobre matÃ©ria relativa Ã  realizaÃ§Ã£o de despesas extraordinÃ¡rias;\r\n<p>\r\n5) Fiscalizar as atividades do SÃ­ndico, examinar suas contas mensais, relatÃ³rios, comprovantes e documentos, emitindo parecer conclusivo, por escrito, aprovando ou rejeitando-as Ã  discussÃ£o da assemblÃ©ia de condÃ´minos;\r\n<p>\r\n6) Pronunciar-se com brevidade sobre todas as consultas ou pedidos de assessoramento que lhe sejam encaminhados pelo SÃ­ndico ou condÃ´minos.', '', '09/11/2017'),
(77, 'ClÃ¡usula 36Âº', '', '', 'Todos os pronunciamentos dos Conselhos Fiscais deverÃ£o ser feitos, por escrito, no Livro de Atas do CondomÃ­nio', '', '09/11/2017'),
(78, 'ClÃ¡usula 37Âº', '', '', 'Um dos membros do Conselho Fiscal serÃ¡ escolhido seu Presidente, com atribuiÃ§Ãµes de coordenaÃ§Ã£o e direÃ§Ã£o de suas atividades, cabendo ao mesmo o relacionamento administrativo oficial com o SÃ­ndico.', '', '09/11/2017'),
(79, 'ClÃ¡usula 37Âº', 'ParÃ¡grafo 1Âº', '', 'O Conselho Fiscal reunir-se-Ã¡ mensalmente em data a ser determinada pelos seus integrantes, em pauta mÃ­nima de anÃ¡lise de balancete mensal a ser entregue pelo SÃ­ndico, devendo este ser aprovado com as assinaturas de, pelo menos, dois de seus membros titulares, nÃ£o sendo reconhecido o voto de abstenÃ§Ã£o.', '', '09/11/2017'),
(80, 'ClÃ¡usula 37Âº', 'ParÃ¡grafo 2Âº', '', 'Ã‰ permitida ao Conselho Fiscal a contrataÃ§Ã£o de assessoria tÃ©cnica para proceder auditoria nas contas do condomÃ­nio, quando julgar necessÃ¡rio.', '', '09/11/2017'),
(81, 'ClÃ¡usula 38Âº', '', '', 'Quando da eleiÃ§Ã£o do SÃ­ndico serÃ£o eleitos na mesma AssemblÃ©ia, 06 (seis) membros do Conselho Administrativo, sendo obrigatoriamente, um proprietÃ¡rio de cada etapa, com mandato idÃªntico ao do Sindico.', '', '09/11/2017'),
(82, 'ClÃ¡usula 39Âº', '', '', 'Os membros do Conselho Administrativo farÃ£o jus Ã  isenÃ§Ã£o da taxa de condomÃ­nio da unidade autÃ´noma onde reside.', '', '09/11/2017'),
(83, 'ClÃ¡usula 40Âº', '', '', 'Compete ao Conselho Administrativo:\r\n<p>\r\n1) Elaborar o plano orÃ§amentÃ¡rio anual, com toda as previsÃµes de investimento limitando a aplicaÃ§Ã£o dos recursos ao valor arrecado em cada etapa: a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria, em conjunto com o sÃ­ndico e Conselho Fiscal;\r\n<p>\r\n2) Aprovar as despesas extraordinÃ¡rias atÃ© o limite de 40 (quarenta) salÃ¡rios mÃ­nimos.\r\n<p>\r\n3) Assessorar o SÃ­ndico na soluÃ§Ã£o dos problemas nÃ£o rotineiros e nos casos urgÃªncia;\r\n<p>\r\n4) Opinar quanto aos assuntos pessoais que envolvam o relacionamento do SÃ­ndico e os condÃ´minos.\r\n<p>\r\n5) Estar presente em todas as assemblÃ©ias e reuniÃµes do CondomÃ­nio.', '', '09/11/2017'),
(84, 'ClÃ¡usula 41Âº', '', '', 'Todos os pronunciamentos do Conselho Administrativo deverÃ£o ser feitos, por escrito, no Livro de Atas do CondomÃ­nio.', '', '09/11/2017'),
(85, 'ClÃ¡usula 41Âº', 'ParÃ¡grafo Ãšnico', '', 'O Conselho Administrativo reunir-se-Ã¡ mensalmente, em data a ser determinada pelos seus integrantes, em conjunto com o conselho fiscal e o sÃ­ndico para deliberaram e acompanharem a execuÃ§Ã£o do plano orÃ§amentÃ¡rio aprovado em AssemblÃ©ia.', '', '09/11/2017'),
(86, 'ClÃ¡usula 42Âº', '', '', 'O SÃ­ndico contratarÃ¡ o seguro do CondomÃ­nio e das unidades autÃ´nomas contra incÃªndio ou quaisquer outros riscos que os possam destruir, total ou parcialmente, de acordo com o artigo 13 parÃ¡grafo Ãºnico da Lei 4.591/64.', '', '09/11/2017'),
(87, 'ClÃ¡usula 42Âº', 'ParÃ¡grafo Ãšnico', '', 'Ã‰ facultado aos condÃ´minos aumentar, por sua conta, o seguro referente Ã s suas unidades e seus pertences pessoais.', '', '09/11/2017'),
(88, 'ClÃ¡usula 43Âº', '', '', 'Na hipÃ³tese da ocorrÃªncia de sinistro previsto neste capitulo aplicar-se-Ã¡ integralmente o disposto na Lei 4.591/64, em seus artigos 13 (treze) a 18 (Dezoito) que, independentemente de sua transcriÃ§Ã£o nesta ConvenÃ§Ã£o, torna obrigatÃ³ria a sua aplicaÃ§Ã£o e cumprimento em todos os seus termos.', '', '09/11/2017'),
(89, 'ClÃ¡usula 44Âº', '', '', 'O uso da Ã¡rea de lazer Ã© privativo dos proprietÃ¡rios, seus familiares, moradores, seus dependentes e convidados, diariamente, exceto nos dias em que, por determinaÃ§Ã£o do SÃ­ndico, sejam interditadas para realizaÃ§Ã£o de limpeza e manutenÃ§Ã£o.', '', '09/11/2017'),
(90, 'ClÃ¡usula 45Âº', '', '', 'Para adentrar Ã  Ã¡rea de lazer, os convidados de condÃ´minos deverÃ£o portar autorizaÃ§Ã£o e/ou convite que funcionarÃ¡ como controle para a evitar estranhos nas dependÃªncias do condomÃ­nio.', '', '09/11/2017'),
(91, 'ClÃ¡usula 45Âº', 'ParÃ¡grafo Ãšnico', '', 'As normas de funcionamento do clube constarÃ£o de Regimento Interno a ser elabora pelo SÃ­ndico, Conselho Fiscal e Conselho Administrativo, posteriormente serÃ¡ aprovado pela AssemblÃ©ia Geral.', '', '09/11/2017'),
(92, 'ClÃ¡usula 46Âº', '', '', 'O uso das piscinas fica sujeito Ã  obediÃªncia das seguintes normas:\r\n<p>\r\n1) O usuÃ¡rio deverÃ¡ passar obrigatoriamente por um banho de ducha antes de entrar na piscina;\r\n<p>\r\n2) NÃ£o serÃ¡ permitida a entrada nas piscinas de pessoas usando lenÃ§os, esparadrapos, gazes Ãª curativos de qualquer espÃ©cie, exigindo-se mÃ©dico para prevenÃ§Ã£o da ocorrÃªncia de qualquer tipo de molÃ©stia:\r\n<p>\r\n3) NÃ£o sÃ£o permitidas brincadeiras perigosas nas Ã¡reas das piscinas e que possam causar danos ou ferimentos;\r\n4) Proibido banhar-se com a pele e cabelos impregnados de cremes, Ã³leos ou bronzeadores, tolerado apenas o filtro solar nÃ£o oleoso;\r\n<p>\r\n5) SÃ³ freqÃ¼entar as piscinas em trajes complexo e adequado ao banho, guardando o devido decoro;\r\n<p>\r\n6) Vedada a presenÃ§a de quaÃ­squer animais nas Ã¡reas das piscinas;\r\n<p>\r\n7) Os pais serÃ£o os responsÃ¡veis pela seguranÃ§a e orientaÃ§Ã£o de seus filhos;\r\n<p>\r\n8) Quando da interdiÃ§Ã£o das piscinas para limpeza e manutenÃ§Ã£o, nenhuma pessoa poderÃ¡ utilizÃ¡-las, podendo o Sindico alterar o dia destinado a este serviÃ§o, caso convenha Ã  administraÃ§Ã£o;\r\n<p>\r\n9) A utilizaÃ§Ã£o individual de aparelho de som movida a bateria prÃ³pria em veÃ­culos ou uso de instrumentos musicais, deverÃ¡ observar um nÃ­vel de ruÃ­do compatÃ­vel com os padrÃµes normais e que nÃ£o incomode nÃ£o sÃ³ os freqÃ¼entadores das Ã¡reas das piscinas como os moradores que estejam em suas casas.\r\n', '', '09/11/2017'),
(93, 'ClÃ¡usula 47Âº', '', '', 'SÃ£o direitos dos condÃ´minos e moradores:\r\n<p>\r\n1) Usar, fruir e dispor das respectivas unidades autÃ´nomas de acordo com a sua destinaÃ§Ã£o, sem prejudicar a solidez e seguranÃ§a do condomÃ­nio e das unidades vizinhas, sem prejudicar a solidez e seguranÃ§a do condomÃ­nio e das unidades vizinhas, sem infringir as normas legais da presente ConvenÃ§Ã£o, bem como sem causar danos ou incÃ´modos aos demais condÃ´minos;\r\n<p>\r\n2) Usar e fruir normalmente das partes comuns do CondomÃ­nio, desde que nÃ£o impeÃ§am idÃªntico uso e fruiÃ§Ã£o por parte dos demais condÃ´minos, observadas as restriÃ§Ãµes da presente convenÃ§Ã£o;\r\n<p>\r\n3) Examinar a qualquer tempo, os livros e documentos da administraÃ§Ã£o e solicitar esclarecimentos ao SÃ­ndico, quando julgar necessÃ¡rio;\r\n<p>\r\n4) Utilizar os serviÃ§os de portaria desde que nÃ£o perturbem a sua ordem nem desviem os empregados para serviÃ§os particulares e/ou internos de suas unidades autÃ´nomas;\r\n<p>\r\n5) Denunciar ao SÃ­ndico, por escrito e no livro deixado Ã  disposiÃ§Ã£o na portaria, com identificaÃ§Ã£o do reclamante e do numero de sua unidade, qualquer irregularidade que observe;\r\n<p>\r\n6) Comparecer Ã s AssemblÃ©ias, podendo nelas participar, votar e ser votado desde que estejam quites com as taxas condominiais e demais encargos;\r\n<p>', '', '09/11/2017'),
(94, 'ClÃ¡usula 48Âº', '', '', 'SÃ£o deveres dos condÃ´minos e moradores:\r\n<p>\r\n1) Guardar o decoro e respeito no uso das coisas e partes comuns do CondomÃ­nio, bem como em sua prÃ³pria unidade privativa, nÃ£o as usando e nem permitindo que as usem, para fins diversos daqueles a que se destinam;\r\n<p>\r\n2) NÃ£o usar as respectivas unidades autÃ´nomas, nem alugÃ¡-las ou cedÃª-las para atividades ruidosas ou para instalaÃ§Ã£o de qualquer atividade comercial ou industrial ou deposito de qualquer objeto ou material capaz de causar unidades ou incomodo aos demais condÃ´minos e usuÃ¡rios;\r\n<p>\r\n3) NÃ£o colocar lixo, detritos e outros rejeitos nas Ã¡reas comuns acomodando-os em sacolas plÃ¡sticas, tantas vezes quantas necessÃ¡rias, fechando-as e colocando-as nos depÃ³sitos prÃ³prios para serem recolhidas;\r\n<p>\r\n4) NÃ£o utilizar nenhum empregado do CondomÃ­nio para serviÃ§os particularidades de qualquer natureza em seu horÃ¡rio normal de trabalho;\r\n<p>\r\n5) NÃ£o manter nas respectivas unidades autÃ´nomas aparelhos, instalaÃ§Ãµes ou substancias que causem perigo Ã  seguranÃ§a e a solidez do conjunto ou incÃ´modos aos demais condÃ´minos e moradores;\r\n<p>\r\n6) NÃ£o fracionar a respectiva unidade autÃ´noma com fins de alienÃ¡-la e/ou colocÃ¡-la a mais de uma pessoa, separadamente;\r\n7) Contribuir para todas as despesas ordinÃ¡rias e extraordinÃ¡rias do CondomÃ­nio, efetuando o correspondente pagamento das taxas nos prazos determinados, obedecidas Ã s proporÃ§Ãµes de suas respectivas fraÃ§Ãµes ideais;\r\n<p>\r\n8) Contribuir para o custeio e realizaÃ§Ã£o de obras autorizadas em AssemblÃ©ia, bem como das obras ou aquisiÃ§Ãµes de carÃ¡ter urgente realizados pelo SÃ­ndico devidamente aprovado pelo Conselho Fiscal e obedecida, sempre, as proporÃ§Ãµes das fraÃ§Ãµes ideais de cada um, com a ressalva da alÃ­nea anterior;\r\n<p>\r\n9) Permitir o ingresso em sua autÃ´noma do SÃ­ndico ou preposto, quando isto se torne indispensÃ¡vel Ã  realizaÃ§Ã£o de trabalhos relativos Ã  estrutura das casas, sua seguranÃ§a e solidez ou a inadiÃ¡vel realizaÃ§Ã£o de reparos em instalaÃ§Ãµes e tubulaÃ§Ãµes que apresentam problemas ao conjunto ao a alguma unidade vizinha quando, embora notificado, nÃ£o os execute;\r\n<p>\r\n10) NÃ£o manter nas Ã¡reas comuns do CondomÃ­nio, em hipÃ³tese alguma, quaisquer animais, sendo tolerados, todavia, animais domÃ©sticos de pequeno porte e de raÃ§as nÃ£o ferozes que nÃ£o atentem contra a saÃºde, higiene, sossego, tranqÃ¼ilidade e seguranÃ§a dos moradores, devendo ser conduzido pelos seus donos com coleira apropriada atÃ© atingir a via pÃºblica e vice-versa.\r\n<p>\r\n11) Os donos desses animais de permanÃªncia tolerada deverÃ£o acondicionar os excrementos dos mesmos em sacolas de lixo devidamente lacrado e conduzi-las atÃ© o deposito ou container da via pÃºblica e lÃ¡ depositÃ¡-los;\r\n<p> \r\n12) Comunicar imediatamente ao SÃ­ndico a ocorrÃªncia de molÃ©stia infecto-contagiosas, ou epidÃªmicas em sua unidade autÃ´noma;\r\n13) NÃ£o provocar ou permitir que se faÃ§a barulho excessivo a qualquer hora do dia ou da noite, com instrumentos, pianos, rÃ¡dios, vitrolas, alto-falantes. Outros aparelhos e equipamentos usados na construÃ§Ã£o civil sÃ³ poderÃ£o ser usados nos horÃ¡rios permitidos e regulamentados por esta ConvenÃ§Ã£o e ou Regimento Interno;\r\n<p>\r\n14) Respeitar o horÃ¡rio de silencio absoluto no perÃ­odo das 22:00 Ã¡s 08:00 horas exceto na Ã¡rea do clube, nÃ£o sendo tolerado mesmo durante o dia barulho em excesso, bem como a queima de fogos de artifÃ­cio;\r\n<p>\r\n15) Para realizaÃ§Ã£o de reformas em sua unidade o condÃ´mino deverÃ¡ notificar a administraÃ§Ã£o do condomÃ­nio da natureza dos serviÃ§os que pretenda realizar, obedecendo aos horÃ¡rios para a reforma determinada pelo sindico, e informando Ã  administraÃ§Ã£o o nome dos funcionÃ¡rios e responsÃ¡veis pela obra, nÃ£o permitida a realizaÃ§Ã£o de qualquer serviÃ§o ao domingos e feriada: \r\n<p>\r\n16) Os entulhos e restos de obras deverÃ£o ser conveniente e devidamente colocado em sacos resistentes, transportados Ã  via pÃºblica, nÃ£o podendo permanecer no recinto do condomÃ­nio, exigindo-se, tambÃ©m, a limpeza do caminho percorrido pelos entulhos;\r\n<p>\r\n17) O CondÃ´mino serÃ¡ responsÃ¡vel por qualquer dano que provocar nas partes comuns do CondomÃ­nio provocados por problemas em sua unidade privativa, sendo obrigado a indenizar os prejuÃ­zos e pagar as multas estipuladas nesta ConvenÃ§Ã£o e no Regimento Interno, quando tal fato ocorrer;\r\n<p>\r\n18) O CondÃ´mino tambÃ©m Ã© responsÃ¡vel pelo reparo de quaisquer danos em sua unidade privativa, bem como nas unidades vizinhas, quando os danos tenham origem em sua unidade, ficando obrigado a reparÃ¡-los quando deles tomar conhecimento ou  for cientificado pelo SÃ­ndico, cabendo ao CondomÃ­nio o reparo dos danos provenientes de defeitos nas Ã¡reas e instalaÃ§Ãµes de uso comum e nas linhas tronco e nas prumadas de serviÃ§os;\r\n<p>\r\n19) O condÃ´mino deverÃ¡ comunicar imediatamente Ã  administraÃ§Ã£o os casos de venda transferÃªncia ou locaÃ§Ã£o de sua unidade;\r\n<p>\r\n20) O entulho e/ou lixo acumulado dentro da unidade autÃ´noma (Ã¡rea privativa), devera ser retirado pelo condÃ´mino no prazo MÃ¡ximo de 30 (trinta dias), mantendo-a limpa e conservada.\r\n<p>', '', '09/11/2017'),
(95, 'ClÃ¡usula 49Âº', '', '', 'O CondÃ´mino que nÃ£o efetuar o pagamento de suas taxas de condomÃ­nio atÃ© o dia do vencimento se sujeita a uma multa de 2% sobre o dÃ©bito mais juros moratÃ³rios de 0,33 % ao dia, nos termos do Art. 1336 Â§ l e 2 do CC, perdendo, ainda, o direito de votar e ser votado em assemblÃ©ias, enquanto perdurar a sua inadimplÃªncia.', '', '09/11/2017'),
(96, 'ClÃ¡usula 49Âº', 'ParÃ¡grafo 1Âº', '', 'O CondÃ´mino que violar qualquer disposiÃ§Ã£o legal, bem como as contidas na presente ConvenÃ§Ã£o e no Regimento Interno serÃ¡ obrigado a reparar os danos que causar e sujeitar-se-Ã¡ a uma multa de 5(cinco) vezes o valor da taxa condominial, independente das perdas e danos que se apurarem (Art. 1336 Â§ 2Â° do CC).', '', '09/11/2017'),
(97, 'ClÃ¡usula 49Âº', 'ParÃ¡grafo 2Âº', '', 'Aplicar-se-Ã¡ tambÃ©m multa por comportamento anti-social, que gere incompatibilidade de convivÃªncia com os demais condÃ´minos ou possuidores, sendo esta multa correspondente ao dÃ©cuplo do valor  atribuÃ­do Ã  taxa condominial atÃ© ulterior deliberaÃ§Ã£o da AssemblÃ©ia, aplicando-se o mesmo critÃ©rio previsto no caput deste artigo 1337 do CC, Â§ (Ãšnico).', '', '09/11/2017'),
(98, 'ClÃ¡usula 49Âº', 'ParÃ¡grafo 3Âº', '', 'ApÃ³s 3 (trÃªs) meses de atraso sem sua quitaÃ§Ã£o, o SÃ­ndico encaminharÃ¡ para cobranÃ§a judicial os boletos devidamente acrescidos dos encargos devidos.', '', '09/11/2017'),
(99, 'ClÃ¡usula 49Âº', 'ParÃ¡grafo 4Âº', '', 'O SÃ­ndico somente poderÃ¡ negociar multas e juros por atraso no pagamento das taxas condominiais ou outras quaisquer obrigaÃ§Ãµes pecuniÃ¡rias com a aprovaÃ§Ã£o do Conselho Fiscal, sob pena dÃª ser obrigado a ressarcir ao CondomÃ­nio os valores que tiver liberado, salvo em negociaÃ§Ã£o feita em juÃ­zo.', '', '09/11/2017'),
(100, 'ClÃ¡usula 50Âº', '', '', 'Os ocupantes das unidades autÃ´nomas que transitÃ³rio ou eventualmente\r\nperturbar o uso das coisas comuns ou der origem a despesas e prejuÃ­zos ao condomÃ­nio, bem como no caso de desrespeito Ã  presente convenÃ§Ã£o ou ao Regimento Interno, sujeitam o proprietÃ¡rio Ã  multa inicial prevista no parÃ¡grafo primeiro da ClÃ¡usula 49, inserida juntamente com o boleto da taxa de condomÃ­nio, cobrada em dobro no caso de reincidÃªncia, sem prejuÃ­zo das demais conseqÃ¼Ãªncias cÃ­veis e criminais que advir do ato praticado. ', '', '09/11/2017'),
(101, 'ClÃ¡usula 51Âº', '', '', 'O CondomÃ­nio nÃ£o serÃ¡, em hipÃ³tese alguma, responsabilizado por quaisquer danos materiais ou pessoais decorrentes de acidentes, furtos, roubos ou extravio de objetos, veÃ­culos, acessÃ³rios ou quaisquer outros bens que permaneÃ§am nas garagens, nas Ã¡reas privativas e comuns ou dentro dos veÃ­culos ou interiores das unidades, renunciando os condÃ´minos, moradores, inquilinos, empregados ou visitantes, expressamente a qualquer tipo de reclamaÃ§Ã£o, aÃ§Ã£o ou indenizaÃ§Ã£o civil, ,inclusive por acidentes pessoais ocorridos nas dependÃªncias do CondomÃ­nio, salvo se comprovada a responsabilidade do CondomÃ­nio.', '', '09/11/2017'),
(102, 'ClÃ¡usula 52Âº', '', '', 'Ficam isentas do pagamento da taxa de condomÃ­nio as unidades nÃ£o comercializadas de propriedade da empresa incorporadora, permanecendo esta responsÃ¡vel pela manutenÃ§Ã£o destas unidades, nÃ£o podendo, em hipÃ³tese alguma, repassar ao condomÃ­nio despesas relativas Ã  melhoramentos ou benfeitorias executadas nas mesmas. ', '', '09/11/2017'),
(103, 'ClÃ¡usula 52Âº', 'ParÃ¡grafo 1Âº', '', 'CaberÃ¡ Ã  empresa incorporadora a responsabilidade pela comunicaÃ§Ã£o de comercializaÃ§Ã£o da unidade (casa ou lote), no prazo mÃ¡ximo de 30(trinta) dias de usa efetivaÃ§Ã£o.', '', '09/11/2017'),
(104, 'ClÃ¡usula 52Âº', 'ParÃ¡grafo 2Âº', '', 'As unidades comercializadas e situadas em etapas nÃ£o entregues nÃ£o arcarÃ£o com as despesas da Ã¡rea de lazer na proporÃ§Ã£o de 37,5 (trinta e sete e meio por cento) do valor da taxa de condomÃ­nio, salvo se optar pela utilizaÃ§Ã£o da mesma atravÃ©s de comunicaÃ§Ã£o formalizada ao CondomÃ­nio.', '', '09/11/2017'),
(105, 'ClÃ¡usula 52Âº', 'ParÃ¡grafo 3Âº', '', 'As unidades comercializadas que integram as etapas jÃ¡ entregues, mesmo sem a edificaÃ§Ã£o da unidade residencial, responderÃ£o pela taxa de condomÃ­nio no mesmo percentual das demais construÃ­das.', '', '09/11/2017'),
(106, 'ClÃ¡usula 52Âº', 'ParÃ¡grafo 4Âº', '', 'As unidades comercializadas e nÃ£o entregues e unidades nÃ£o comercializadas sob a responsabilidade da incorporadora, participarÃ£o do rateio das despesas com benfeitorias das Ã¡reas comuns na mesma proporÃ§Ã£o das demais.', '', '09/11/2017'),
(107, 'ClÃ¡usula 53Âº', '', '', 'Os proprietÃ¡rios, promitentes compradores, promitentes cessionÃ¡rios, condÃ´minos e locatÃ¡rios, obriga-se por si, seu herdeiro ou sucessor, pelo fiel cumprimento desta convenÃ§Ã£o e do Regimento Interno em todos os seus termos, condiÃ§Ã£o e obrigaÃ§Ã£o. ', '', '09/11/2017'),
(108, 'ClÃ¡usula 54Âº', '', '', 'A presente ConvenÃ§Ã£o sujeita a todos ocupantes e visitantes, ainda que eventuais a qualquer titulo, serviÃ§ais do edifÃ­cio ou de quaisquer de suas unidades, obrigando a todos os condÃ´minos, seus sub-rogados e sucessores a titulo universal ou singular.', '', '09/11/2017'),
(109, 'ClÃ¡usula 55Âº', '', '', 'Dos atos do Sindico e do Conselho Consultivo, que tragam prejuÃ­zos a qualquer condÃ´mino, caberÃ¡ recursos para a AssemblÃ©ia Geral convocada na forma prevista nesta ConvenÃ§Ã£o para as AssemblÃ©ias OrdinÃ¡rias ou ExtraordinÃ¡rias.', '', '09/11/2017'),
(110, 'ClÃ¡usula 56Âº', '', '', 'Obriga-se os proprietÃ¡rios, nos contratos de locaÃ§Ã£o de suas respectivas unidades a fornecer um resumo dos direitos e obrigaÃ§Ãµes ao locatÃ¡rio e, nas propostas de vendas, a inserir no instrumento respectivo em ClÃ¡usula que o locatÃ¡rio e o proponente comprador se obriguem e se comprometam a cumprir esta ConvenÃ§Ã£o e o Regimento Interno.', '', '09/11/2017'),
(111, 'ClÃ¡usula 57Âº', '', '', 'Para locaÃ§Ã£o e comercializaÃ§Ã£o das unidades do CondomÃ­nio devera ser comprovada a quitaÃ§Ã£o das taxas condominiais ate a data da assinatura do contrato de locaÃ§Ã£o ou do fechamento do negocio.', '', '09/11/2017'),
(112, 'ClÃ¡usula 58Âº', '', '', 'Os casos omissos nesta convenÃ§Ã£o serÃ£o regulados pelo Regimento Interno, pelas disposiÃ§Ãµes legais e, especialmente pela Lei nÂ° 4.591 de 16/12/64 e Lei 10.406 de 10/01/2002 - (CC) e posteriores modificaÃ§Ã£o, bem com pelas resoluÃ§Ãµes tomadas em assemblÃ©ia, obedecendo aos quoruns especÃ­ficos.', '', '09/11/2017'),
(113, 'ClÃ¡usula 59Âº', '', '', 'Fica eleita o FÃ³rum da cidade de Caldas Novas/ GO, para dirimir outras duvidas desta ConvenÃ§Ã£o. E, por assim estarem justos, contratados e combinados, no que concerne aos direitos e obrigaÃ§Ãµes constantes das ClÃ¡usulas supra e retro mencionadas, aprovam e assinam a presente convenÃ§Ã£o de  condomÃ­nio que devera ser levada a registro no CartÃ³rio da CircunscriÃ§Ã£o de Registro de ImÃ³veis  respectivo, para que produza seus feitos legais.', '', '09/11/2017');

-- --------------------------------------------------------

--
-- Estrutura da tabela `convencao_propalt`
--

CREATE TABLE `convencao_propalt` (
  `id` int(11) NOT NULL,
  `unidade` varchar(4) COLLATE latin1_general_ci NOT NULL,
  `etapa` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `clausula` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `paragrafo` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `conteudo` text COLLATE latin1_general_ci NOT NULL,
  `conteudo_proposto` text COLLATE latin1_general_ci NOT NULL,
  `proponente` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `dt_proposicao` varchar(25) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `convencao_propalt`
--

INSERT INTO `convencao_propalt` (`id`, `unidade`, `etapa`, `email`, `clausula`, `paragrafo`, `conteudo`, `conteudo_proposto`, `proponente`, `dt_proposicao`) VALUES
(21, '0029', 'JAC - JacarandÃ¡s', '', '30', 'ParÃ¡grafo 1Âº', 'Para instituÃ­do um Fundo de Reserva permanente, destinado ao atendimento exclusivo das despesas extraordinÃ¡rias para cuja constituiÃ§Ã£o obriga-se cada condÃ´mino ao pagamento mensal correspondente a (5% por cinco por cento) do valor da taxa normal, incorporada ao valor do rateio mensal, deixando este de ser cobrado quando o saldo atingir o limite de l00(cento) salÃ¡rios mÃ­nimos regionais.', '<b>Excluir esse parÃ¡grafo, pois estÃ¡ repetido com o captu da clÃ¡usula 12</b>', 'Rogerio Wilson Lelis Caixeta', '26/07/2018 - 23:37:21'),
(22, '0029', 'JAC - JacarandÃ¡s', '', '52', '', 'Na hipÃ³tese de uma unidade pertencer a mais de um condÃ´mino, os mesmo deverÃ£o eleger quem os representarÃ¡ nas assemblÃ©ias, credenciando-o por escritor falta da indicaÃ§Ã£o aqui prevista, um sÃ³ dos proprietÃ¡rios exercerÃ¡ o direito de voto observada a ordem de chegada Ã  ordem de chegada Ã  AssemblÃ©ia pelo registro no livro de presenÃ§as.', 'Na hipÃ³tese de uma unidade pertencer a mais de um condÃ´mino, os mesmo deverÃ£o eleger quem os representarÃ¡ nas assemblÃ©ias, credenciando-o por escrito. A falta da indicaÃ§Ã£o aqui prevista, concederÃ¡ o direito Ã  Ã quele que se apresentar Ã  assemblÃ©ia, observada a ordem de chegada pelo registro no livro de presenÃ§as.\r\n', 'Rogerio Wilson Lelis Caixeta', '26/07/2018 - 23:45:03'),
(23, '0029', 'JAC - JacarandÃ¡s', '', '53', 'ParÃ¡grafo Ãšnico', 'Ã‰ facultado a qualquer condÃ´mino fazer-se representar por procurador formalmente constituÃ­do, condÃ´mino ou nÃ£o, com os poderes especÃ­ficos para a assemblÃ©ia.', 'Ã‰ facultado a qualquer condÃ´mino fazer-se representar por procurador formalmente constituÃ­do, condÃ´mino ou nÃ£o, com os poderes especÃ­ficos para a assemblÃ©ia.<p>\r\n\r\n<b>A procuraÃ§Ã£o deverÃ¡ estar com firma reconhecida. NÃ£o serÃ£o aceitas copias de procuraÃ§Ã£o. Cada representante, formalmente constituÃ­do, poderÃ¡ representar atÃ© 02 (duas) unidades, estando elas adimplentes</b>.\r\n', 'Rogerio Wilson Lelis Caixeta', '26/07/2018 - 23:50:46'),
(24, '0029', 'JAC - JacarandÃ¡s', '', '75', 'ParÃ¡grafo Ãšnico', 'Os membros do Conselho Fiscal que tiveram mais de 3(trÃªs) faltas nas reuniÃµes do mesmo, serÃ£o excluÃ­dos, assumindo em seu lugar o primeiro suplente eleito.', 'Os membros do Conselho Fiscal que tiveram mais de 3(trÃªs) faltas <b> nÃ£o justificadas</b> nas reuniÃµes do mesmo, serÃ£o excluÃ­dos, assumindo em seu lugar o primeiro suplente eleito.\r\n', 'Rogerio Wilson Lelis Caixeta', '26/07/2018 - 23:59:58'),
(25, '0029', 'JAC - JacarandÃ¡s', '', '81', '', 'Quando da eleiÃ§Ã£o do SÃ­ndico serÃ£o eleitos na mesma AssemblÃ©ia, 06 (seis) membros do Conselho Administrativo, sendo obrigatoriamente, um proprietÃ¡rio de cada etapa, com mandato idÃªntico ao do Sindico.', 'Quando da eleiÃ§Ã£o do SÃ­ndico serÃ£o eleitos na mesma AssemblÃ©ia, 03(trÃªs) membros do Conselho Administrativo, proprietÃ¡rios e residentes, com mandato idÃªntico ao do SÃ­ndico. <p><p>\r\n\r\n<b>Incluir:</b><p>\r\nParÃ¡grafo Ãšnico: O conselho administrativo Ã© responsÃ¡vel pelo apoio Ã Â GestÃ£o, organizaÃ§Ã£o, coordenaÃ§Ã£o e controle de atividades junto ao sÃ­Â­ndico, alÃ©m de ser o responsÃ¡vel operacional pelas atividades executadas no condomÃ­nio', 'Rogerio Wilson Lelis Caixeta', '27/07/2018 - 00:10:07'),
(26, '0029', 'JAC - JacarandÃ¡s', '', '38', 'ParÃ¡grafo 1Âº', 'As AssemblÃ©ias Gerais OrdinÃ¡rias serÃ£o realizadas anualmente, atÃ© a primeira quinzena de marÃ§o, ou quando as circunstÃ¢ncias exigirem, convocadas pelo SÃ­ndico e nelas discutidos e decididos os seguintes assuntos:\r\n<p>\r\na) ApreciaÃ§Ã£o e aprovaÃ§Ã£o das contas do SÃ­ndico relativas ao exercÃ­cio findo;\r\n<p>\r\nb) ApreciaÃ§Ã£o do orÃ§amento para o exercÃ­cio subseqÃ¼ente;\r\n<p>\r\nc) EleiÃ§Ã£o de Sindico\r\n<p>', 'As AssemblÃ©ias Gerais OrdinÃ¡rias serÃ£o realizadas anualmente, atÃ© a primeira quinzena de marÃ§o, ou quando as circunstÃ¢ncias exigirem, convocadas pelo SÃ­ndico ou <b>pelo conselho Fiscal</b> e nelas discutidos e decididos os seguintes assuntos:\r\n<p>\r\na) ApreciaÃ§Ã£o e aprovaÃ§Ã£o das contas do SÃ­ndico relativas ao exercÃ­cio findo;\r\n<p>\r\nb) ApreciaÃ§Ã£o do orÃ§amento para o exercÃ­cio subseqÃ¼ente;\r\n<p>\r\nc) EleiÃ§Ã£o de Sindico e Conselhos ficais e administrativo, a cada biÃªnio.\r\n<p>\r\n\r\n', 'Rogerio Wilson Lelis Caixeta', '27/07/2018 - 00:21:24'),
(27, '0029', 'JAC - JacarandÃ¡s', '', '62', '', 'A administraÃ§Ã£o do CondomÃ­nio serÃ¡ exercida por um SÃ­ndico (condÃ´mino proprietÃ¡rio adimplente) eleito em AssemblÃ©ia Geral OrdinÃ¡ria para uma gestÃ£o de 02(dois) anos, permitida a reeleiÃ§Ã£o (Art. 1347, do CC).', 'A administraÃ§Ã£o do CondomÃ­nio serÃ¡ exercida por um SÃ­ndico (condÃ´mino proprietÃ¡rio adimplente) eleito em AssemblÃ©ia Geral OrdinÃ¡ria para uma gestÃ£o de 02(dois) anos, permitida a reeleiÃ§Ã£o (Art. 1347, do CC)<b> por mais 01 (um) mandato apenas</b>', 'RogÃ©rio Wilson Lelis Caixeta', '27/07/2018 - 09:28:59'),
(28, '0029', 'JAC - JacarandÃ¡s', '', '71', '', 'Compete ao SÃ­ndico:\r\n<p>\r\n1) Administrar o CondomÃ­nio dentro das exigÃªncias legais;\r\n<p>\r\n2) Representar o CondomÃ­nio, ativa e passivamente, em juÃ­zo ou fora dele, cumprir e fazer cumprir a lei, a presente ConvenÃ§Ã£o, o Regimento Interno, bem como as deliberaÃ§Ãµes das assemblÃ©ias;\r\n<p>\r\n3) Admitir e dispensar funcionÃ¡rios e empregados;\r\n<p>\r\n4) Contratar com firmas especializadas os serviÃ§os de administraÃ§Ã£o terceirizada do CondomÃ­nio, desde que autorizado pela AssemblÃ©ia;\r\n<p>\r\n5) Prestar a qualquer condÃ´mino ou morador, a qualquer tempo e quando solicitado, mediante registro no livro de OcorrÃªncias, informaÃ§Ãµes sobre os atos da administraÃ§Ã£o e submeter ao Conselho Fiscal,  mensalmente, os balancetes respectivos para analise e aprovaÃ§Ã£o;\r\n<p>\r\n6) Prestar Ã  assemblÃ©ia anual contas de sua gestÃ£o com todos os balancetes mensais analisados e aprovados pelo Conselho Fiscal; \r\n<p>\r\n7) Cobrar em juÃ­zo, apÃ³s o vencimento da 3 (terceira) taxa em atraso, Ã¡s despesas do CondomÃ­nio nÃ£o pagas, bem como as multas e encargos por infraÃ§Ã£o de dispositivos legais ou previstos nesta ConvenÃ§Ã£o e no Regimento Interno; \r\n<p>\r\n8) Entregar ao seu sucessor todos os livros, documentos e pertences do CondomÃ­nio; \r\n<p>\r\n9) Manter atualizado o Livro de PatrimÃ´nio do CondomÃ­nio;\r\n<p>\r\n10) Comunicar aos Conselhos Fiscal e Administrativo as citaÃ§Ãµes judiciais ou administrativas que receber;\r\n<p>\r\n11) Convocar assemblÃ©ias gerais ordinÃ¡rias ou extraordinÃ¡rias, estas quando necessÃ¡rias, e nos casos de interposiÃ§Ã£o de recurso, conforme dispÃµe a presente ConvenÃ§Ã£o;\r\n<p>\r\n12) Elaborar o plano orÃ§amentÃ¡rio anual com todas as previsÃµes de investimentos, a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria \r\n<p>', '\r\n<b>Ao SÃ­ndico recaem as responsabilidades por todas as decisÃµes administrativas e operacionais, respeitada a competÃªncia do Conselho Fiscal e Administrativo. Atua conforme autonomia definida em Estatuto. Coordena e fiscaliza, com orientaÃ§Ãµes do Conselho fiscal, fazendo cumprir o Estatuto, o Regimento Interno e a legislaÃ§Ã£o vigente. <p>\r\nAlÃ©m do exposto, compete ao sÃ­ndico:</b><p>\r\n\r\n1. Administrar o CondomÃ­Â­nio dentro das exigÃªncias legais;<p>\r\n\r\n2. Representar o CondomÃ­Â­nio, ativa e passivamente, em juÃ­Â­zo ou fora dele, cumprir e fazer cumprir a lei, a presente ConvenÃ§Ã£o, o Regimento Interno, bem como as deliberaÃ§Ãµes das assemblÃ©ias;<p>\r\n\r\n3. Admitir e dispensar funcionÃ¡rios e empregados, apoiado pelos conselhos;<p>\r\n\r\n4. Contratar com firmas especializadas os serviÃ§os de administraÃ§Ã£o terceirizada do CondomÃ­nio, desde que autorizado pela AssemblÃ©ia;<p>\r\n\r\n5.Prestar a qualquer condÃ´mino ou morador, a qualquer tempo e quando solicitado, mediante registro no livro de OcorrÃªncias, informaÃ§Ãµes sobre os atos da administraÃ§Ã£o e submeter ao Conselho Fiscal, mensalmente, os balancetes respectivos para analise e aprovaÃ§Ã£o;<p>\r\n\r\n6. Prestar Ã Â  assemblÃ©ia anual as contas de sua gestÃ£o com todos os balancetes mensais analisados e aprovados pelo Conselho Fiscal;<p>\r\n\r\n7. Cobrar em juÃ­zo, apÃ³s o vencimento da 3 (terceira) taxa em atraso, as despesas do CondomÃ­nio nÃ£o pagas, bem como as multas e encargos por infraÃ§Ã£o de dispositivos legais ou previstos nesta ConvenÃ§Ã£o e no Regimento Interno;<p>\r\n\r\n8. Entregar ao seu sucessor todos os livros, documentos e pertences do CondomÃ­nio;<p>\r\n\r\n9.Manter atualizado o Livro de PatrimÃ´nio do CondomÃ­nio;<p>\r\n\r\n10. Comunicar aos Conselhos Fiscal e Administrativo as citaÃ§Ãµes judiciais ou administrativas que receber;<p>\r\n\r\n11. Convocar assemblÃ©ias gerais ordinÃ¡rias ou extraordinÃ¡rias, estas quando necessÃ¡rias, e nos casos de interposiÃ§Ã£o de recurso, conforme dispÃµe a presente ConvenÃ§Ã£o;<p>\r\n\r\n12. Elaborar o plano orÃ§amentÃ¡rio anual com todas as previsÃµes de investimentos, a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria. <p>\r\n\r\n13. Determinar, quando necessÃ¡rio ou conveniente, o exame e verificaÃ§Ã£o do cumprimento dos atos normativos ou programas de atividades;<p>\r\n\r\n14. Convocar, extraordinariamente, o Conselho Fiscal;<p>\r\n\r\n15. Participar das reuniÃµes do Conselho Fiscal, quando convocado;<p>\r\n\r\n16. Praticar atos de gestÃ£o nÃ£o expresso nesta convenÃ§Ã£o e nÃ£o vedados por lei.', 'RogÃ©rio Wilson Lelis Caixeta', '27/07/2018 - 09:43:33'),
(29, '0029', 'JAC - JacarandÃ¡s', '', '76', '', 'Compete ao Conselho Fiscal:\r\n<p>\r\n1) Apreciar o Plano orÃ§amentÃ¡rio anual, com todas as previsÃµes de investimentos; a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria, em conjunto com o sÃ­ndico e Conselho Administrativo;\r\n<p>\r\n2) Assessorar o SÃ­ndico na soluÃ§Ã£o dos problemas nÃ£o rotineiros e nos casos de urgÃªncia;\r\n<p>\r\n3) Opinar quanto aos assuntos pessoais que envolvam o relacionamento do SÃ­ndico e os condÃ´minos;\r\n<p>\r\n4) Dar parecer sobre matÃ©ria relativa Ã  realizaÃ§Ã£o de despesas extraordinÃ¡rias;\r\n<p>\r\n5) Fiscalizar as atividades do SÃ­ndico, examinar suas contas mensais, relatÃ³rios, comprovantes e documentos, emitindo parecer conclusivo, por escrito, aprovando ou rejeitando-as Ã  discussÃ£o da assemblÃ©ia de condÃ´minos;\r\n<p>\r\n6) Pronunciar-se com brevidade sobre todas as consultas ou pedidos de assessoramento que lhe sejam encaminhados pelo SÃ­ndico ou condÃ´minos.', '\r\nCompete ao Conselho Fiscal:<p>\r\n\r\n1) Apreciar o Plano orÃ§amentÃ¡rio anual, com todas as previsÃµes de investimentos, a ser apresentado na AssemblÃ©ia Geral OrdinÃ¡ria, em conjunto com o sÃ­ndico;<p>\r\n\r\n2) Assessorar o sÃ­ndico na soluÃ§Ã£o dos problemas nÃ£o rotineiros e nos casos de urgÃªncia;<p>\r\n\r\n3) Opinar quanto aos assuntos pessoais que envolvam o relacionamento do sÃ­ndico e os condÃ´minos;<p>\r\n\r\n4) Dar parecer sobre matÃ©ria relativa Ã Â  realizaÃ§Ã£o de despesas extraordinÃ¡rias;<p>\r\n\r\n5) Fiscalizar as atividades do SÃ­ndico, examinar suas contas mensais, relatÃ³rios, comprovantes e documentos, emitindo parecer conclusivo, por escrito, aprovando ou rejeitando-as Ã Â  discussÃ£o da assemblÃ©ia de condÃ´minos;<p>\r\n\r\n6) Pronunciar-se com brevidade sobre todas as consultas ou pedidos de assessoramento que lhe sejam encaminhados pelo SÃ­ndico ou condÃ´minos.<p>\r\n\r\n7) Convocar o sÃ­ndico e os membros do conselho administrativo para prestar esclarecimentos quando necessÃ¡rio;<p>\r\n\r\n8) Apresentar ao sÃ­ndico as irregularidades apuradas, sugerindo medidas saneadoras;<p>\r\n\r\n9) Lavrar, em livro de atas, os pareceres emitidos sobre o resultado de exames procedidos.<p>', 'RogÃ©rio Wilson Lelis Caixeta', '27/07/2018 - 09:54:14'),
(32, '0001', 'AdministraÃ§Ã£o Village', '', '2', '', 'Todas as casas de campo serÃ£o padronizadas, constituÃ­das de varanda, sala, dois ou trÃªs quartos, banheiro e cozinha, de acordo com os projetos jÃ¡ aprovados e executados em terrenos de fraÃ§Ãµes ideais de iguais dimensÃµes, exceto as localidades nas esquinas que serÃ£o menores em virtude dos chanfrados.', 'Todas as casas do CondomÃ­nio seguirÃ£o os padrÃµes estabelecidos pela legislaÃ§Ã£o municipal,  especialmente a Lei nÂº. 058/2016 e a Lei de CondomÃ­nio Horizontais de nÂº. 1841/2012 e pelas regras definidas pelo condomÃ­nio devendo todos os projetos alÃ©m de serem aprovados pela secretaria municipal de obras, deverÃ£o tambÃ©m ser aprovados pelo SÃ­ndico e Conselheiros Administrativos antes de serem executados, seguindo as normas preestabelecidas no anexo I desta convenÃ§Ã£o.\r\n\r\n<b> Inserir Anexo I</b>', 'AdministraÃ§Ã£o do condomÃ­nio', '27/07/2018 - 11:07:31'),
(33, '0000', 'PIT - Pitangueiras', '', '19', 'ParÃ¡grafo 2Âº', 'A receita do CondomÃ­nio serÃ¡ constituÃ­da pelas contribuiÃ§Ãµes dos condÃ´minos, aprovadas pelas AssemblÃ©ia Gerais OrdinÃ¡ria e ExtraordinÃ¡rias, e, arrecadada na forma prevista nesta ConvenÃ§Ã£o.', 'A receita do CondomÃ­nio serÃ¡ constituÃ­da pelas contribuiÃ§Ãµes dos condÃ´minos, aprovadas pelas AssemblÃ©ia Gerais OrdinÃ¡ria e ExtraordinÃ¡rias, e, arrecadada na forma prevista nesta ConvenÃ§Ã£o.', 'Francisca Pereira DA Silva MagalhÃ£es ', '18/10/2018 - 16:00:38'),
(34, '0029', 'JAC - JacarandÃ¡s', '', '16', 'ParÃ¡grafo Ãšnico', 'Ã‰ vedada a alteraÃ§Ã£o da utilizaÃ§Ã£o ou quaisquer modificaÃ§Ãµes da edificaÃ§Ã£o que lhe desvirtue o uso ou altere as condiÃ§Ãµes que sÃ£o prÃ³prias Ã  sua destinaÃ§Ã£o.', 'Ã‰ vedada a alteraÃ§Ã£o da utilizaÃ§Ã£o ou quaisquer modificaÃ§Ãµes da edificaÃ§Ã£o que lhe desvirtue o uso ou altere as condiÃ§Ãµes <b>padronizadas</b> que sÃ£o prÃ³prias Ã  sua destinaÃ§Ã£o.', 'RogÃ©rio Wilson Lelis Caixeta', '18/10/2018 - 16:30:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `dependente_adm`
--

CREATE TABLE `dependente_adm` (
  `id` int(11) NOT NULL,
  `nome_dependente` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `id_proprietario` int(20) NOT NULL,
  `parentesco` varchar(30) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `dependente_adm`
--

INSERT INTO `dependente_adm` (`id`, `nome_dependente`, `id_proprietario`, `parentesco`) VALUES
(9, 'Caleul Raposo Caixeta', 2, 'Filho(a)'),
(10, 'Rafael Ros LÃ©lis Caixeta', 2, 'Filho(a)'),
(11, 'Tamila Raposo Caixeta', 2, 'Filho(a)'),
(13, 'Ana Claudia Raposo de Melo', 2, 'CÃ´njugue / Marido / Esposa'),
(14, 'Vera Regina LÃ©lis Caixeta', 2, 'Genitor (Pai/MÃ£e)'),
(16, 'Aparecida Montoro de Melo', 2, 'Sogro(a)'),
(18, 'Wilson GonÃ§alves Caixeta', 2, 'Genitor (Pai/MÃ£e)'),
(19, 'Danuta Raposo Nunes', 2, 'Filho(a)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `falesindico`
--

CREATE TABLE `falesindico` (
  `id` int(11) NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `assunto` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `dt_cadastro` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `dt_atendimento` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `situacao` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `mensagem` text COLLATE latin1_general_ci NOT NULL,
  `msgsindico` text COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `falesindico`
--

INSERT INTO `falesindico` (`id`, `email`, `assunto`, `dt_cadastro`, `dt_atendimento`, `situacao`, `mensagem`, `msgsindico`) VALUES
(1, 'rogerio@1portodos.com.br', 'Sugestao', '17/08/2017', '21/08/2017', 'Tratada', 'Teste de sugestÃ£o', 'Bom dia.\r\n\r\nMensagem recebida. Vamos dar andamento aos testes!!'),
(2, 'carvalho.ideal@gmail.com', 'Sugestao', '20/08/2017', '21/08/2017', 'Tratada', 'Vamos que vamos!!! Teste....', 'Bom dia Claudio.\r\n\r\nVamos que vamos! O Teste parece que esta sendo produtivo. \r\nAgora verifique o menu \"Consulta SolicitaÃ§Ãµes\" e veja se consegue visualizar  somente os seus registros.'),
(3, 'van101960@outlook.com.br', 'Outros', '23/08/2017', '23/08/2017', 'Tratada', 'Teste ', 'Bom dia Vanilda!!\r\nTeste realizado com sucesso.\r\nVocÃª deverÃ¡ receber um e-mail com essa resposta. Depois verifique sua caixa postal.\r\nAbraÃ§o.\r\nRogÃ©rio'),
(4, 'van101960@outlook.com.br', 'Outros', '23/08/2017', '04/09/2017', 'Tratada', 'Teste ', 'Teste realizado. VocÃª deve estar recebendo um e-mail em breve'),
(5, 'rogerio@1portodos.com.br', 'Sugestao', '04/09/2017', '04/09/2017', 'Tratada', 'Oi', 'Teste de oi');

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(160) COLLATE latin1_general_ci NOT NULL,
  `foto` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `noticia` text COLLATE latin1_general_ci NOT NULL,
  `visitas` int(11) NOT NULL,
  `data` varchar(25) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `foto`, `noticia`, `visitas`, `data`) VALUES
(1, 'PÃ¡gina de notÃ­cias do Residencial Village Thermas das Caldas', '', 'Prezados amigos,<p>\r\n\r\nComo prometido, criei a pÃ¡gina de noticias do nosso condomÃ­Â­nio.<p>\r\nEsta pÃ¡gina tem por objetivo divulgar assuntos ou noticias de cunho pÃºblico,  importantes e de interesse comum.<p>\r\nComo <b><i>exemplo</i></b> de um assunto, cito o pagamento do IPTU: <p>\r\n<dl><dt><dd><ol style=\"list-style-type: disc\">\r\n\r\n<li> O pagamento antecipado do IPTU darÃ¡ desconto de atÃ© 50% no valor final. Aproveite essa oportunidade! </li>\r\n\r\n</ol><dd></dt></dl>\r\n\r\n<b>OBSERVAÃ‡ÃƒO</b>: Importante lembrar que <b>somente pessoas autorizadas</b> poderÃ£o cadastrar noticias no site.<p>\r\nForte abraÃ§o a todos.', 44, '31/08/2017 - 09:32'),
(2, 'IPTU 2018', '', 'Prezados,<br>\r\n\r\nO site da prefeitura de Caldas Novas, disponibilizou o acesso para a emissÃ£o dos boletos para pagamento da taxa de ITPU/TLP. <br>\r\n\r\nPara acessar o seu cadastro, insira as informaÃ§Ãµes solicitadas, tais como o CPF. <br>\r\nVocÃª terÃ¡ a opÃ§Ã£o de pagar em uma Ãºnica parcela, sendo concedido o desconto de 20% para os pagamentos atÃ© a data de 12/03/2018.<br>\r\n\r\nPara acessar o serviÃ§o, <a href=\"http://servicos.caldasnovas.go.gov.br:8080/sig/app.html#/servicosonline/index\" title=\"IPTU 2018 - Caldas Novas\" >clique aqui</a>\r\n<br>\r\nAbraÃ§o a todos!<br>\r\n\r\nRogÃ©rio Caixeta', 15, '15/02/2018 - 17:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `PROPRIETARIOS`
--

CREATE TABLE `PROPRIETARIOS` (
  `COD_PROP` int(11) NOT NULL,
  `NOME` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `ENDERECO` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `CIDADE` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `FONE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tab_docfiscal`
--

CREATE TABLE `tab_docfiscal` (
  `id` int(11) NOT NULL,
  `ano_doc` varchar(4) COLLATE latin1_general_ci NOT NULL,
  `mes_doc` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `dt_emissao_doc` varchar(21) COLLATE latin1_general_ci NOT NULL,
  `cpfcnpj_fornecedor` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `nr_documento` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `nome_fornecedor` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `valor_doc` decimal(9,2) NOT NULL,
  `comprovante` varchar(220) COLLATE latin1_general_ci NOT NULL,
  `finalidade_doc` text COLLATE latin1_general_ci NOT NULL,
  `situacao` varchar(25) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `tab_docfiscal`
--

INSERT INTO `tab_docfiscal` (`id`, `ano_doc`, `mes_doc`, `dt_emissao_doc`, `cpfcnpj_fornecedor`, `nr_documento`, `nome_fornecedor`, `valor_doc`, `comprovante`, `finalidade_doc`, `situacao`) VALUES
(1, '2017', 'Janeiro', '01/01/2017', '05.755.149-0001/31', 'NFe325', 'Casas Bahia', 1258.70, '2017_Janeiro_05755149000131_01012017.jpg', 'Teste de upload', 'Pendente de aprovaÃ§Ã£o'),
(2, '2017', 'Janeiro', '09/01/2018', '07.454.896/0001-28', 'CF529142', 'Panificadora Bonanza', 29.70, '2017_Janeiro_07454896000128_09012018.pdf', 'Teste de upload', 'Rejeitada');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tab_historico_documento`
--

CREATE TABLE `tab_historico_documento` (
  `id` int(11) NOT NULL,
  `id_doc` varchar(4) COLLATE latin1_general_ci NOT NULL,
  `dt_movimento` varchar(21) COLLATE latin1_general_ci NOT NULL,
  `situacao` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `agente_autorizado` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `anotacao` text COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `tab_historico_documento`
--

INSERT INTO `tab_historico_documento` (`id`, `id_doc`, `dt_movimento`, `situacao`, `agente_autorizado`, `anotacao`) VALUES
(1, '1', '08/01/2018', 'LanÃ§ado', 'rogerio@1portodos.com.br', 'Upload do arquivo'),
(2, '1', '08/01/2018 - 20:57:46', 'Em analise', '', 'AnotaÃ§Ã£o 01'),
(3, '1', '08/01/2018 - 20:58:09', 'Pendente de aprovaÃ§Ã£o', 'rogerio@1portodos.com.br', 'AprovaÃ§Ã£o 01'),
(4, '1', '09/01/2018 - 15:02:22', 'Em analise', 'rogerio@1portodos.com.br', 'Em analise- - Ver gravaÃ§Ã£o do nome'),
(5, '1', '09/01/2018 - 15:03:25', 'Rejeitada', 'rogerio@1portodos.com.br', 'Teste de rejeiÃ§Ã£o'),
(6, '2', '09/01/2018', 'LanÃ§ado', 'rogerio@1portodos.com.br', 'Upload do arquivo'),
(7, '2', '09/01/2018 - 15:09:56', 'Em analise', 'rogerio@1portodos.com.br', 'Ena anÃ¡lise'),
(8, '2', '09/01/2018 - 15:10:13', 'Rejeitada', 'rogerio@1portodos.com.br', 'Rejeitado - valor divergente'),
(9, '1', '09/01/2018 - 15:10:41', 'Pendente de aprovaÃ§Ã£o', 'rogerio@1portodos.com.br', 'Aprovado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `senha` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `tipo` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `conselho` varchar(3) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `tipo`, `conselho`) VALUES
(1, 'abmilhomem@gmail.com', 'abmilhomem@gmail.com', '', ''),
(2, 'acarloslneves@gmail.com', 'acarloslneves@gmail.com', '', ''),
(3, 'acblago@gmail.com', 'acblago@gmail.com', '', ''),
(4, 'acmandrami@gmail.com', 'acmandrami@gmail.com', '', ''),
(5, 'adaoazevedo@globo.com', 'adaoazevedo@globo.com', '', ''),
(6, 'adhara@adharaluz.com.br', 'adhara@adharaluz.com.br', '', ''),
(7, 'adriana.hospitalar@hotmail.com', 'adriana.hospitalar@hotmail.com', '', ''),
(8, 'adriano.meneses@hotmail.com.br', 'adriano.meneses@hotmail.com.br', '', ''),
(9, 'adrianotyrka@gmail.com', 'adrianotyrka@gmail.com', '', ''),
(10, 'agildoapeixoto@yahoo.com.br', 'agildoapeixoto@yahoo.com.br', '', ''),
(11, 'agnaldo.bezerra@conab.gov.br', 'agnaldo.bezerra@conab.gov.br', '', ''),
(12, 'agsilva3@gmail.com', 'agsilva3@gmail.com', '', ''),
(13, 'airamcb2014@gmail.com', 'airamcb2014@gmail.com', '', ''),
(14, 'alaylins@terra.com.br', 'alaylins@terra.com.br', '', ''),
(15, 'aline-g@yahoo.com.br', 'aline-g@yahoo.com.br', '', ''),
(16, 'allinecramos@hotmail.com', 'allinecramos@hotmail.com', '', ''),
(17, 'aloisio-aguiar@uol.com.br', 'aloisio-aguiar@uol.com.br', '', ''),
(18, 'aloisioferreiradossantos@gmail.com', 'aloisioferreiradossantos@gmail.com', '', ''),
(19, 'alveswp@hotmail.com', 'alveswp@hotmail.com', '', ''),
(20, 'alveswp@hotmail.com', 'alveswp@hotmail.com', '', ''),
(21, 'amandaabatistadeoliveira@gmail.com', 'amandaabatistadeoliveira@gmail.com', '', ''),
(22, 'ana.alcantara@marisa.com.br', 'ana.alcantara@marisa.com.br', '', ''),
(23, 'ana.poeck@gmail.com ', 'ana.poeck@gmail.com ', '', ''),
(24, 'aninhatchipsh@hotmail.com', 'aninhatchipsh@hotmail.com', '', ''),
(25, 'aparecidoseguros@gmail.com', 'aparecidoseguros@gmail.com', '', ''),
(26, 'apcap.df@gmail.com', 'apcap.df@gmail.com', '', ''),
(27, 'AQUARIUS@aquariusdespachante.com.br', 'AQUARIUS@aquariusdespachante.com.br', '', ''),
(28, 'ar.donatoni@gmail.com', 'ar.donatoni@gmail.com', '', ''),
(29, 'arantestatiana@yahoo.com.br', 'arantestatiana@yahoo.com.br', '', ''),
(30, 'arianne@iegui.com.br', 'arianne@iegui.com.br', '', ''),
(31, 'arionemarques@hotmail.com', 'arionemarques@hotmail.com', '', ''),
(32, 'arlindo.chaves@terra.com.br', 'arlindo.chaves@terra.com.br', '', ''),
(33, 'arnaldomiziara@gmail.com', 'arnaldomiziara@gmail.com', '', ''),
(34, 'aurideafernandes@gmail.com', 'aurideafernandes@gmail.com', '', ''),
(35, 'axos@uol.com.br', 'axos@uol.com.br', '', ''),
(36, 'b.ilhadosaber@gmail.com', 'b.ilhadosaber@gmail.com', '', ''),
(37, 'barbozarep@bol.com.br', 'barbozarep@bol.com.br', '', ''),
(38, 'barros@quimifol.com.br', 'barros@quimifol.com.br', '', ''),
(39, 'belchiorc@gmail.com', 'belchiorc@gmail.com', '', ''),
(40, 'bernardogalli@hotmail.com', 'bernardogalli@hotmail.com', '', ''),
(41, 'bernardomonticelligm@outlook.com', 'bernardomonticelligm@outlook.com', '', ''),
(42, 'betimcs@bol.com.br', 'betimcs@bol.com.br', '', ''),
(43, 'bizzottoangela@yahoo.com.br', 'bizzottoangela@yahoo.com.br', '', ''),
(44, 'boletoacontece@gmail.com', 'boletoacontece@gmail.com', '', ''),
(45, 'bradisba@yahoo.com.br', 'bradisba@yahoo.com.br', '', ''),
(46, 'brenno.machado@gmail.com', 'brenno.machado@gmail.com', '', ''),
(47, 'caldasnovas@yogoothies.com.br', 'caldasnovas@yogoothies.com.br', '', ''),
(48, 'camilaarouca@hotmail.com', 'camilaarouca@hotmail.com', '', ''),
(49, 'camillavieira81@gmail.com', 'camillavieira81@gmail.com', '', ''),
(50, 'capmartinsgontijo@gmail.com', 'capmartinsgontijo@gmail.com', '', ''),
(51, 'carita.marques@hotmail.com', 'carita.marques@hotmail.com', '', ''),
(52, 'carlosapmorgado@hotmail.com', 'carlosapmorgado@hotmail.com', '', ''),
(53, 'carlosdaciano@hotmail.com', 'carlosdaciano@hotmail.com', '', ''),
(54, 'carmelita2105@gmail.com', 'carmelita2105@gmail.com', '', ''),
(55, 'carolinatroyan@gmail.com', 'carolinatroyan@gmail.com', '', ''),
(56, 'carvalho.ideal@gmail.com', 'carvalho.ideal@gmail.com', '', ''),
(57, 'celio@viacontabil.net.br', 'celio@viacontabil.net.br', '', ''),
(58, 'celio-bezerra@hotmail.com', 'celio-bezerra@hotmail.com', '', ''),
(59, 'cerejafaria@gmail.com', 'cerejafaria@gmail.com', '', ''),
(60, 'cezarestevesadv@gmail.com', 'cezarestevesadv@gmail.com', '', ''),
(61, 'cgma@iegui.com.br', 'cgma@iegui.com.br', '', ''),
(62, 'christianmarra2005@yahoo.com.br', 'christianmarra2005@yahoo.com.br', '', ''),
(63, 'claric@globo.com', 'claric@globo.com', '', ''),
(64, 'claudiagnakato@hotmail.com', 'claudiagnakato@hotmail.com', '', ''),
(65, 'claudiamarizegsilva@gmail.com', 'claudiamarizegsilva@gmail.com', '', ''),
(66, 'claudinhalins0307@hotmail.com', 'claudinhalins0307@hotmail.com', '', ''),
(67, 'claudinhu_uu@hotmail.com', 'claudinhu_uu@hotmail.com', '', ''),
(68, 'claudio.mendesaidar@gmail.com', 'claudio.mendesaidar@gmail.com', '', ''),
(69, 'cleberj@libertyseguros.com.br', 'cleberj@libertyseguros.com.br', '', ''),
(70, 'cledson.marques@hotmail.com', 'cledson.marques@hotmail.com', '', ''),
(71, 'cleoms@hotmail.com', 'cleoms@hotmail.com', '', ''),
(72, 'clodoaldo_silva@uol.com.br', 'clodoaldo_silva@uol.com.br', '', ''),
(73, 'clorisilvaadv@gmail.com', 'clorisilvaadv@gmail.com', '', ''),
(74, 'consultedf@yahoo.com.br', 'consultedf@yahoo.com.br', '', ''),
(75, 'contabil.assistente3@cifarma.com.br', 'contabil.assistente3@cifarma.com.br', '', ''),
(76, 'contador.valdelino@gmail.com', 'contador.valdelino@gmail.com', '', ''),
(77, 'costar849@gmail.com', 'costar849@gmail.com', '', ''),
(78, 'crakdebola@yahoo.com.br', 'crakdebola@yahoo.com.br', '', ''),
(79, 'cristianop@ibest.com.br', 'cristianop@ibest.com.br', '', ''),
(80, 'cristina.csggn@ig.com.br', 'cristina.csggn@ig.com.br', '', ''),
(81, 'cs_teixeira@yahoo.com.br', 'cs_teixeira@yahoo.com.br', '', ''),
(82, 'cyannbrindes@gmail.com', 'cyannbrindes@gmail.com', '', ''),
(83, 'danielrotordiesel@outlook.com', 'danielrotordiesel@outlook.com', '', ''),
(84, 'daodamota@hotmail.com', 'daodamota@hotmail.com', '', ''),
(85, 'dayseetacinha@hotmail.com', 'dayseetacinha@hotmail.com', '', ''),
(86, 'ddrika0809@hotmail.com', 'ddrika0809@hotmail.com', '', ''),
(87, 'deaimoveiscaldasnovas@gmail.com', 'deaimoveiscaldasnovas@gmail.com', '', ''),
(88, 'debora.gate2009@gmail.com', 'debora.gate2009@gmail.com', '', ''),
(89, 'deiziella@hotmail.com', 'deiziella@hotmail.com', '', ''),
(90, 'deus21@ig.com.br', 'deus21@ig.com.br', '', ''),
(91, 'diagnistica.go@hotmail.com', 'diagnistica.go@hotmail.com', '', ''),
(92, 'divanypires@gmail.com', 'divanypires@gmail.com', '', ''),
(93, 'divina-claudia@hotmail.com', 'divina-claudia@hotmail.com', '', ''),
(94, 'doraadv@terra.com.br', 'doraadv@terra.com.br', '', ''),
(95, 'dp@grupop7.com.br', 'dp@grupop7.com.br', '', ''),
(96, 'dulcineiasouza@hotmail.com', 'dulcineiasouza@hotmail.com', '', ''),
(97, 'dutraortodontia@gmail.com', 'dutraortodontia@gmail.com', '', ''),
(98, 'dwildson@gmail.com', 'dwildson@gmail.com', '', ''),
(99, 'edmaria1@hotmail.com', 'edmaria1@hotmail.com', '', ''),
(100, 'ednatania@yahoo.com.br', 'ednatania@yahoo.com.br', '', ''),
(101, 'edsonpalhao5@gmail.com', 'edsonpalhao5@gmail.com', '', ''),
(102, 'eduardojamiro@yahoo.com.br', 'eduardojamiro@yahoo.com.br', '', ''),
(103, 'eduardomagalhaes1209@hotmail.com', 'eduardomagalhaes1209@hotmail.com', '', ''),
(104, 'edudagama@gmail.com', 'edudagama@gmail.com', '', ''),
(105, 'eleandracristina@yahoo.com.br', 'eleandracristina@yahoo.com.br', '', ''),
(106, 'elenicebeteti@ig.com.br', 'elenicebeteti@ig.com.br', '', ''),
(107, 'eliel.adet@gmail.com', 'eliel.adet@gmail.com', '', ''),
(108, 'elton-lan@hotmail.com', 'elton-lan@hotmail.com', '', ''),
(109, 'emersoncoordenador@bol.com.br', 'emersoncoordenador@bol.com.br', '', ''),
(110, 'emijecsan@hotmail.com', 'emijecsan@hotmail.com', '', ''),
(111, 'emilianagomes@terra.com.br', 'emilianagomes@terra.com.br', '', ''),
(112, 'engenharia2@familiapaulista.com.br', 'engenharia2@familiapaulista.com.br', '', ''),
(113, 'engenhariajc10@gmail.com', 'engenhariajc10@gmail.com', '', ''),
(114, 'epeduff@yahoo.com.br', 'epeduff@yahoo.com.br', '', ''),
(115, 'eraldoab@gmail.com', 'eraldoab@gmail.com', '', ''),
(116, 'erlaneasiqueira@hotmail.com', 'erlaneasiqueira@hotmail.com', '', ''),
(117, 'estrelasul2010@hotmail.com', 'estrelasul2010@hotmail.com', '', ''),
(118, 'euliviamuro@gmail.com', 'euliviamuro@gmail.com', '', ''),
(119, 'Fatimabianchi@gmail.com', 'Fatimabianchi@gmail.com', '', ''),
(120, 'fatimagonzaga08@gmail.com', 'fatimagonzaga08@gmail.com', '', ''),
(121, 'fcatorquato@hotmail.com', 'fcatorquato@hotmail.com', '', ''),
(122, 'felipegaldino1703@gmail.com', 'felipegaldino1703@gmail.com', '', ''),
(123, 'felix_farma@hotmail.com', 'felix_farma@hotmail.com', '', ''),
(124, 'ferreira_s8@hotmail.com', 'ferreira_s8@hotmail.com', '', ''),
(125, 'financeiro@camagri.com.br', 'financeiro@camagri.com.br', '', ''),
(126, 'financeiro02@centrocarautoservice.com.br', 'financeiro02@centrocarautoservice.com.br', '', ''),
(127, 'flacarlito@gmail.com', 'flacarlito@gmail.com', '', ''),
(128, 'flaviarm59@gmail.com', 'flaviarm59@gmail.com', '', ''),
(129, 'flaviojds089@gmail.com', 'flaviojds089@gmail.com', '', ''),
(130, 'floracysilva@hotmail.com', 'floracysilva@hotmail.com', '', ''),
(131, 'franchi.regina@ig.com.br', 'franchi.regina@ig.com.br', '', ''),
(132, 'francieleromadf@hotmail.com', 'francieleromadf@hotmail.com', '', ''),
(133, 'francisco.cunha@trf1.jus.br', 'francisco.cunha@trf1.jus.br', '', ''),
(134, 'francisco.x.lima@funasa.gov.br', 'francisco.x.lima@funasa.gov.br', '', ''),
(135, 'frederico.frp@gmail.com', 'frederico.frp@gmail.com', '', ''),
(136, 'ger_salves@yahoo.com.br', 'ger_salves@yahoo.com.br', '', ''),
(137, 'GERALDO.MAJELLA@hotmail.com', 'GERALDO.MAJELLA@hotmail.com', '', ''),
(138, 'geraldoney@terra.com.br', 'geraldoney@terra.com.br', '', ''),
(139, 'germannameloo@gmail.com', 'germannameloo@gmail.com', '', ''),
(140, 'gileneteles@gmail.com', 'gileneteles@gmail.com', '', ''),
(141, 'giokono@gmail.com', 'giokono@gmail.com', '', ''),
(142, 'gislene2801@gmail.com ', 'gislene2801@gmail.com ', '', ''),
(143, 'gislenemoraes28@gmail.com', 'gislenemoraes28@gmail.com', '', ''),
(144, 'glenister5@hotmail.com', 'glenister5@hotmail.com', '', ''),
(145, 'gomes.carmen@hotmail.com', 'gomes.carmen@hotmail.com', '', ''),
(146, 'gondim.edson@gmail.com', 'gondim.edson@gmail.com', '', ''),
(147, 'Goreth.rezende@gmail.com. ', 'Goreth.rezende@gmail.com. ', '', ''),
(148, 'gorettcouto@gmail.com', 'gorettcouto@gmail.com', '', ''),
(149, 'gorettcouto@hotmail.com', 'gorettcouto@hotmail.com', '', ''),
(150, 'hbagyn@gmail.com', 'hbagyn@gmail.com', '', ''),
(151, 'helenabsbmartins@hotmail.com', 'helenabsbmartins@hotmail.com', '', ''),
(153, 'hilarinoferreira@gmail.com', 'hilarinoferreira@gmail.com', '', ''),
(154, 'hilcey16@hotmail.com', 'hilcey16@hotmail.com', '', ''),
(155, 'hipertintas@bol.com.br', 'hipertintas@bol.com.br', '', ''),
(156, 'hmarques2502@yahoo. com.br', 'hmarques2502@yahoo. com.br', '', ''),
(157, 'hugomendespc@hotmail.com', 'hugomendespc@hotmail.com', '', ''),
(158, 'i.gmodesto@ig.com.br', 'i.gmodesto@ig.com.br', '', ''),
(159, 'idealimoveiscn@hotmail.com', 'idealimoveiscn@hotmail.com', '', ''),
(160, 'ionimoveis@globo.com', 'ionimoveis@globo.com', '', ''),
(161, 'ira.imc@gmail.com', 'ira.imc@gmail.com', '', ''),
(162, 'irisdamata@hotmail.com', 'irisdamata@hotmail.com', '', ''),
(163, 'isabelamunizfeitosa@gmail.com', 'isabelamunizfeitosa@gmail.com', '', ''),
(164, 'j_mauro_ferreira@hotmail.com', 'j_mauro_ferreira@hotmail.com', '', ''),
(165, 'j1601@hotmail.com', 'j1601@hotmail.com', '', ''),
(166, 'jaircampospai@gmail.com', 'jaircampospai@gmail.com', '', ''),
(167, 'janete_educ@hotmail.com', 'janete_educ@hotmail.com', '', ''),
(168, 'janyjaline@yahoo.com.br', 'janyjaline@yahoo.com.br', '', ''),
(169, 'jcsegurado@yahoo.com.br', 'jcsegurado@yahoo.com.br', '', ''),
(170, 'jdinizgodoy@gmail.com', 'jdinizgodoy@gmail.com', '', ''),
(171, 'jeovap@gmail.com', 'jeovap@gmail.com', '', ''),
(172, 'jeronimofranciscob@yahoo.com.br', 'jeronimofranciscob@yahoo.com.br', '', ''),
(173, 'jesomarciom@gmail.com', 'jesomarciom@gmail.com', '', ''),
(174, 'jgravina25@gmail.com', 'jgravina25@gmail.com', '', ''),
(175, 'jgravina25@gmail.com', 'jgravina25@gmail.com', '', ''),
(176, 'jjjonasmincri@gmail.com', 'jjjonasmincri@gmail.com', '', ''),
(177, 'joaoreboucas56@hotmail.com', 'joaoreboucas56@hotmail.com', '', ''),
(178, 'joaquimaguabranca@gmail.com', 'joaquimaguabranca@gmail.com', '', ''),
(179, 'joaquimorivaldo2010@hotmail.com', 'joaquimorivaldo2010@hotmail.com', '', ''),
(180, 'jorgelino114@gmail.com', 'jorgelino114@gmail.com', '', ''),
(181, 'jorgelu@embratel.com.br', 'jorgelu@embratel.com.br', '', ''),
(182, 'josecaetano1963@gmail.com', 'josecaetano1963@gmail.com', '', ''),
(183, 'josemgc1@gmail.com', 'josemgc1@gmail.com', '', ''),
(184, 'joserenatoresende@gmail.com', 'joserenatoresende@gmail.com', '', ''),
(185, 'josyandmary@hotmail.com', 'josyandmary@hotmail.com', '', ''),
(186, 'jpereira@sefaz.am.gov.br', 'jpereira@sefaz.am.gov.br', '', ''),
(187, 'jsantoscta@yahoo.com.br', 'jsantoscta@yahoo.com.br', '', ''),
(188, 'jsilvaresende@yahoo.com.br', 'jsilvaresende@yahoo.com.br', '', ''),
(189, 'juanita.costa@gmail.com', 'juanita.costa@gmail.com', '', ''),
(190, 'juliomaria@juliomaria.com.br', 'juliomaria@juliomaria.com.br', '', ''),
(191, 'juniorborys@gmail.com', 'juniorborys@gmail.com', '', ''),
(192, 'juniorcoelho2@hotmail.com', 'juniorcoelho2@hotmail.com', '', ''),
(193, 'JUNIORDU@uol.com.br', 'JUNIORDU@uol.com.br', '', ''),
(194, 'jvn47@hotmail.com', 'jvn47@hotmail.com', '', ''),
(195, 'karinerodovalho@hotmail.com', 'karinerodovalho@hotmail.com', '', ''),
(196, 'katita18@hotmail.com', 'katita18@hotmail.com', '', ''),
(197, 'keylarh@hotmail.com', 'keylarh@hotmail.com', '', ''),
(198, 'Ksalesmoreira@hotmail.com', 'Ksalesmoreira@hotmail.com', '', ''),
(199, 'lan.cell.sp@gmail.com', 'lan.cell.sp@gmail.com', '', ''),
(200, 'larissa@masterconsultoria.srv.br', 'larissa@masterconsultoria.srv.br', '', ''),
(201, 'lcpdprotese@hotmail.com', 'lcpdprotese@hotmail.com', '', ''),
(202, 'lfbnobre@hotmail.com', 'lfbnobre@hotmail.com', '', ''),
(203, 'lilacandido@hotmail.com', 'lilacandido@hotmail.com', '', ''),
(204, 'lizsiq4@hotmail.com', 'lizsiq4@hotmail.com', '', ''),
(205, 'lmaborges@yahoo.com.br', 'lmaborges@yahoo.com.br', '', ''),
(206, 'lucelio.meireles@gmail.com', 'lucelio.meireles@gmail.com', '', ''),
(207, 'lucia1portocastro@hotmail.com', 'lucia1portocastro@hotmail.com', '', ''),
(208, 'luciano.rotordiesel@hotmail.com', 'luciano.rotordiesel@hotmail.com', '', ''),
(209, 'lucilenelvieira@hotmail.com', 'lucilenelvieira@hotmail.com', '', ''),
(210, 'lucyguedes2001@hotmail.com', 'lucyguedes2001@hotmail.com', '', ''),
(211, 'ludmaria@hotmail.com', 'ludmaria@hotmail.com', '', ''),
(212, 'luisedubf@yahoo.com.br', 'luisedubf@yahoo.com.br', '', ''),
(213, 'luizedu_abreu@hotmail.com', 'luizedu_abreu@hotmail.com', '', ''),
(214, 'lutavares2809@hotmail.com', 'lutavares2809@hotmail.com', '', ''),
(215, 'malvessatas@yahoo.com.br', 'malvessatas@yahoo.com.br', '', ''),
(216, 'manoelens@hotmail.com', 'manoelens@hotmail.com', '', ''),
(217, 'MARCELO.BMELO@hotmail.com', 'MARCELO.BMELO@hotmail.com', '', ''),
(218, 'marcia1997@uol.com.br', 'marcia1997@uol.com.br', '', ''),
(219, 'margarethbailoni@gmail.com', 'margarethbailoni@gmail.com', '', ''),
(220, 'marheco@bol.com.br', 'marheco@bol.com.br', '', ''),
(221, 'maria.rodrigues@saude.gov.br', 'maria.rodrigues@saude.gov.br', '', ''),
(222, 'mariabarbara.p@hotmail.com', 'mariabarbara.p@hotmail.com', '', ''),
(223, 'mariaelaineborges@hotmail.com', 'mariaelaineborges@hotmail.com', '', ''),
(224, 'marisabruder@msn.com', 'marisabruder@msn.com', '', ''),
(225, 'marisol@saneago.com.br', 'marisol@saneago.com.br', '', ''),
(226, 'marlene@carvalhomc.com.br', 'marlene@carvalhomc.com.br', '', ''),
(227, 'martins.d.alva@hotmail.com', 'martins.d.alva@hotmail.com', '', ''),
(228, 'marysmachados@hotmail.com', 'marysmachados@hotmail.com', '', ''),
(229, 'maura.tania@hotmail.com', 'maura.tania@hotmail.com', '', ''),
(230, 'mauricioriberplas@hotmail.com', 'mauricioriberplas@hotmail.com', '', ''),
(231, 'mauro@sosoja.com.br', 'mauro@sosoja.com.br', '', ''),
(232, 'max.okamoto@gmail.com', 'max.okamoto@gmail.com', '', ''),
(233, 'maxmazareno@hotmail.com', 'maxmazareno@hotmail.com', '', ''),
(234, 'mchozen@gmail.com', 'mchozen@gmail.com', '', ''),
(235, 'mcristina.pinheiro14@gmail.com', 'mcristina.pinheiro14@gmail.com', '', ''),
(236, 'mctrezende@gmail.com', 'mctrezende@gmail.com', '', ''),
(237, 'mendes-climaco@hotmail.com', 'mendes-climaco@hotmail.com', '', ''),
(238, 'mendesemerson@hotmail.com', 'mendesemerson@hotmail.com', '', ''),
(239, 'mgamart@gmail.com', 'mgamart@gmail.com', '', ''),
(240, 'Mgomes2@brturbo.com.br', 'Mgomes2@brturbo.com.br', '', ''),
(241, 'mharliagr@gmail.com', 'mharliagr@gmail.com', '', ''),
(242, 'Misszilda@gmail.com', 'Misszilda@gmail.com', '', ''),
(243, 'mlibanio1@gmail.com', 'mlibanio1@gmail.com', '', ''),
(244, 'mmarquesn@gmail.com', 'mmarquesn@gmail.com', '', ''),
(245, 'moiseslimafilho@gmail.com', 'moiseslimafilho@gmail.com', '', ''),
(246, 'mouravivi2013@hotmail.com', 'mouravivi2013@hotmail.com', '', ''),
(247, 'msfodonto@hotmail.com', 'msfodonto@hotmail.com', '', ''),
(248, 'muciogomes@hotmail.com', 'muciogomes@hotmail.com', '', ''),
(249, 'mudjr@hotmail.com', 'mudjr@hotmail.com', '', ''),
(250, 'mzpzelia@gmail.com', 'mzpzelia@gmail.com', '', ''),
(251, 'nadiamaduleta@yahoo.com.br', 'nadiamaduleta@yahoo.com.br', '', ''),
(252, 'nairfugimoto@gmail.com', 'nairfugimoto@gmail.com', '', ''),
(253, 'neide_linda@hotmail.com', 'neide_linda@hotmail.com', '', ''),
(254, 'nelzylouza@gmail.com', 'nelzylouza@gmail.com', '', ''),
(255, 'neuzavc@hotmail.com', 'neuzavc@hotmail.com', '', ''),
(256, 'Nilodaglobo@globo.com', 'Nilodaglobo@globo.com', '', ''),
(257, 'nilzo.alves@hotmail.com', 'nilzo.alves@hotmail.com', '', ''),
(258, 'nirooliveira54@gmail.com', 'nirooliveira54@gmail.com', '', ''),
(259, 'nivio52@gmail.com', 'nivio52@gmail.com', '', ''),
(260, 'noemigrodrigues@hotmail.com', 'noemigrodrigues@hotmail.com', '', ''),
(261, 'norteminas@hotmail.com.br', 'norteminas@hotmail.com.br', '', ''),
(262, 'norval10@hotmail.com', 'norval10@hotmail.com', '', ''),
(263, 'nubia3010@yahoo.com.br', 'nubia3010@yahoo.com.br', '', ''),
(264, 'nubia3010@yahoo.om.br', 'nubia3010@yahoo.om.br', '', ''),
(265, 'olavina@assuncao.net', 'olavina@assuncao.net', '', ''),
(266, 'orlenepsilva@gmail.com', 'orlenepsilva@gmail.com', '', ''),
(267, 'osmarrcampos@yahoo.com.br', 'osmarrcampos@yahoo.com.br', '', ''),
(268, 'pachecao1950@hotmail.com ', 'pachecao1950@hotmail.com ', '', ''),
(270, 'PAMPLONALUCIANO33@gmail.com', 'PAMPLONALUCIANO33@gmail.com', '', ''),
(271, 'pastora_ana_min_@hotmail.com', 'pastora_ana_min_@hotmail.com', '', ''),
(272, 'pckhanna@hotmail.com ', 'pckhanna@hotmail.com ', '', ''),
(273, 'pcsanches_1@yahoo.com.br', 'pcsanches_1@yahoo.com.br', '', ''),
(274, 'pgcurisco@gmail.com', 'pgcurisco@gmail.com', '', ''),
(275, 'pinheirojv27@gmail.com', 'pinheirojv27@gmail.com', '', ''),
(276, 'pollianabahmad@gmail.com', 'pollianabahmad@gmail.com', '', ''),
(277, 'preludiomusica@gmail.com', 'preludiomusica@gmail.com', '', ''),
(278, 'priveplan@terra.com.br', 'priveplan@terra.com.br', '', ''),
(279, 'PROFESSORAHOLANDACANTRIZ@gmail.com', 'PROFESSORAHOLANDACANTRIZ@gmail.com', '', ''),
(280, 'psmv.fla@gmail.com', 'psmv.fla@gmail.com', '', ''),
(281, 'pt2lb@uol.com.br', 'pt2lb@uol.com.br', '', ''),
(282, 'r.a.cardoso@hotmail.com', 'r.a.cardoso@hotmail.com', '', ''),
(283, 'r246rodrigues@hotmail.com', 'r246rodrigues@hotmail.com', '', ''),
(284, 'rafael_amarante@uol.com.br', 'rafael_amarante@uol.com.br', '', ''),
(285, 'rafaelrodrigues82@hotmail.com', 'rafaelrodrigues82@hotmail.com', '', ''),
(286, 'rainer12alencar@hotmail.com', 'rainer12alencar@hotmail.com', '', ''),
(287, 'ramonrezende@hotmail.com', 'ramonrezende@hotmail.com', '', ''),
(288, 'renata_coqueiros@hotmail.com', 'renata_coqueiros@hotmail.com', '', ''),
(289, 'resendegc@hotmail.com', 'resendegc@hotmail.com', '', ''),
(290, 'rfbatitude@hotmail.com', 'rfbatitude@hotmail.com', '', ''),
(291, 'ribeiro.auri@yahoo.com.br', 'ribeiro.auri@yahoo.com.br', '', ''),
(292, 'ricardo.marrocos@funasa.gov.br', 'ricardo.marrocos@funasa.gov.br', '', ''),
(293, 'ricardo_oliveiracesar@hotmail.com', 'ricardo_oliveiracesar@hotmail.com', '', ''),
(294, 'ricardollobet@hotmail.com', 'ricardollobet@hotmail.com', '', ''),
(295, 'rivaldoaassis@hotmail.com', 'rivaldoaassis@hotmail.com', '', ''),
(296, 'robcampos2010@hotmail.com', 'robcampos2010@hotmail.com', '', ''),
(297, 'robjosy@terra.com.br', 'robjosy@terra.com.br', '', ''),
(298, 'rodosbrasil@oi.com.br', 'rodosbrasil@oi.com.br', '', ''),
(299, 'rogerio.marinho@xerox.com', 'rogerio.marinho@xerox.com', '', ''),
(300, 'rogerio@1portodos.com.br', 'caleul99', 'sim', 'sim'),
(301, 'rogerio_o.araujo@hotmail.com', 'rogerio_o.araujo@hotmail.com', '', ''),
(302, 'rooseveltdiniz@hotmail.com', 'rooseveltdiniz@hotmail.com', '', ''),
(303, 'rosane.miotto@terra.com.br', 'rosane.miotto@terra.com.br', '', ''),
(304, 'rosangelampcsantos@gmail.com', 'rosangelampcsantos@gmail.com', '', ''),
(305, 'rosemcs912@hotmail.com', 'rosemcs912@hotmail.com', '', ''),
(306, 'rubensdivinodasilva@icloud.com', 'rubensdivinodasilva@icloud.com', '', ''),
(307, 'sabc1979@hotmail.com', 'sabc1979@hotmail.com', '', ''),
(308, 'sca53@hotmail.com', 'sca53@hotmail.com', '', ''),
(309, 'sec.diretoria@homehospital.com.br', 'sec.diretoria@homehospital.com.br', '', ''),
(310, 'serginhomed@hotmail.com', 'serginhomed@hotmail.com', '', ''),
(311, 'sergiosilva.caldas@gmail.com', 'sergiosilva.caldas@gmail.com', '', ''),
(312, 'shirleifariacunha@gmail.com', 'shirleifariacunha@gmail.com', '', ''),
(313, 'siledaalmeidaster@gmail.com', 'siledaalmeidaster@gmail.com', '', ''),
(314, 'silvestre@stm.gov.br', 'silvestre@stm.gov.br', '', ''),
(315, 'silvia@liberdade-contabil.com.br', 'silvia@liberdade-contabil.com.br', '', ''),
(316, 'silviabcvp@gmail.com', 'silviabcvp@gmail.com', '', ''),
(317, 'si-monevida@hotmail.com', 'si-monevida@hotmail.com', '', ''),
(318, 'siqueirakaroline@outlook.com', 'siqueirakaroline@outlook.com', '', ''),
(319, 'smtere@msn.com', 'smtere@msn.com', '', ''),
(320, 'soares.inez@bol.com.br', 'soares.inez@bol.com.br', '', ''),
(321, 'soccorro_nary@hotmail.com', 'soccorro_nary@hotmail.com', '', ''),
(322, 'solange.queiroz@superig.com.br', 'solange.queiroz@superig.com.br', '', ''),
(323, 'solarrudama@hotmail.com', 'solarrudama@hotmail.com', '', ''),
(324, 'sotemari4@gmail.com', 'sotemari4@gmail.com', '', ''),
(325, 'spaula7@gmail.com', 'spaula7@gmail.com', '', ''),
(326, 'stampgrafica@hotmail.com', 'stampgrafica@hotmail.com', '', ''),
(327, 'stjafo@gmail.com', 'stjafo@gmail.com', 'sim', 'sim'),
(328, 'sucesso1contabilidade@gmail.com', 'sucesso1contabilidade@gmail.com', '', ''),
(329, 'sulamita_cunhabarros@yahoo.com.br', 'sulamita_cunhabarros@yahoo.com.br', '', ''),
(330, 'tafs_dc@yahoo.com.br', 'tafs_dc@yahoo.com.br', '', ''),
(331, 'taniaferreira.bsb@gmail.com', 'taniaferreira.bsb@gmail.com', '', ''),
(332, 'terplan@gmail.com', 'terplan@gmail.com', '', ''),
(333, 'TERRA_PLAN44@hotmail.com', 'TERRA_PLAN44@hotmail.com', '', ''),
(334, 'thedemedeiros@hotmail.com', 'thedemedeiros@hotmail.com', '', ''),
(335, 'thedemedeiros@hotmail.com', 'thedemedeiros@hotmail.com', '', ''),
(336, 'thiagopbarcelos@gmail.com', 'thiagopbarcelos@gmail.com', '', ''),
(337, 'toledoamarok@yahoo.com.br', 'toledoamarok@yahoo.com.br', '', ''),
(338, 'tolentinot@ig.com.br', 'tolentinot@ig.com.br', '', ''),
(339, 'toninhop@terra.com.br', 'toninhop@terra.com.br', '', ''),
(340, 'trsmartinez49@gmail.com', 'trsmartinez49@gmail.com', '', ''),
(341, 'tsct.df@gmail.com', 'tsct.df@gmail.com', '', ''),
(342, 'valdenorqj@gmail.com', 'valdenorqj@gmail.com', '', ''),
(343, 'VALERIA.M.FERREIRA@hotmail.com', 'VALERIA.M.FERREIRA@hotmail.com', '', ''),
(344, 'valeria.neiva@embrapa.br', 'valeria.neiva@embrapa.br', '', ''),
(345, 'valfredo.valle@hotmail.com', 'valfredo.valle@hotmail.com', '', ''),
(346, 'valtecimachado@gmail.com', 'valtecimachado@gmail.com', '', ''),
(347, 'van101960@outlook.com.br', 'wavapaju', '', ''),
(348, 'vanessasempresa@gmail.com', 'vanessasempresa@gmail.com', '', ''),
(349, 'vanessatsx@hotmail.com', 'vanessatsx@hotmail.com', '', ''),
(350, 'vanilda.alcantara@caixa.gov.br', 'vanilda.alcantara@caixa.gov.br', '', ''),
(351, 'vendas@papellink.com.br', 'vendas@papellink.com.br', '', ''),
(352, 'vera muniz @gmail. com', 'vera muniz @gmail. com', '', ''),
(353, 'virtualbel@gmail.com', 'virtualbel@gmail.com', '', ''),
(354, 'virtualjogos@hotmail.com', 'virtualjogos@hotmail.com', '', ''),
(355, 'vivianelorenco@hotmail.com', 'vivianelorenco@hotmail.com', '', ''),
(356, 'waniafbertanha@gmail.com', 'waniafbertanha@gmail.com', '', ''),
(357, 'werleypereira@ig.com.br', 'werleypereira@ig.com.br', '', ''),
(358, 'weuderrr@gmail.com', 'weuderrr@gmail.com', '', ''),
(359, 'wic.felix@yahoo.com.br', 'wic.felix@yahoo.com.br', '', ''),
(360, 'will_rodrigue@hotmail.com', 'will_rodrigue@hotmail.com', '', ''),
(361, 'willemmadison@globo.com', 'willemmadison@globo.com', '', ''),
(362, 'williana.bezerra@gmail.com', 'williana.bezerra@gmail.com', '', ''),
(363, 'wjcal22@hotmail.com', 'wjcal22@hotmail.com', '', ''),
(364, 'wjoselyra@hotmail.com', 'wjoselyra@hotmail.com', '', ''),
(365, 'xandisato@gmail.com', 'xandisato@gmail.com', '', ''),
(366, 'YURIAB11@hotmail.com', 'YURIAB11@hotmail.com', '', ''),
(367, 'zenilda@saneago.com.br', 'zenilda@saneago.com.br', '', ''),
(369, 'acraposo@1portodos.com.br', 'caleul99', '', ''),
(370, 'caleul@1portodos.com.br', 'caleul99', '', ''),
(371, 'cledson.marques@hotmail.com', 'cledson.marques@hotmail.com', '', ''),
(372, 'annacarvalho52@hotmail.com', 'annacarvalho52@hotmail.com', '', ''),
(373, 'rosane.miotto@terra.com.br', 'rosane.miotto@terra.com.br', '', ''),
(374, 'mcristina.pinheiro14@gmail.com', 'mcristina.pinheiro14@gmail.com', '', ''),
(375, 'Ladobbb@hotmail.com', 'Ladobbb@hotmail.com', '', ''),
(376, 'marheco@bol.com.br', 'marheco@bol.com.br', '', ''),
(377, 'emijecsan@hotmail.com', 'emijecsan@hotmail.com', '', ''),
(378, 'emijecsan@hotmail.com', 'emijecsan@hotmail.com', '', ''),
(379, 'doraadv@terra.com.br', 'doraadv@terra.com.br', '', ''),
(380, 'emijecsan@hotmail.com', 'emijecsan@hotmail.com', '', ''),
(381, 'amabialacerda@gmail.com', 'amabialacerda@gmail.com', 'sim', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ANIMAIS`
--
ALTER TABLE `ANIMAIS`
  ADD PRIMARY KEY (`COD_AN`);

--
-- Indexes for table `cadastro`
--
ALTER TABLE `cadastro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cadastro_adm`
--
ALTER TABLE `cadastro_adm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `convencao`
--
ALTER TABLE `convencao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `convencao_propalt`
--
ALTER TABLE `convencao_propalt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dependente_adm`
--
ALTER TABLE `dependente_adm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `falesindico`
--
ALTER TABLE `falesindico`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PROPRIETARIOS`
--
ALTER TABLE `PROPRIETARIOS`
  ADD PRIMARY KEY (`COD_PROP`);

--
-- Indexes for table `tab_docfiscal`
--
ALTER TABLE `tab_docfiscal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tab_historico_documento`
--
ALTER TABLE `tab_historico_documento`
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
-- AUTO_INCREMENT for table `cadastro`
--
ALTER TABLE `cadastro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=442;

--
-- AUTO_INCREMENT for table `cadastro_adm`
--
ALTER TABLE `cadastro_adm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `convencao`
--
ALTER TABLE `convencao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `convencao_propalt`
--
ALTER TABLE `convencao_propalt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `dependente_adm`
--
ALTER TABLE `dependente_adm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `falesindico`
--
ALTER TABLE `falesindico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tab_docfiscal`
--
ALTER TABLE `tab_docfiscal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tab_historico_documento`
--
ALTER TABLE `tab_historico_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
