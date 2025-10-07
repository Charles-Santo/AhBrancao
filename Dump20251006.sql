CREATE DATABASE  IF NOT EXISTS `db_ahbrancao` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_ahbrancao`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: db_ahbrancao
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbcategoria`
--

DROP TABLE IF EXISTS `tbcategoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbcategoria` (
  `codigo_categoria` int NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(50) NOT NULL,
  `descricao_categoria` varchar(150) NOT NULL,
  `imagem_categoria` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo_categoria`),
  UNIQUE KEY `nome_categoria` (`nome_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcategoria`
--

LOCK TABLES `tbcategoria` WRITE;
/*!40000 ALTER TABLE `tbcategoria` DISABLE KEYS */;
INSERT INTO `tbcategoria` VALUES (1,'Categoria Legal','ela é bacana',''),(2,'Melhores Memes','Contém os melhores memes já vistos na face da terra','');
/*!40000 ALTER TABLE `tbcategoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcomentario`
--

DROP TABLE IF EXISTS `tbcomentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbcomentario` (
  `codigo_comentario` int NOT NULL AUTO_INCREMENT,
  `texto_comentario` varchar(255) NOT NULL,
  `curtidas_comentario` int NOT NULL DEFAULT '0',
  `id_usuario` int NOT NULL,
  `codigo_meme` int NOT NULL,
  PRIMARY KEY (`codigo_comentario`),
  KEY `fk_tbUsuariotbComentario` (`id_usuario`),
  KEY `fk_tbMemetbComentario` (`codigo_meme`),
  CONSTRAINT `fk_tbMemetbComentario` FOREIGN KEY (`codigo_meme`) REFERENCES `tbmeme` (`codigo_meme`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbUsuariotbComentario` FOREIGN KEY (`id_usuario`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcomentario`
--

LOCK TABLES `tbcomentario` WRITE;
/*!40000 ALTER TABLE `tbcomentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbcomentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcurtidacomentario`
--

DROP TABLE IF EXISTS `tbcurtidacomentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbcurtidacomentario` (
  `id_usuario` int NOT NULL,
  `codigo_comentario` int NOT NULL,
  `data_curtida` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`,`codigo_comentario`),
  KEY `fk_tbCurtidaComentariotbComentario` (`codigo_comentario`),
  CONSTRAINT `fk_tbCurtidaComentariotbComentario` FOREIGN KEY (`codigo_comentario`) REFERENCES `tbcomentario` (`codigo_comentario`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbCurtidaComentariotbUsuario` FOREIGN KEY (`id_usuario`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcurtidacomentario`
--

LOCK TABLES `tbcurtidacomentario` WRITE;
/*!40000 ALTER TABLE `tbcurtidacomentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbcurtidacomentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcurtidameme`
--

DROP TABLE IF EXISTS `tbcurtidameme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbcurtidameme` (
  `id_usuario` int NOT NULL,
  `codigo_meme` int NOT NULL,
  `data_curtida` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`,`codigo_meme`),
  KEY `fk_tbCurtidaMemetbMeme` (`codigo_meme`),
  CONSTRAINT `fk_tbCurtidaMemetbMeme` FOREIGN KEY (`codigo_meme`) REFERENCES `tbmeme` (`codigo_meme`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbCurtidaMemetbUsuario` FOREIGN KEY (`id_usuario`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcurtidameme`
--

LOCK TABLES `tbcurtidameme` WRITE;
/*!40000 ALTER TABLE `tbcurtidameme` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbcurtidameme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbmeme`
--

DROP TABLE IF EXISTS `tbmeme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbmeme` (
  `codigo_meme` int NOT NULL AUTO_INCREMENT,
  `titulo_meme` varchar(50) NOT NULL,
  `texto_meme` varchar(100) NOT NULL,
  `isAprovado` tinyint(1) NOT NULL DEFAULT '0',
  `data_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem_meme` varchar(255) DEFAULT NULL,
  `idioma_meme` varchar(20) NOT NULL,
  `curtidas_meme` int NOT NULL DEFAULT '0',
  `codigo_admin_aprovador` int DEFAULT NULL,
  `id_usuario_autor` int NOT NULL,
  PRIMARY KEY (`codigo_meme`),
  KEY `fk_tbUsuariotbMeme` (`codigo_admin_aprovador`),
  KEY `fk_tbUsuarioadmtbMeme` (`id_usuario_autor`),
  CONSTRAINT `fk_tbUsuarioadmtbMeme` FOREIGN KEY (`id_usuario_autor`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbUsuariotbMeme` FOREIGN KEY (`codigo_admin_aprovador`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `ck_idiomatbMeme` CHECK ((`idioma_meme` in (_utf8mb4'Português',_utf8mb4'Espanhol',_utf8mb4'Inglês')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbmeme`
--

LOCK TABLES `tbmeme` WRITE;
/*!40000 ALTER TABLE `tbmeme` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbmeme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbmemecategoria`
--

DROP TABLE IF EXISTS `tbmemecategoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbmemecategoria` (
  `codigo_meme` int NOT NULL,
  `codigo_categoria` int NOT NULL,
  PRIMARY KEY (`codigo_meme`,`codigo_categoria`),
  KEY `fk_tbMemeCategoria_tbCategoria` (`codigo_categoria`),
  CONSTRAINT `fk_tbMemeCategoria_tbCategoria` FOREIGN KEY (`codigo_categoria`) REFERENCES `tbcategoria` (`codigo_categoria`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbMemeCategoria_tbMeme` FOREIGN KEY (`codigo_meme`) REFERENCES `tbmeme` (`codigo_meme`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbmemecategoria`
--

LOCK TABLES `tbmemecategoria` WRITE;
/*!40000 ALTER TABLE `tbmemecategoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbmemecategoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbseguidores`
--

DROP TABLE IF EXISTS `tbseguidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbseguidores` (
  `id_seguidor` int NOT NULL,
  `id_seguido` int NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_seguidor`,`id_seguido`),
  KEY `fk_seguidortbSeguido` (`id_seguido`),
  CONSTRAINT `fk_seguidortbSeguido` FOREIGN KEY (`id_seguido`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_seguidotbSeguidor` FOREIGN KEY (`id_seguidor`) REFERENCES `tbusuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbseguidores`
--

LOCK TABLES `tbseguidores` WRITE;
/*!40000 ALTER TABLE `tbseguidores` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbseguidores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbusuario`
--

DROP TABLE IF EXISTS `tbusuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbusuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `senha_usuario` varchar(255) NOT NULL,
  `email_usuario` varchar(255) NOT NULL,
  `nome_usuario` varchar(255) NOT NULL,
  `avatar_usuario` varchar(255) DEFAULT NULL,
  `funcao_usuario` varchar(100) NOT NULL,
  `data_inicio_usuario` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao_usuario` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email_usuario` (`email_usuario`),
  UNIQUE KEY `nome_usuario` (`nome_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbusuario`
--

LOCK TABLES `tbusuario` WRITE;
/*!40000 ALTER TABLE `tbusuario` DISABLE KEYS */;
INSERT INTO `tbusuario` VALUES (1,'$2y$10$9eCubRgwhYrvSEq/bQW4OeaO0jXLpTWDp2u8K5ztsTRhOxISFqcQO','Admin_malvado@Gmail.com','Admin do Mal','','Admin','2025-10-06 23:40:30','2025-10-06 23:40:30'),(2,'1234','userBeta@Gmail.com','Beta','','user','2025-10-06 23:40:30','2025-10-06 23:40:30');
/*!40000 ALTER TABLE `tbusuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-06 23:43:43
