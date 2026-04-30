-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para receitas_db
CREATE DATABASE IF NOT EXISTS `receitas_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `receitas_db`;

-- Copiando estrutura para tabela receitas_db.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela receitas_db.clients: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela receitas_db.ingredientes
CREATE TABLE IF NOT EXISTS `ingredientes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `receita_id` int DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `receita_id` (`receita_id`),
  CONSTRAINT `ingredientes_ibfk_1` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela receitas_db.ingredientes: ~31 rows (aproximadamente)
INSERT INTO `ingredientes` (`ID`, `receita_id`, `nome`) VALUES
	(96, 3, 'cenoura'),
	(97, 3, 'farinha'),
	(98, 3, 'chocolate'),
	(99, 3, 'cobertura'),
	(100, 10, 'Ovos'),
	(101, 10, 'Açúcar refinado'),
	(102, 10, 'Manteiga em temperatura ambiente'),
	(103, 10, 'Leite de coco'),
	(104, 10, 'Leite integral'),
	(105, 10, 'Farinha de trigo peneirada'),
	(106, 10, 'Coco ralado seco ou fresco'),
	(107, 10, 'Fermento químico em pó'),
	(108, 10, 'Pitada de sal'),
	(109, 10, 'Leite condensado (para a calda).'),
	(110, 13, 'Espaguete ou Rigatoni de boa qualidade'),
	(111, 13, 'Guanciale (ou pancetta/toucinho defumado) cortado em cubos'),
	(112, 13, 'Gemas de ovos frescos'),
	(113, 13, 'Queijo Pecorino Romano ralado (ou Parmesão)'),
	(114, 13, 'Pimenta-do-reino moída na hora'),
	(115, 13, 'Sal.'),
	(116, 14, 'Secos'),
	(117, 14, '2 xícaras de farinha de trigo'),
	(118, 14, '1 colher de sopa de fermento em pó'),
	(119, 14, 'Liquidos'),
	(120, 14, '3 ovos'),
	(121, 14, '1 xícara de leite'),
	(122, 14, 'suco de 1 limão taiti'),
	(131, 15, 'Carne'),
	(132, 15, 'pão'),
	(133, 15, 'alface'),
	(134, 15, 'tomate');

-- Copiando estrutura para tabela receitas_db.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `title` varchar(150) NOT NULL,
  `deadline` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `ptoject_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela receitas_db.projects: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela receitas_db.receitas
CREATE TABLE IF NOT EXISTS `receitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) DEFAULT NULL,
  `descricao` text,
  `imagem` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela receitas_db.receitas: ~5 rows (aproximadamente)
INSERT INTO `receitas` (`id`, `titulo`, `descricao`, `imagem`, `created_at`) VALUES
	(3, 'Bolo de Cenoura', 'Prepare o forno: Pré-aqueça a \r\n e unte uma forma com manteiga e farinha.\r\nBata os líquidos: No liquidificador, adicione as cenouras, os ovos e o óleo. Bata bem até obter um creme homogêneo e sem pedaços de cenoura.\r\nMisture os secos: Em uma tigela, peneire a farinha de trigo e o açúcar. Despeje a mistura do liquidificador sobre os secos e mexa bem com um fouet ou colher até incorporar.\r\nFermento: Adicione o fermento e misture delicadamente.\r\nAssar: Despeje na forma e leve ao forno por cerca de 35 a 40 minutos.\r\nCobertura: Em uma panela, misture todos os ingredientes da cobertura e leve ao fogo baixo, mexendo sempre, até começar a soltar do fundo da panela (ponto de brigadeiro mole).\r\nFinalize: Despeje a cobertura quente sobre o bolo ainda morno.', '1776435285.jpg', '2026-04-11 23:11:58'),
	(10, 'Bolo de Côco', 'A Base: Bata o açúcar, os ovos e a manteiga até obter um creme claro e fofinho. Isso garante a leveza da massa.\r\n\r\nLíquidos e Secos: Adicione o leite de coco e o leite integral alternando com a farinha de trigo. Misture delicadamente para não desenvolver demais o glúten e o bolo não solar.\r\n\r\nFinalização da Massa: Incorpore o coco ralado e, por último, o fermento e a pitada de sal, misturando apenas o suficiente para homogeneizar.\r\n\r\nForno: Despeje em uma forma untada e enfarinhada. Leve ao forno preaquecido a 180°C por aproximadamente 40 minutos (ou até que o palito saia limpo).\r\n\r\nA Calda (Opcional): Para um bolo bem molhadinho, misture um pouco de leite condensado com leite de coco e regue o bolo ainda morno, logo após fazer furinhos com um garfo.\r\n\r\nO Toque Final: Finalize polvilhando mais coco ralado por cima para dar textura e um visual caprichado.\r\n\r\nDica de mestre: Se quiser um "Bolo Gelado" (aquele de embrulhar no papel alumínio), deixe ele na geladeira por pelo menos 4 horas após colocar a calda. Ele fica muito mais saboroso quando está bem frio!', '1776435304.jpg', '2026-04-17 13:32:03'),
	(13, 'Macarrão carbonara', 'A Carne: Em uma frigideira grande e fria, coloque o guanciale e ligue o fogo baixo. Deixe a gordura derreter lentamente até que os cubinhos fiquem dourados e crocantes. Reserve a carne e a gordura na própria frigideira (desligada).\r\n\r\nO Creme: Em uma tigela, misture as gemas com o queijo ralado e muita pimenta-do-reino. Bata com um garfo até formar uma pasta grossa. Adicione uma colher de água morna da massa para ajudar a emulsificar.\r\n\r\nA Massa: Cozinhe o macarrão em água salgada (use menos sal que o normal, pois o queijo e a carne já são salgados) até ficar al dente.\r\n\r\nA União: Escorra a massa e jogue-a diretamente na frigideira com o guanciale. Misture bem para que a massa seja envolvida pela gordura da carne.\r\n\r\nO Pulo do Gato: Com o fogo desligado (essencial para não fazer ovos mexidos), despeje o creme de gemas sobre a massa. Adicione uma concha da água do cozimento.\r\n\r\nEmulsão: Misture vigorosamente e sem parar. O calor residual da massa e da água vai cozinhar as gemas levemente, criando um molho cremoso e brilhante.\r\n\r\nDica de mestre: Nunca use creme de leite! A cremosidade do verdadeiro carbonara vem exclusivamente da emulsão da gordura, das gemas e da água rica em amido do cozimento da massa.', '1776435397_carbonara.jpg', '2026-04-17 14:16:37'),
	(14, 'Bolo de limão', 'Massa: No liquidificador, bata os ovos, o óleo, o leite e o suco de limão. Em uma tigela, misture o açúcar e a farinha. Despeje o líquido sobre os secos e misture até ficar homogêneo. Por fim, adicione as raspas e o fermento, mexendo delicadamente.\r\n\r\nAssar: Despeje em uma forma untada e leve ao forno preaquecido a 180°C por cerca de 40 minutos.\r\n\r\nCobertura: Basta misturar o leite condensado com o suco de limão (ele vai engrossar naturalmente) e espalhar sobre o bolo já frio.', '69e3f1a401d8c.jpg', '2026-04-18 21:03:32'),
	(15, 'Hamburguer caseiro', 'Modele os Discos: Divida a carne em 4 bolas de cerca de 170g a 180g. Jogue a bola de uma mão para a outra com força (isso chama-se "bater a carne" para tirar o ar). Modele em formato de disco, deixando-os um pouco maiores que o pão, pois eles encolhem ao fritar. Faça uma pequena pressão no centro do disco com o polegar para evitar que ele estufe.\r\n\r\nPrepare o Pão: Corte os pães ao meio e passe um pouco de manteiga. Sele-os na frigideira quente até dourarem. Isso cria uma "barreira" para o suco da carne não encharcar o pão.\r\n\r\nFrite a Carne: Aumente o fogo. Quando a frigideira estiver bem quente, coloque os discos. Só agora tempere o lado de cima com sal e pimenta. Não mexa na carne por uns 3 a 4 minutos para criar aquela crostinha deliciosa.\r\n\r\nVire e Finalize: Vire o hambúrguer, tempere o lado que ficou para cima e coloque o queijo. Se quiser que o queijo derreta mais rápido, pingue uma gota de água no canto da frigideira e abafe com uma tampa por 30 segundos.\r\n\r\nDescanso: Retire a carne e deixe descansar por 1 ou 2 minutos antes de montar. Isso faz os sucos se redistribuírem e não molharem todo o pão.', '69e619c25a591.jpg', '2026-04-20 12:19:14');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
